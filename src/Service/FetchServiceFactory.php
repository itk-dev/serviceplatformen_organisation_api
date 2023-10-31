<?php

namespace App\Service;

use App\Service\SF1500\AdresseFetchService;
use App\Service\SF1500\BrugerFetchService;
use App\Service\SF1500\OrganisationEnhedFetchService;
use App\Service\SF1500\OrganisationFunktionFetchService;
use App\Service\SF1500\PersonFetchService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class FetchServiceFactory
{
    public function __construct(private readonly EntityManagerInterface $entityManager, private readonly SF1500Service $sf1500Service)
    {
    }

    public function personService(?LoggerInterface $logger): PersonFetchService
    {
        $personFetchService = new PersonFetchService($this->entityManager, $this->sf1500Service);
        $personFetchService->setLogger($logger);

        return $personFetchService;
    }

    public function brugerService(?LoggerInterface $logger): BrugerFetchService
    {
        $brugerFetchService = new BrugerFetchService($this->entityManager, $this->sf1500Service);
        $brugerFetchService->setLogger($logger);

        return $brugerFetchService;
    }

    public function adresseService(?LoggerInterface $logger): AdresseFetchService
    {
        $adresseFetchService = new AdresseFetchService($this->entityManager, $this->sf1500Service);
        $adresseFetchService->setLogger($logger);

        return $adresseFetchService;
    }

    public function organisationFunktionService(?LoggerInterface $logger): OrganisationFunktionFetchService
    {
        $organisationFunktionService = new OrganisationFunktionFetchService($this->entityManager, $this->sf1500Service);
        $organisationFunktionService->setLogger($logger);

        return $organisationFunktionService;
    }

    public function organisationEnhedService(?LoggerInterface $logger): OrganisationEnhedFetchService
    {
        $organisationEnhedService = new OrganisationEnhedFetchService($this->entityManager, $this->sf1500Service);
        $organisationEnhedService->setLogger($logger);

        return $organisationEnhedService;
    }
}