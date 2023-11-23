<?php

namespace App\State;

use ApiPlatform\Exception\InvalidIdentifierException;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Exception\ProviderException;
use App\Repository\Model\FunktionsDataRepository;
use App\Repository\Model\OrganisationDataRepository;

readonly class FunktionOrganisationPathProvider implements ProviderInterface
{
    public function __construct(private FunktionsDataRepository $funktionsDataRepository, private OrganisationDataRepository $organisationDataRepository)
    {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        if (!isset($uriVariables['id'])) {
            throw new InvalidIdentifierException('Could not find id in uri');
        }

        $id = $uriVariables['id'];

        $funktion = $this->funktionsDataRepository->findOneBy(['id' => $id]);

        if (!$funktion) {
            throw new ProviderException(sprintf('Could not find funktion with id %s', $id));
        }

        $tilknyttetEnhedId = $funktion->getTilknyttetEnhedId();

        $organisationer = [];

        // Traverse through enheder until top is reached.
        while (null !== $tilknyttetEnhedId) {
            $organisation = $this->organisationDataRepository->findOneBy(['id' => $tilknyttetEnhedId]);

            if (!$organisation) {
                throw new ProviderException(sprintf('Error building organization path (parent organization with ID %s does not exist)', $tilknyttetEnhedId));
            }

            $organisationer[] = $organisation;

            $tilknyttetEnhedId = $organisation->getOverordnetId();
        }

        return $organisationer;
    }
}
