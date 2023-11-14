<?php

namespace App\Service;

use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;
use Symfony\Component\HttpFoundation\Response;

class FetchData
{
    use LoggerAwareTrait;

    public function __construct(private readonly FetchServiceFactory $fetchServiceFactory)
    {
        $this->setLogger(new NullLogger());
    }

    public function fetch(string $dataType, $pageSize = 1000, $max = null)
    {
        $this->logger->debug('Fetching data');

        if ('all' === $dataType) {
            $this->fetchAll($pageSize, $max);
        } else {
            $service = match ($dataType) {
                'person' => $this->fetchServiceFactory->personService($this->logger),
                'bruger' => $this->fetchServiceFactory->brugerService($this->logger),
                'adresse' => $this->fetchServiceFactory->adresseService($this->logger),
                'organisationfunktion' => $this->fetchServiceFactory->organisationFunktionService($this->logger),
                'organisationenhed' => $this->fetchServiceFactory->organisationEnhedService($this->logger),
            };

            $service->fetch($pageSize, $max);
        }
    }

    private function fetchAll(int $pageSize, $max = null)
    {
        // Person data.
        $personService = $this->fetchServiceFactory->personService($this->logger);
        $personService->fetch($pageSize, $max);

        // Bruger data.
        $brugerService = $this->fetchServiceFactory->brugerService($this->logger);
        $brugerService->fetch($pageSize, $max);

        // Adresse data.
        $adresseService = $this->fetchServiceFactory->adresseService($this->logger);
        $adresseService->fetch($pageSize, $max);

        // OrganisationFunktion data.
        $organisationFunktionService = $this->fetchServiceFactory->organisationFunktionService($this->logger);
        $organisationFunktionService->fetch($pageSize, $max);

        // OrganisationEnhed data.
        $organisationEnhedService = $this->fetchServiceFactory->organisationEnhedService($this->logger);
        $organisationEnhedService->fetch($pageSize, $max);
    }


}
