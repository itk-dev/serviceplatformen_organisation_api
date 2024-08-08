<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Exception\InvalidProviderRequestException;
use App\Repository\Model\FunktionRepository;
use App\Repository\Model\OrganisationRepository;

class FunktionOrganisationProvider implements ProviderInterface
{
    public function __construct(private FunktionRepository $funktionsDataRepository, private OrganisationRepository $organisationDataRepository)
    {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        if (!isset($uriVariables['id'])) {
            throw new InvalidProviderRequestException('Could not find id in uri');
        }

        $id = $uriVariables['id'];

        $funktion = $this->funktionsDataRepository->findOneBy(['id' => $id]);

        if (!$funktion) {
            throw new InvalidProviderRequestException(sprintf('Could not find funktion with id %s', $id));
        }

        $tilknyttetEnhedId = $funktion->getTilknyttetEnhedId();

        $organisation = $this->organisationDataRepository->findOneBy(['id' => $tilknyttetEnhedId]);

        if (!$organisation) {
            throw new InvalidProviderRequestException(sprintf('Could not find organisation with id %s', $id));
        }

        return $organisation;
    }
}
