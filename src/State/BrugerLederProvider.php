<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Exception\InvalidProviderRequestException;
use App\Repository\Model\FunktionsDataRepository;

readonly class BrugerLederProvider implements ProviderInterface
{
    public function __construct(private FunktionsDataRepository $funktionsDataRepository, private array $options)
    {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        if (!isset($uriVariables['id'])) {
            throw new InvalidProviderRequestException('Could not find id in uri');
        }

        $id = $uriVariables['id'];

        $funktioner = $this->funktionsDataRepository->findBy(['brugerId' => $id]);

        if (!$funktioner) {
            throw new InvalidProviderRequestException(sprintf('Could not find funktion with id %s', $id));
        }

        $lederUuid = $this->options['test_mode'] ? $this->options['leder_rolle_uuid_test'] : $this->options['leder_rolle_uuid_prod'];

        $ledereResult = [];

        foreach ($funktioner as $funktion) {
            $ledere = $this->funktionsDataRepository->findBy(['funktionsType' => $lederUuid, 'tilknyttetEnhedId' => $funktion->getTilknyttetEnhedId()]);

            foreach ($ledere as $leder) {
                $ledereResult[] = $leder->getBrugerId();
            }
        }

        return $ledereResult;
    }
}
