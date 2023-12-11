<?php

namespace App\Service\SF1500;

use App\Entity\SF1500\AdresseRegistrering;
use App\Entity\SF1500\AdresseRegistreringEgenskab;
use ItkDev\Serviceplatformen\SF1500\Adresse\ServiceType\_List;
use ItkDev\Serviceplatformen\SF1500\Adresse\ServiceType\Soeg;
use ItkDev\Serviceplatformen\SF1500\Adresse\StructType\EgenskabType;
use ItkDev\Serviceplatformen\SF1500\Adresse\StructType\FiltreretOejebliksbilledeType;
use ItkDev\Serviceplatformen\SF1500\Adresse\StructType\ListInputType;
use ItkDev\Serviceplatformen\SF1500\Adresse\StructType\RegistreringType;
use ItkDev\Serviceplatformen\SF1500\Adresse\StructType\RelationListeType;
use ItkDev\Serviceplatformen\SF1500\Adresse\StructType\SoegInputType;
use ItkDev\Serviceplatformen\SF1500\Adresse\StructType\SoegOutputType;
use ItkDev\Serviceplatformen\SF1500\Adresse\StructType\TilstandListeType;

class AdresseDataFetcher extends AbstractDataFetcher
{
    protected const DATA_TYPE = 'adresse';

    protected function fetchData(int $pageSize, int $total, int $max): int
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

        $brugerList = $this->clientList()->_list(new ListInputType($ids));

        foreach ($brugerList->getFiltreretOejebliksbillede() as /* @var FiltreretOejebliksbilledeType $oejebliksbillede */ $oejebliksbillede) {
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

    private function handleRegistrering(string $adresseId, ?array $registreringer): void
    {
        foreach ($registreringer as /* @var RegistreringType $registrering */ $registrering) {
            $adresseRegistrering = new AdresseRegistrering();

            $adresseRegistrering
                ->setAdresseId($adresseId)
            ;

            $this->entityManager->persist($adresseRegistrering);

            $this->handleEgenskab($adresseRegistrering, $registrering->getAttributListe()->getEgenskab());
            $this->handleRelation($adresseRegistrering, $registrering->getRelationListe());
            $this->handleTilstand($adresseRegistrering, $registrering->getTilstandListe());
        }
    }

    private function handleEgenskab(AdresseRegistrering $adresseRegistrering, ?array $egenskaber): void
    {
        if (null === $egenskaber) {
            return;
        }

        foreach ($egenskaber as /* @var EgenskabType $egenskab */ $egenskab) {
            $adresseRegistreringEgenskab = new AdresseRegistreringEgenskab();
            $adresseRegistrering->addEgenskaber($adresseRegistreringEgenskab);

            $adresseRegistreringEgenskab
                ->setAdresseTekst($egenskab->getAdresseTekst())
            ;

            $this->entityManager->persist($adresseRegistreringEgenskab);
        }
    }

    private function handleRelation(AdresseRegistrering $adresseRegistrering, ?RelationListeType $relationListeType): void
    {
        // Not needed for now.
    }

    private function handleTilstand(AdresseRegistrering $adresseRegistrering, ?TilstandListeType $tilstandListeType): void
    {
        // Not needed for now.
    }
}
