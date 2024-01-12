<?php

namespace App\Service;

use ItkDev\Serviceplatformen\Service\SF1500\SF1500;
use ItkDev\Serviceplatformen\Service\SF1514\SF1514;
use ItkDev\Serviceplatformen\Service\SoapClient;

class SF1500Service
{
    /**
     * The SF1500 service.
     */
    private ?SF1500 $sf1500 = null;

    public function __construct(private readonly CertificateLocator $certificateLocator, private readonly array $options)
    {
    }

    /**
     * Gets SF1500 Service.
     */
    public function getSF1500(): SF1500
    {
        if (null === $this->sf1500) {
            $this->setupSF1500();
        }

        if ($this->certificateLocator->tokenShouldBeRefreshed()) {
            $this->setupSF1500();
        }

        return $this->sf1500;
    }

    private function setupSF1500()
    {
        $certificateSettings = $this->options;

        $soapClient = new SoapClient([
            'cache_expiration_time' => ['tomorrow'],
        ]);

        $options = [
            'certificate_locator' => $this->certificateLocator->getCertificateLocator(),
            'authority_cvr' => $certificateSettings['authority_cvr'],
            'sts_applies_to' => $certificateSettings['sts_applies_to'],
            'test_mode' => $certificateSettings['test_mode'],
        ];

        $sf1514 = new SF1514($soapClient, $options);

        unset($options['sts_applies_to']);

        $this->sf1500 = new SF1500($sf1514, $options);
    }
}
