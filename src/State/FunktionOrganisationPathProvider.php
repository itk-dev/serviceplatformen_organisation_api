<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Exception\InvalidProviderRequestException;
use App\Repository\Model\FunktionRepository;
use App\Repository\Model\OrganisationRepository;

readonly class FunktionOrganisationPathProvider implements ProviderInterface
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

        $organisationer = [];

        // Traverse through enheder until top is reached.
        while (null !== $tilknyttetEnhedId) {
            $organisation = $this->organisationDataRepository->findOneBy(['id' => $tilknyttetEnhedId]);

            if (!$organisation) {
                throw new InvalidProviderRequestException(sprintf('Error building organization path (parent organization with ID %s does not exist)', $tilknyttetEnhedId));
            }

            $organisationer[] = $organisation;

            $tilknyttetEnhedId = $organisation->getOverordnetId();
        }

        return $organisationer;
    }
}
