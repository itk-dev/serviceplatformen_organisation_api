<?php

namespace App\Service\SF1500;

use App\Entity\SF1500\OrganisationEnhedRegistrering;
use App\Entity\SF1500\OrganisationEnhedRegistreringAdresser;
use App\Entity\SF1500\OrganisationEnhedRegistreringEgenskab;
use App\Entity\SF1500\OrganisationEnhedRegistreringOverordnet;
use ItkDev\Serviceplatformen\SF1500\OrganisationEnhed\ServiceType\_List;
use ItkDev\Serviceplatformen\SF1500\OrganisationEnhed\ServiceType\Soeg;
use ItkDev\Serviceplatformen\SF1500\OrganisationEnhed\StructType\AdresseFlerRelationType;
use ItkDev\Serviceplatformen\SF1500\OrganisationEnhed\StructType\EgenskabType;
use ItkDev\Serviceplatformen\SF1500\OrganisationEnhed\StructType\FiltreretOejebliksbilledeType;
use ItkDev\Serviceplatformen\SF1500\OrganisationEnhed\StructType\GyldighedType;
use ItkDev\Serviceplatformen\SF1500\OrganisationEnhed\StructType\KlasseRelationType;
use ItkDev\Serviceplatformen\SF1500\OrganisationEnhed\StructType\ListInputType;
use ItkDev\Serviceplatformen\SF1500\OrganisationEnhed\StructType\LokalUdvidelseType;
use ItkDev\Serviceplatformen\SF1500\OrganisationEnhed\StructType\OrganisationEnhedRelationType;
use ItkDev\Serviceplatformen\SF1500\OrganisationEnhed\StructType\OrganisationFlerRelationType;
use ItkDev\Serviceplatformen\SF1500\OrganisationEnhed\StructType\RegistreringType;
use ItkDev\Serviceplatformen\SF1500\OrganisationEnhed\StructType\RelationListeType;
use ItkDev\Serviceplatformen\SF1500\OrganisationEnhed\StructType\SoegInputType;
use ItkDev\Serviceplatformen\SF1500\OrganisationEnhed\StructType\SoegOutputType;
use ItkDev\Serviceplatformen\SF1500\OrganisationEnhed\StructType\TilstandListeType;
use ItkDev\Serviceplatformen\SF1500\OrganisationEnhed\StructType\VirksomhedRelationType;

class OrganisationEnhedDataFetcher extends AbstractDataFetcher
{
    protected const DATA_TYPE = 'organisation enhed';
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

        $brugerList = $this->clientList()->_list_9(new ListInputType($ids));

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

    private function handleRegistrering(string $organisationEnhedId, ?array $registreringer): void
    {
        foreach ($registreringer as /* @var RegistreringType $registrering */ $registrering) {
            $organisationEnhedRegistrering = new OrganisationEnhedRegistrering();

            $organisationEnhedRegistrering
                ->setOrganisationEnhedId($organisationEnhedId)
            ;

            $this->entityManager->persist($organisationEnhedRegistrering);

            $this->handleEgenskab($organisationEnhedRegistrering, $registrering->getAttributListe()->getEgenskab());
            $this->handleGyldighed($organisationEnhedRegistrering, $registrering->getTilstandListe()->getGyldighed());
            $this->handleRelation($organisationEnhedRegistrering, $registrering->getRelationListe());
        }
    }

    private function handleEgenskab(OrganisationEnhedRegistrering $organisationEnhedRegistrering, ?array $egenskaber): void
    {
        if (null === $egenskaber) {
            return;
        }

        foreach ($egenskaber as /* @var EgenskabType $egenskab */ $egenskab) {
            $organisationEnhedRegistreringEgenskab = new OrganisationEnhedRegistreringEgenskab();
            $organisationEnhedRegistrering->addEgenskaber($organisationEnhedRegistreringEgenskab);

            $organisationEnhedRegistreringEgenskab
                ->setEnhedNavn($egenskab->getEnhedNavn())
            ;

            $this->entityManager->persist($organisationEnhedRegistreringEgenskab);
        }
    }

    private function handleGyldighed(OrganisationEnhedRegistrering $organisationEnhedRegistrering, ?array $gyldigheder): void
    {
        // Not needed for now.
    }

    private function handleRelation(OrganisationEnhedRegistrering $organisationEnhedRegistrering, ?RelationListeType $relation): void
    {
        if (null === $relation) {
            return;
        }

        $this->handleAdresser($organisationEnhedRegistrering, $relation->getAdresser());
        $this->handleAnsatte($organisationEnhedRegistrering, $relation->getAnsatte());
        $this->handleBranche($organisationEnhedRegistrering, $relation->getBranche());
        $this->handleEnhedstype($organisationEnhedRegistrering, $relation->getEnhedstype());
        $this->handleOpgaver($organisationEnhedRegistrering, $relation->getOpgaver());
        $this->handleOverordnet($organisationEnhedRegistrering, $relation->getOverordnet());
        $this->handleProduktionsenhed($organisationEnhedRegistrering, $relation->getProduktionsenhed());
        $this->handleSkatteenhed($organisationEnhedRegistrering, $relation->getSkatteenhed());
        $this->handleTilhoerer($organisationEnhedRegistrering, $relation->getTilhoerer());
        $this->handleTilknyttedeBrugere($organisationEnhedRegistrering, $relation->getTilknyttedeBrugere());
        $this->handleTilknyttedeEnheder($organisationEnhedRegistrering, $relation->getTilknyttedeEnheder());
        $this->handleTilknyttedeFunktioner($organisationEnhedRegistrering, $relation->getTilknyttedeFunktioner());
        $this->handleTilknyttedeInteressefaellesskaber($organisationEnhedRegistrering, $relation->getTilknyttedeInteressefaellesskaber());
        $this->handleTilknyttedeOrganisationer($organisationEnhedRegistrering, $relation->getTilknyttedeOrganisationer());
        $this->handleTilknyttedePersoner($organisationEnhedRegistrering, $relation->getTilknyttedePersoner());
        $this->handleTilknyttedeItSystemer($organisationEnhedRegistrering, $relation->getTilknyttedeItSystemer());
        $this->handleLokalUdvidelse($organisationEnhedRegistrering, $relation->getLokalUdvidelse());
    }

