<?php

namespace App\Service;

use App\Exception\CertificateLocatorException;
use ItkDev\Serviceplatformen\Certificate\CertificateLocatorInterface;
use ItkDev\Serviceplatformen\Certificate\FilesystemCertificateLocator;
use ItkDev\VaultBundle\Service\Vault;

class CertificateLocator
{
    private const LOCATOR_TYPE_VAULT = 'vault';
    private const LOCATOR_TYPE_FILE_SYSTEM = 'file_system';

    public function __construct(private readonly Vault $vault, private readonly array $options)
    {
    }

    /**
     * Get certificate locator.
     */
    public function getCertificateLocator(): CertificateLocatorInterface
    {
        $settings = $this->options;
        $locatorType = $settings['certificate_locator_type'];

        if (self::LOCATOR_TYPE_VAULT === $locatorType) {
            $token = $this->vault->login($settings['vault_role_id'], $settings['vault_secret_id']);
            $secret = $this->vault->getSecret(
                token: $token,
                path: $settings['vault_path'],
                secret: $settings['certificate_secret'],
                key: $settings['vault_key'],
                version: '' === $settings['certificate_version'] ? null : (int) $settings['certificate_version'],
            );

            return new VaultCertificateLocator($secret->value, $settings['certificate_passphrase']);
        }

        if (self::LOCATOR_TYPE_FILE_SYSTEM === $locatorType) {
            $certificatePath = realpath($settings['certificate_path']) ?: null;
            if (null === $certificatePath) {
                throw new CertificateLocatorException(sprintf('Invalid certificate path %s', $settings['certificate_path']));
            }

            return new FilesystemCertificateLocator($certificatePath, $settings['certificate_passphrase']);
        }

        throw new CertificateLocatorException(sprintf('Invalid certificate locator type: %s', $locatorType));
    }
}
