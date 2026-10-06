<?php

namespace App\Service;

use ItkDev\Serviceplatformen\Certificate\AbstractCertificateLocator;
use ItkDev\Serviceplatformen\Certificate\Exception\CertificateLocatorException;

/**
 * Certificate locator for a PEM certificate fetched from a vault.
 *
 * The value must hold one unencrypted private key and the certificate chain,
 * leaf first, as PEM blocks; anything outside the blocks, such as the
 * "Bag Attributes" lines printed by openssl exports, is ignored. The
 * certificate is kept in memory and only written to a temporary file when a
 * path is required; that file disappears when the process ends.
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
     * Splits the PEM into the parts the library reads: pkey, cert and optional extracerts.
     */
    private function readCertificate(): array
    {
        $pem = str_replace("\r\n", "\n", trim($this->certificate));
        if (!str_contains($pem, self::PEM_MARKER)
            || !preg_match_all('/-----BEGIN ([A-Z ]+)-----\n.+?\n-----END \1-----/s', $pem, $matches)) {
            throw new CertificateLocatorException('Certificate is not in PEM format.');
        }

        $keys = [];
        $certificates = [];
        foreach ($matches[0] as $index => $block) {
            if ('CERTIFICATE' === $matches[1][$index]) {
                $certificates[] = $block."\n";
            } elseif (str_ends_with($matches[1][$index], 'PRIVATE KEY')) {
                $keys[] = $block."\n";
            }
        }
        if (1 !== count($keys) || [] === $certificates) {
            throw new CertificateLocatorException('Certificate must hold one private key and at least one certificate.');
        }

        // Leaf first, then the chain, as openssl and certificate issuers order them.
        $store = ['pkey' => $keys[0], 'cert' => array_shift($certificates)];
        if (!openssl_x509_check_private_key($store['cert'], $store['pkey'])) {
            throw new CertificateLocatorException('Private key does not match the first certificate.');
        }
        if ([] !== $certificates) {
            $store['extracerts'] = $certificates;
        }

        return $store;
    }
}
