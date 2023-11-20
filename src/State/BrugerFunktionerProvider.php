<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Exception\ProviderException;
use App\Repository\FunktionsDataRepository;

readonly class BrugerFunktionerProvider implements ProviderInterface
{
    public function __construct(private FunktionsDataRepository $funktionsDataRepository)
    {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        if (!isset($uriVariables['id'])) {
            throw new ProviderException('Could not find id in uri');
        }

        return $this->funktionsDataRepository->findOneBy(['brugerId' => $uriVariables['id']]);
    }
}
