<?php

namespace App\Service;

use ItkDev\Serviceplatformen\Certificate\AbstractCertificateLocator;
use ItkDev\Serviceplatformen\Certificate\Exception\CertificateLocatorException;

/**
 * Certificate locator for a PEM certificate fetched from a vault.
 *
 * The value must hold the private key and the certificate chain as PEM blocks;
 * anything outside the blocks, such as the "Bag Attributes" lines printed by
 * openssl exports, is ignored. The certificate is kept in memory and only
 * written to a temporary file when a path is required; that file disappears
 * when the process ends.
 */
class VaultCertificateLocator extends AbstractCertificateLocator
{
    private const PEM_MARKER = '-----BEGIN ';

    /**
     * @param string $certificate PEM text with the private key and the certificate chain
     */
    public function __construct(private readonly string $certificate, string $passphrase = '')
    {
        parent::__construct($passphrase);
    }

    #[\Override]
    public function getCertificates(): array
    {
        return $this->readCertificate();
    }

    #[\Override]
    public function getCertificate(): string
    {
        $store = $this->readCertificate();

        return $store['pkey'].PHP_EOL.$store['cert'].implode('', $store['extracerts'] ?? []);
    }

    #[\Override]
    public function getAbsolutePathToCertificate(): string
    {
        // The static keeps the handle, and thereby the file, alive until shutdown.
        static $tmpFile = null;
        $tmpFile = tmpfile();
        if (false === $tmpFile) {
            throw new CertificateLocatorException('Could not create temporary certificate file.');
        }
        fwrite($tmpFile, $this->getCertificate());

        return stream_get_meta_data($tmpFile)['uri'];
    }

    #[\Override]
    public function jsonSerialize(): mixed
    {
        return parent::jsonSerialize() + [
            'certificate' => $this->hideSecret($this->certificate),
        ];
    }

    /**
     * Reads the PEM into the structure openssl_pkcs12_read() produces: pkey, cert and optional extracerts.
     */
    private function readCertificate(): array
    {
        $pem = str_replace("\r\n", "\n", trim($this->certificate));
        if (!str_contains($pem, self::PEM_MARKER)) {
            throw new CertificateLocatorException('Certificate is not in PEM format.');
        }
        if (!preg_match_all('/-----BEGIN ([A-Z ]+)-----\n.+?\n-----END \1-----/s', $pem, $matches)) {
            throw new CertificateLocatorException('No PEM blocks found in certificate.');
        }

        $privateKeyBlock = null;
        $certificateBlocks = [];
        foreach ($matches[0] as $index => $block) {
            $type = $matches[1][$index];
            if ('CERTIFICATE' === $type) {
                $certificateBlocks[] = $block."\n";
            } elseif (str_ends_with($type, 'PRIVATE KEY')) {
                $privateKeyBlock = $block."\n";
            }
        }
        if (null === $privateKeyBlock) {
            throw new CertificateLocatorException('No private key found in certificate.');
        }
        if ([] === $certificateBlocks) {
            throw new CertificateLocatorException('No certificate found in certificate.');
        }

        $privateKey = openssl_pkey_get_private($privateKeyBlock, $this->hasPassphrase() ? $this->getPassphrase() : null);
        if (false === $privateKey) {
            throw new CertificateLocatorException('Could not read private key.');
        }
        // The library expects the key unencrypted, as openssl_pkcs12_read() returns it.
        $exportedKey = '';
        if (!openssl_pkey_export($privateKey, $exportedKey)) {
            throw new CertificateLocatorException('Could not export private key.');
        }

        // The certificate belonging to the key is the leaf; the rest is the chain.
        $certificate = null;
        $chain = [];
        foreach ($certificateBlocks as $block) {
            if (null === $certificate && openssl_x509_check_private_key($block, $privateKey)) {
                $certificate = $block;
            } else {
                $chain[] = $block;
            }
        }
        if (null === $certificate) {
            throw new CertificateLocatorException('None of the certificates match the private key.');
        }

        $store = ['cert' => $certificate, 'pkey' => $exportedKey];
        if ([] !== $chain) {
            $store['extracerts'] = $chain;
        }

        return $store;
    }
}
