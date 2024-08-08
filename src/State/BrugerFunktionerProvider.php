<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Exception\InvalidProviderRequestException;
use App\Repository\Model\FunktionRepository;

class BrugerFunktionerProvider implements ProviderInterface
{
    public function __construct(private FunktionRepository $funktionRepository)
    {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        if (!isset($uriVariables['id'])) {
            throw new InvalidProviderRequestException('Could not find id in uri');
        }

        return $this->funktionRepository->findBy(['brugerId' => $uriVariables['id']]);
    }
}
