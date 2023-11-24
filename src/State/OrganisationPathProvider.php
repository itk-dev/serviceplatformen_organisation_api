<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Exception\InvalidProviderRequestException;
use App\Repository\Model\OrganisationRepository;

readonly class OrganisationPathProvider implements ProviderInterface
{
    public function __construct(private OrganisationRepository $organisationDataRepository)
    {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        if (!isset($uriVariables['id'])) {
            throw new InvalidProviderRequestException('Could not find id in uri');
        }

        $id = $uriVariables['id'];

        $organisationer = [];

        // Traverse through enheder until top is reached.
        while (null !== $id) {
            $organisation = $this->organisationDataRepository->findOneBy(['id' => $id]);

            if (!$organisation) {
                throw new InvalidProviderRequestException(sprintf('Error building organization path (parent organization with ID %s does not exist)', $id));
            }

            $organisationer[] = $organisation;

            $id = $organisation->getOverordnetId();
        }

        return $organisationer;
    }
}
