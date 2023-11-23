<?php

namespace App\State;

use ApiPlatform\Exception\InvalidIdentifierException;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Repository\Model\FunktionsDataRepository;

readonly class BrugerFunktionerProvider implements ProviderInterface
{
    public function __construct(private FunktionsDataRepository $funktionsDataRepository)
    {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        if (!isset($uriVariables['id'])) {
            throw new InvalidIdentifierException('Could not find id in uri');
        }

        return $this->funktionsDataRepository->findBy(['brugerId' => $uriVariables['id']]);
    }
}
