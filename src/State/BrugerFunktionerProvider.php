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

    /**
     * {@inheritDoc}
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        if (!isset($uriVariables['id'])) {
            throw new ProviderException('Could not find id.');
        }

        $funktioner = $this->funktionsDataRepository->findBy(['brugerId' => $uriVariables['id']]);

//        $res = [];
//        foreach ($funktioner as $funktion) {
//            $res[$funktion->getId()] = [
//
//            ];
//        }

        return $funktioner;
    }
}
