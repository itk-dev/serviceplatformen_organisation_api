<?php

namespace App\Service\SF1500;

use App\Entity\SF1500\PersonRegistrering;
use App\Entity\SF1500\PersonRegistreringEgenskab;
use ItkDev\Serviceplatformen\SF1500\Person\ServiceType\_List;
use ItkDev\Serviceplatformen\SF1500\Person\ServiceType\Soeg;
use ItkDev\Serviceplatformen\SF1500\Person\StructType\EgenskabType;
use ItkDev\Serviceplatformen\SF1500\Person\StructType\FiltreretOejebliksbilledeType;
use ItkDev\Serviceplatformen\SF1500\Person\StructType\ListInputType;
use ItkDev\Serviceplatformen\SF1500\Person\StructType\RegistreringType;
use ItkDev\Serviceplatformen\SF1500\Person\StructType\SoegInputType;
use ItkDev\Serviceplatformen\SF1500\Person\StructType\SoegOutputType;

class PersonDataFetcher extends AbstractDataFetcher
{
    protected const DATA_TYPE = 'person';

    public function fetchData(int $pageSize, int $total, int $max): int
    {
        $request = (new SoegInputType())
            ->setMaksimalAntalKvantitet(min($pageSize, $max - $total))
            ->setFoersteResultatReference($total)
        ;

        /** @var SoegOutputType $data */
        $soeg = $this->clientSoeg()->soeg($request);

        $ids = $soeg->getIdListe()->getUUIDIdentifikator();

        if (!is_countable($ids) || empty($ids)) {
            return -1;
        }

        $personList = $this->clientList()->_list_11(new ListInputType($ids));

        foreach ($personList->getFiltreretOejebliksbillede() as /* @var FiltreretOejebliksbilledeType $oejebliksbillede */ $oejebliksbillede) {
            $this->handleOejebliksbillede($oejebliksbillede);
        }

        return count($ids);
    }

    public function clientSoeg(array $options = []): Soeg
    {
        return $this->sf1500Service->getSF1500()->getClient(Soeg::class, $options);
    }

    public function clientList(array $options = []): _List
    {
        return $this->sf1500Service->getSF1500()->getClient(_List::class, $options);
    }

    private function handleOejebliksbillede(FiltreretOejebliksbilledeType $oejebliksbillede): void
    {
        $this->handleRegistrering($oejebliksbillede->getObjektType()->getUUIDIdentifikator(), $oejebliksbillede->getRegistrering());
    }

    private function handleRegistrering(string $personId, array $registreringer): void
    {
        foreach ($registreringer as /* @var RegistreringType $registrering */ $registrering) {
            $personRegistrering = new PersonRegistrering();

            $personRegistrering
                ->setPersonId($personId)
            ;

            $this->entityManager->persist($personRegistrering);

            $this->handleEgenskab($personRegistrering, $registrering->getAttributListe()->getEgenskab());
        }
    }

    private function handleEgenskab(PersonRegistrering $personRegistrering, ?array $egenskaber): void
    {
        if (null === $egenskaber) {
            return;
        }

        foreach ($egenskaber as /* @var EgenskabType $egenskab */ $egenskab) {
            $personRegistreringEgenskab = new PersonRegistreringEgenskab();
            $personRegistrering->addEgenskaber($personRegistreringEgenskab);

            $personRegistreringEgenskab
                ->setNavnTekst($egenskab->getNavnTekst())
            ;
            // Left out due to undefined property.
            // Warning: Undefined property: ItkDev\Serviceplatformen\SF1500\Person\StructType\EgenskabType::$CPR-NummerTekst
            // $personRegistreringEgenskab->setCprNummerTekst($egenskab->getCPR_NummerTekst());

            $this->entityManager->persist($personRegistreringEgenskab);
        }
    }
}
