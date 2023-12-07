<?php

namespace App\Service\SF1500;

use App\Entity\SF1500\OrganisationFunktionRegistrering;
use App\Entity\SF1500\OrganisationFunktionRegistreringEgenskab;
use App\Entity\SF1500\OrganisationFunktionRegistreringFunktionstype;
use App\Entity\SF1500\OrganisationFunktionRegistreringTilknyttedeBrugere;
use App\Entity\SF1500\OrganisationFunktionRegistreringTilknyttedeEnheder;
use ItkDev\Serviceplatformen\SF1500\OrganisationFunktion\ServiceType\_List;
use ItkDev\Serviceplatformen\SF1500\OrganisationFunktion\ServiceType\Soeg;
use ItkDev\Serviceplatformen\SF1500\OrganisationFunktion\StructType\BrugerFlerRelationType;
use ItkDev\Serviceplatformen\SF1500\OrganisationFunktion\StructType\EgenskabType;
use ItkDev\Serviceplatformen\SF1500\OrganisationFunktion\StructType\FiltreretOejebliksbilledeType;
use ItkDev\Serviceplatformen\SF1500\OrganisationFunktion\StructType\GyldighedType;
use ItkDev\Serviceplatformen\SF1500\OrganisationFunktion\StructType\KlasseRelationType;
use ItkDev\Serviceplatformen\SF1500\OrganisationFunktion\StructType\ListInputType;
use ItkDev\Serviceplatformen\SF1500\OrganisationFunktion\StructType\LokalUdvidelseType;
use ItkDev\Serviceplatformen\SF1500\OrganisationFunktion\StructType\OrganisationEnhedFlerRelationType;
use ItkDev\Serviceplatformen\SF1500\OrganisationFunktion\StructType\RegistreringType;
use ItkDev\Serviceplatformen\SF1500\OrganisationFunktion\StructType\RelationListeType;
use ItkDev\Serviceplatformen\SF1500\OrganisationFunktion\StructType\SoegInputType;
use ItkDev\Serviceplatformen\SF1500\OrganisationFunktion\StructType\SoegOutputType;
use ItkDev\Serviceplatformen\SF1500\OrganisationFunktion\StructType\TilstandListeType;

class OrganisationFunktionDataFetcher extends AbstractDataFetcher
{
    protected const DATA_TYPE = 'organisation funktion';
    private ?TilstandListeType $tilstandListeType = null;

    protected function preFetchData(): void
    {
        $this->tilstandListeType = new TilstandListeType();
        $this->tilstandListeType->addToGyldighed(
            new GyldighedType(
                null,
                'Aktiv'
            )
        );
    }

