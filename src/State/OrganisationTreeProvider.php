<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Exception\ProviderException;
use App\Repository\OrganisationDataRepository;

readonly class OrganisationTreeProvider implements ProviderInterface
{
    public function __construct(private OrganisationDataRepository $organisationDataRepository)
    {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        if (!isset($uriVariables['id'])) {
            throw new ProviderException('Could not find id in uri');
        }

        $id = $uriVariables['id'];

        $organisationer = [];

        // Traverse through enheder until top is reached.
        while (true) {
            $organisation = $this->organisationDataRepository->findOneBy(['id' => $id]);

            if (!$organisation) {
                throw new ProviderException(sprintf('Could not find organisation with id %s', $id));
            }

            $organisationer[] = $organisation;

            if (null === $organisation->getOverordnetId()) {
                break;
            }

            $id = $organisation->getOverordnetId();
        }

        return $organisationer;
    }
}
