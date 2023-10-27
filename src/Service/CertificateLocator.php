<?php

namespace App\Service;

use App\Exception\CertificateLocatorException;
use GuzzleHttp\Client;
use Http\Adapter\Guzzle7\Client as GuzzleAdapter;
use Http\Factory\Guzzle\RequestFactory;
use ItkDev\AzureKeyVault\Authorisation\VaultToken;
use ItkDev\AzureKeyVault\KeyVault\VaultSecret;
use ItkDev\Serviceplatformen\Certificate\AzureKeyVaultCertificateLocator;
use ItkDev\Serviceplatformen\Certificate\CertificateLocatorInterface;
use ItkDev\Serviceplatformen\Certificate\FilesystemCertificateLocator;

class CertificateLocator
{
    private const LOCATOR_TYPE_AZURE_KEY_VAULT = 'azure_key_vault';
    private const LOCATOR_TYPE_FILE_SYSTEM = 'file_system';

    public function __construct(private readonly array $options)
    {
    }

    /**
     * Get certificate locator.
     */
    public function getCertificateLocator(): CertificateLocatorInterface {
        $certificateSettings = $this->options;

        $locatorType = $certificateSettings['certificate_locator_type'];

        if (self::LOCATOR_TYPE_AZURE_KEY_VAULT === $locatorType) {
            $httpClient = new GuzzleAdapter(new Client());
            $requestFactory = new RequestFactory();

            $vaultToken = new VaultToken($httpClient, $requestFactory);

            $token = $vaultToken->getToken(
                $certificateSettings['certificate_tenant_id'],
                $certificateSettings['certificate_application_id'],
                $certificateSettings['certificate_client_secret'],
            );

            $vault = new VaultSecret(
                $httpClient,
                $requestFactory,
                $certificateSettings['certificate_name'],
                $token->getAccessToken()
            );

            return new AzureKeyVaultCertificateLocator(
                $vault,
                $certificateSettings['certificate_secret'],
                $certificateSettings['certificate_version'],
                $certificateSettings['certificate_passphrase'],
            );
        }
        elseif (self::LOCATOR_TYPE_FILE_SYSTEM === $locatorType) {
            $certificatepath = realpath($certificateSettings['certificate_path']) ?: NULL;
            if (NULL === $certificatepath) {
                throw new CertificateLocatorException(sprintf('Invalid certificate path %s', $certificateSettings['certificate_path']));
            }
            return new FilesystemCertificateLocator($certificatepath, $certificateSettings['certificate_passphrase']);
        }

        throw new CertificateLocatorException(sprintf('Invalid certificate locator type: %s', $locatorType));
    }
}