    private function handleAdresser(OrganisationEnhedRegistrering $organisationEnhedRegistrering, ?array $adresser): void
    {
        if (null === $adresser) {
            return;
        }

        foreach ($adresser as /* @var AdresseFlerRelationType $adresse */ $adresse) {
            $organisationEnhedRegistreringAdresse = new OrganisationEnhedRegistreringAdresser();
            $organisationEnhedRegistrering->addAdresser($organisationEnhedRegistreringAdresse);

            // Reference id.
            $referenceId = $adresse->getReferenceID();

            $organisationEnhedRegistreringAdresse
                ->setReferenceIdUUIDIdentifikator($referenceId->getUUIDIdentifikator())
            ;

            // Rolle.
            $rolle = $adresse->getRolle();

            $organisationEnhedRegistreringAdresse
                ->setRolleLabel($rolle->getLabel())
            ;

            $this->entityManager->persist($organisationEnhedRegistreringAdresse);
        }
    }

    private function handleAnsatte(OrganisationEnhedRegistrering $organisationEnhedRegistrering, ?array $ansatte): void
    {
        // Not needed for now.
    }

    private function handleBranche(OrganisationEnhedRegistrering $organisationEnhedRegistrering, ?KlasseRelationType $branche): void
    {
        // Not needed for now.
    }

    private function handleEnhedstype(OrganisationEnhedRegistrering $organisationEnhedRegistrering, ?KlasseRelationType $enhedstype): void
    {
        // Not needed for now.
    }

    private function handleOpgaver(OrganisationEnhedRegistrering $organisationEnhedRegistrering, ?array $opgaver): void
    {
        // Not needed for now.
    }

    private function handleOverordnet(OrganisationEnhedRegistrering $organisationEnhedRegistrering, ?OrganisationEnhedRelationType $overordnet): void
    {
        if (null === $overordnet) {
            return;
        }

        $organisationEnhedRegistreringOverordnet = new OrganisationEnhedRegistreringOverordnet();
        $organisationEnhedRegistrering->setOverordnet($organisationEnhedRegistreringOverordnet);

        // Reference id.
        $referenceId = $overordnet->getReferenceID();

        $organisationEnhedRegistreringOverordnet
            ->setReferenceIdUUIDIdentifikator($referenceId->getUUIDIdentifikator())
        ;

        $this->entityManager->persist($organisationEnhedRegistreringOverordnet);
    }

    private function handleProduktionsenhed(OrganisationEnhedRegistrering $organisationEnhedRegistrering, ?VirksomhedRelationType $produktionsenhed): void
    {
        // Not needed for now.
    }

    private function handleSkatteenhed(OrganisationEnhedRegistrering $organisationEnhedRegistrering, ?VirksomhedRelationType $skatteenhed): void
    {
        // Not needed for now.
    }

    private function handleTilhoerer(OrganisationEnhedRegistrering $organisationEnhedRegistrering, ?OrganisationFlerRelationType $tilhoerer): void
    {
        // Not needed for now.
    }

    private function handleTilknyttedeBrugere(OrganisationEnhedRegistrering $organisationEnhedRegistrering, ?array $tilknyttedeBrugere): void
    {
        // Not needed for now.
    }

    private function handleTilknyttedeEnheder(OrganisationEnhedRegistrering $organisationEnhedRegistrering, ?array $tilknyttedeEnheder): void
    {
        // Not needed for now.
    }

    private function handleTilknyttedeFunktioner(OrganisationEnhedRegistrering $organisationEnhedRegistrering, ?array $tilknyttedeFunktioner): void
    {
        // Not needed for now.
    }

    private function handleTilknyttedeInteressefaellesskaber(OrganisationEnhedRegistrering $organisationEnhedRegistrering, ?array $tilknyttedeInteressefaellesskaber): void
    {
        // Not needed for now.
    }

    private function handleTilknyttedeOrganisationer(OrganisationEnhedRegistrering $organisationEnhedRegistrering, ?array $tilknyttedeOrganisationer): void
    {
        // Not needed for now.
    }

    private function handleTilknyttedePersoner(OrganisationEnhedRegistrering $organisationEnhedRegistrering, ?array $tilknyttedePersoner): void
    {
        // Not needed for now.
    }

    private function handleTilknyttedeItSystemer(OrganisationEnhedRegistrering $organisationEnhedRegistrering, ?array $tilknyttedeItSystemer): void
    {
        // Not needed for now.
    }

    private function handleLokalUdvidelse(OrganisationEnhedRegistrering $organisationEnhedRegistrering, ?LokalUdvidelseType $lokalUdvidelse): void
    {
        // Not needed for now.
    }
}
