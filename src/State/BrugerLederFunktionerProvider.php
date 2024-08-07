<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Exception\InvalidProviderRequestException;
use App\Repository\Model\FunktionRepository;

class BrugerLederFunktionerProvider implements ProviderInterface
{
    public function __construct(private FunktionRepository $funktionRepository, private array $options)
    {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        if (!isset($uriVariables['id'])) {
            throw new InvalidProviderRequestException('Could not find id in uri');
        }

        $lederUuid = $this->options['test_mode'] ? $this->options['leder_rolle_uuid_test'] : $this->options['leder_rolle_uuid_prod'];

        return $this->funktionRepository->findBy(['brugerId' => $uriVariables['id'], 'funktionsType' => $lederUuid]);
    }
}
