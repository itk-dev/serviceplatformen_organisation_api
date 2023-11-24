<?php

namespace App\Service;

use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;

class FetchData
{
    use LoggerAwareTrait;

    public function __construct(private readonly FetchServiceFactory $fetchServiceFactory)
    {
        $this->setLogger(new NullLogger());
    }

    public function fetch(array $dataTypes, $pageSize = 1000, $max = null)
    {
        $this->logger->debug('Fetching data');

        $services = [];

        foreach ($dataTypes as $dataType) {
            $service = match ($dataType) {
                'person' => $this->fetchServiceFactory->personService($this->logger),
                'bruger' => $this->fetchServiceFactory->brugerService($this->logger),
                'adresse' => $this->fetchServiceFactory->adresseService($this->logger),
                'organisationfunktion' => $this->fetchServiceFactory->organisationFunktionService($this->logger),
                'organisationenhed' => $this->fetchServiceFactory->organisationEnhedService($this->logger),
            };

            $services[] = $service;
        }

        foreach ($services as $service) {
            $service->fetch($pageSize, $max);
        }
    }
}