    protected function fetchData(int $pageSize, int $total, int $max): int
    {
        $request = (new SoegInputType())
            ->setMaksimalAntalKvantitet(min($pageSize, $max - $total))
            ->setFoersteResultatReference($total)
            // Only want active objects.
            ->setTilstandListe($this->tilstandListeType)
        ;

        /** @var SoegOutputType $data */
        $soeg = $this->clientSoeg()->soeg($request);

        $ids = $soeg->getIdListe()->getUUIDIdentifikator();

        if (!is_countable($ids) || empty($ids)) {
            return -1;
        }

        $brugerList = $this->clientList()->_list_10(new ListInputType($ids));

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

    private function handleRegistrering(string $organisationFunktionId, array $registreringer): void
    {
        foreach ($registreringer as /* @var RegistreringType $registrering */ $registrering) {
            $organisationFunktionRegistrering = new OrganisationFunktionRegistrering();

            $organisationFunktionRegistrering
                ->setOrganisationFunktionId($organisationFunktionId)
            ;

            $this->entityManager->persist($organisationFunktionRegistrering);

            $this->handleEgenskab($organisationFunktionRegistrering, $registrering->getAttributListe()->getEgenskab());
            $this->handleGyldighed($organisationFunktionRegistrering, $registrering->getTilstandListe()->getGyldighed());
            $this->handleRelation($organisationFunktionRegistrering, $registrering->getRelationListe());
        }
    }

    private function handleEgenskab(OrganisationFunktionRegistrering $organisationFunktionRegistrering, ?array $egenskaber): void
    {
        if (null === $egenskaber) {
            return;
        }

        foreach ($egenskaber as /* @var EgenskabType $egenskab */ $egenskab) {
            $organisationFunktionRegistreringEgenskab = new OrganisationFunktionRegistreringEgenskab();
            $organisationFunktionRegistrering->addEgenskaber($organisationFunktionRegistreringEgenskab);

            $organisationFunktionRegistreringEgenskab
                ->setFunktionNavn($egenskab->getFunktionNavn())
            ;

            $this->entityManager->persist($organisationFunktionRegistreringEgenskab);
        }
    }

    private function handleGyldighed(OrganisationFunktionRegistrering $organisationFunktionRegistrering, ?array $gyldigheder): void
    {
        // Not needed for now.
    }

    private function handleRelation(OrganisationFunktionRegistrering $organisationFunktionRegistrering, ?RelationListeType $relation): void
    {
        if (null === $relation) {
            return;
        }

        $this->handleAdresser($organisationFunktionRegistrering, $relation->getAdresser());
        $this->handleFunktionstype($organisationFunktionRegistrering, $relation->getFunktionstype());
        $this->handleTilknyttedeOpgaver($organisationFunktionRegistrering, $relation->getTilknyttedeOpgaver());
        $this->handleTilknyttedeBrugere($organisationFunktionRegistrering, $relation->getTilknyttedeBrugere());
        $this->handleTilknyttedeEnheder($organisationFunktionRegistrering, $relation->getTilknyttedeEnheder());
        $this->handleTilknyttedeInteressefaellesskaber($organisationFunktionRegistrering, $relation->getTilknyttedeInteressefaellesskaber());
        $this->handleTilknyttedeOrganisationer($organisationFunktionRegistrering, $relation->getTilknyttedeOrganisationer());
        $this->handleTilknyttedePersoner($organisationFunktionRegistrering, $relation->getTilknyttedePersoner());
        $this->handleTilknyttedeItSystemer($organisationFunktionRegistrering, $relation->getTilknyttedeItSystemer());
        $this->handleLokalUdvidelse($organisationFunktionRegistrering, $relation->getLokalUdvidelse());
    }

    private function handleAdresser(OrganisationFunktionRegistrering $organisationFunktionRegistrering, ?array $adresser): void
    {
        // Not needed for now.
    }

    private function handleFunktionstype(OrganisationFunktionRegistrering $organisationFunktionRegistrering, ?KlasseRelationType $funktionstype): void
    {
        if (null === $funktionstype) {
            return;
        }

        $organisationFunktionRegistreringFunktionstype = new OrganisationFunktionRegistreringFunktionstype();
        $organisationFunktionRegistrering->setFunktionstype($organisationFunktionRegistreringFunktionstype);

        // Reference id.
        $referenceId = $funktionstype->getReferenceID();

        $organisationFunktionRegistreringFunktionstype
            ->setReferenceIdUUIDIdentifikator($referenceId->getUUIDIdentifikator())
        ;

        $this->entityManager->persist($organisationFunktionRegistreringFunktionstype);
    }

    private function handleTilknyttedeOpgaver(OrganisationFunktionRegistrering $organisationFunktionRegistrering, ?array $tilknyttedeOpgaver): void
    {
        // Not needed for now.
    }

    private function handleTilknyttedeBrugere(OrganisationFunktionRegistrering $organisationFunktionRegistrering, ?array $tilknyttedeBrugere): void
    {
        if (null === $tilknyttedeBrugere) {
            return;
        }

        foreach ($tilknyttedeBrugere as /* @var BrugerFlerRelationType $tilknyttedeBruger */ $tilknyttedeBruger) {
            $organisationFunktionRegistreringTilknyttedeBrugere = new OrganisationFunktionRegistreringTilknyttedeBrugere();
            $organisationFunktionRegistrering->addTilknyttedeBrugere($organisationFunktionRegistreringTilknyttedeBrugere);

            // Reference id.
            $referenceId = $tilknyttedeBruger->getReferenceID();

            $organisationFunktionRegistreringTilknyttedeBrugere
                ->setReferenceIdUUIDIdentifikator($referenceId->getUUIDIdentifikator())
            ;

            $this->entityManager->persist($organisationFunktionRegistreringTilknyttedeBrugere);
        }
    }

    private function handleTilknyttedeEnheder(OrganisationFunktionRegistrering $organisationFunktionRegistrering, ?array $tilknyttedeEnheder): void
    {
        if (null === $tilknyttedeEnheder) {
            return;
        }

        foreach ($tilknyttedeEnheder as /* @var OrganisationEnhedFlerRelationType $tilknyttedeEnhed */ $tilknyttedeEnhed) {
            $organisationFunktionRegistreringTilknyttedeEnheder = new OrganisationFunktionRegistreringTilknyttedeEnheder();
            $organisationFunktionRegistrering->addTilknyttedeEnheder($organisationFunktionRegistreringTilknyttedeEnheder);

            // Reference id.
            $referenceId = $tilknyttedeEnhed->getReferenceID();

            $organisationFunktionRegistreringTilknyttedeEnheder
                ->setReferenceIdUUIDIdentifikator($referenceId->getUUIDIdentifikator())
            ;

            $this->entityManager->persist($organisationFunktionRegistreringTilknyttedeEnheder);
        }
    }

    private function handleTilknyttedeInteressefaellesskaber(OrganisationFunktionRegistrering $organisationFunktionRegistrering, ?array $tilknyttedeInteressefaellesskaber): void
    {
        // Not needed for now.
    }

    private function handleTilknyttedeOrganisationer(OrganisationFunktionRegistrering $organisationFunktionRegistrering, ?array $tilknyttedeOrganisationer): void
    {
        // Not needed for now.
    }

    private function handleTilknyttedePersoner(OrganisationFunktionRegistrering $organisationFunktionRegistrering, ?array $tilknyttedePersoner): void
    {
        // Not needed for now.
    }

    private function handleTilknyttedeItSystemer(OrganisationFunktionRegistrering $organisationFunktionRegistrering, ?array $tilknyttedeItSystemer): void
    {
        // Not needed for now.
    }

    private function handleLokalUdvidelse(OrganisationFunktionRegistrering $organisationFunktionRegistrering, ?LokalUdvidelseType $lokalUdvidelse): void
    {
        // Not needed for now.
    }
}
