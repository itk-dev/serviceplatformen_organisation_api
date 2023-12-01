<?php

namespace App\Service;

use App\Service\SF1500\AdresseDataFetcher;
use App\Service\SF1500\BrugerDataFetcher;
use App\Service\SF1500\OrganisationEnhedDataFetcher;
use App\Service\SF1500\OrganisationFunktionDataFetcher;
use App\Service\SF1500\PersonDataFetcher;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

readonly class FetchServiceFactory
{
    public function __construct(private EntityManagerInterface $entityManager, private SF1500Service $sf1500Service)
    {
    }

    public function personService(?LoggerInterface $logger): PersonDataFetcher
    {
        $personFetchService = new PersonDataFetcher($this->entityManager, $this->sf1500Service);
        $personFetchService->setLogger($logger);

        return $personFetchService;
    }

    public function brugerService(?LoggerInterface $logger): BrugerDataFetcher
    {
        $brugerFetchService = new BrugerDataFetcher($this->entityManager, $this->sf1500Service);
        $brugerFetchService->setLogger($logger);

        return $brugerFetchService;
    }

    public function adresseService(?LoggerInterface $logger): AdresseDataFetcher
    {
        $adresseFetchService = new AdresseDataFetcher($this->entityManager, $this->sf1500Service);
        $adresseFetchService->setLogger($logger);

        return $adresseFetchService;
    }

    public function organisationFunktionService(?LoggerInterface $logger): OrganisationFunktionDataFetcher
    {
        $organisationFunktionService = new OrganisationFunktionDataFetcher($this->entityManager, $this->sf1500Service);
        $organisationFunktionService->setLogger($logger);

        return $organisationFunktionService;
    }

    public function organisationEnhedService(?LoggerInterface $logger): OrganisationEnhedDataFetcher
    {
        $organisationEnhedService = new OrganisationEnhedDataFetcher($this->entityManager, $this->sf1500Service);
        $organisationEnhedService->setLogger($logger);

        return $organisationEnhedService;
    }
}
