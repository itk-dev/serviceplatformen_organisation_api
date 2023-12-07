<?php

namespace App\Service\SF1500;

use App\Entity\SF1500\OrganisationFunktionRegistrering;
use App\Entity\SF1500\OrganisationFunktionRegistreringEgenskab;
use App\Entity\SF1500\OrganisationFunktionRegistreringFunktionstype;
use App\Entity\SF1500\OrganisationFunktionRegistreringGyldighed;
use App\Entity\SF1500\OrganisationFunktionRegistreringTilknyttedeBrugere;
use App\Entity\SF1500\OrganisationFunktionRegistreringTilknyttedeEnheder;
use App\Entity\SF1500\OrganisationFunktionRegistreringTilknyttedeOrganisationer;
use App\Exception\UnhandledException;
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
use ItkDev\Serviceplatformen\SF1500\OrganisationFunktion\StructType\OrganisationFlerRelationType;
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
                ->setTidspunkt($registrering->getTidspunkt())
                ->setNoteTekst($registrering->getNoteTekst())
                ->setLivscyklusKode($registrering->getLivscyklusKode())
                ->setBrugerRefUUIDIdentifikator($registrering->getBrugerRef()->getUUIDIdentifikator())
                ->setBrugerRefURNIdentifikator($registrering->getBrugerRef()->getURNIdentifikator())
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
                ->setBrugervendtNoegleTekst($egenskab->getBrugervendtNoegleTekst())
            ;

            // Virkning.
            $virkning = $egenskab->getVirkning();

            $organisationFunktionRegistreringEgenskab
                ->setVirkningFraTidsstempelDatoTid($virkning->getFraTidspunkt()->getTidsstempelDatoTid())
                ->setVirkningFraGraenseIndikator($virkning->getFraTidspunkt()->getGraenseIndikator())
                ->setVirkningTilTidsstempelDatoTid($virkning->getTilTidspunkt()->getTidsstempelDatoTid())
                ->setVirkningTilGraenseIndikator($virkning->getTilTidspunkt()->getGraenseIndikator())
                ->setVirkningAktoerRefUUIDIdentifikator($virkning->getAktoerRef()->getUUIDIdentifikator())
                ->setVirkningAktoerRefURNIdentifikator($virkning->getAktoerRef()->getURNIdentifikator())
                ->setVirkningAktoerTypeKode($virkning->getAktoerTypeKode())
                ->setVirkningNoteTekst($virkning->getNoteTekst())
            ;

            $this->entityManager->persist($organisationFunktionRegistreringEgenskab);
        }
    }

    private function handleGyldighed(OrganisationFunktionRegistrering $organisationFunktionRegistrering, ?array $gyldigheder): void
    {
        return;

        if (null === $gyldigheder) {
            return;
        }

        foreach ($gyldigheder as /* @var GyldighedType $gyldighed */ $gyldighed) {
            $organisationFunktionRegistreringGyldighed = new OrganisationFunktionRegistreringGyldighed();

            $organisationFunktionRegistrering->addGyldigheder($organisationFunktionRegistreringGyldighed);

            $organisationFunktionRegistreringGyldighed
                ->setGyldighedStatusKode($gyldighed->getGyldighedStatusKode())
            ;

            // Virkning.
            $virkning = $gyldighed->getVirkning();

            $organisationFunktionRegistreringGyldighed
                ->setVirkningFraTidsstempelDatoTid($virkning->getFraTidspunkt()->getTidsstempelDatoTid())
                ->setVirkningFraGraenseIndikator($virkning->getFraTidspunkt()->getGraenseIndikator())
                ->setVirkningTilTidsstempelDatoTid($virkning->getTilTidspunkt()->getTidsstempelDatoTid())
                ->setVirkningTilGraenseIndikator($virkning->getTilTidspunkt()->getGraenseIndikator())
                ->setVirkningAktoerRefUUIDIdentifikator($virkning->getAktoerRef()->getUUIDIdentifikator())
                ->setVirkningAktoerRefURNIdentifikator($virkning->getAktoerRef()->getURNIdentifikator())
                ->setVirkningAktoerTypeKode($virkning->getAktoerTypeKode())
                ->setVirkningNoteTekst($virkning->getNoteTekst())
            ;

            $this->entityManager->persist($organisationFunktionRegistreringGyldighed);
        }
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
        if (null === $adresser) {
            return;
        } else {
            throw new UnhandledException(sprintf('Unhandled data in %s: %s.', __CLASS__, __FUNCTION__));
        }
    }

    private function handleFunktionstype(OrganisationFunktionRegistrering $organisationFunktionRegistrering, ?KlasseRelationType $funktionstype): void
    {
        if (null === $funktionstype) {
            return;
        }

        $organisationFunktionRegistreringFunktionstype = new OrganisationFunktionRegistreringFunktionstype();
        $organisationFunktionRegistrering->setFunktionstype($organisationFunktionRegistreringFunktionstype);

        // Virkning.
        $virkning = $funktionstype->getVirkning();

        $organisationFunktionRegistreringFunktionstype
            ->setVirkningFraTidsstempelDatoTid($virkning->getFraTidspunkt()->getTidsstempelDatoTid())
            ->setVirkningFraGraenseIndikator($virkning->getFraTidspunkt()->getGraenseIndikator())
            ->setVirkningTilTidsstempelDatoTid($virkning->getTilTidspunkt()->getTidsstempelDatoTid())
            ->setVirkningTilGraenseIndikator($virkning->getTilTidspunkt()->getGraenseIndikator())
            ->setVirkningAktoerRefUUIDIdentifikator($virkning->getAktoerRef()->getUUIDIdentifikator())
            ->setVirkningAktoerRefURNIdentifikator($virkning->getAktoerRef()->getURNIdentifikator())
            ->setVirkningAktoerTypeKode($virkning->getAktoerTypeKode())
            ->setVirkningNoteTekst($virkning->getNoteTekst())
        ;

        // Reference id.
        $referenceId = $funktionstype->getReferenceID();

        $organisationFunktionRegistreringFunktionstype
            ->setReferenceIdUUIDIdentifikator($referenceId->getUUIDIdentifikator())
            ->setReferenceIdURNIdentifikator($referenceId->getURNIdentifikator())
        ;

        $this->entityManager->persist($organisationFunktionRegistreringFunktionstype);
    }

    private function handleTilknyttedeOpgaver(OrganisationFunktionRegistrering $organisationFunktionRegistrering, ?array $tilknyttedeOpgaver): void
    {
        if (null === $tilknyttedeOpgaver) {
            return;
        } else {
            throw new UnhandledException(sprintf('Unhandled data in %s: %s.', __CLASS__, __FUNCTION__));
        }
    }

    private function handleTilknyttedeBrugere(OrganisationFunktionRegistrering $organisationFunktionRegistrering, ?array $tilknyttedeBrugere): void
    {
        if (null === $tilknyttedeBrugere) {
            return;
        }

        foreach ($tilknyttedeBrugere as /* @var BrugerFlerRelationType $tilknyttedeBruger */ $tilknyttedeBruger) {
            $organisationFunktionRegistreringTilknyttedeBrugere = new OrganisationFunktionRegistreringTilknyttedeBrugere();
            $organisationFunktionRegistrering->addTilknyttedeBrugere($organisationFunktionRegistreringTilknyttedeBrugere);

            // Virkning.
            $virkning = $tilknyttedeBruger->getVirkning();

            $organisationFunktionRegistreringTilknyttedeBrugere
                ->setVirkningFraTidsstempelDatoTid($virkning->getFraTidspunkt()->getTidsstempelDatoTid())
                ->setVirkningFraGraenseIndikator($virkning->getFraTidspunkt()->getGraenseIndikator())
                ->setVirkningTilTidsstempelDatoTid($virkning->getTilTidspunkt()->getTidsstempelDatoTid())
                ->setVirkningTilGraenseIndikator($virkning->getTilTidspunkt()->getGraenseIndikator())
                ->setVirkningAktoerRefUUIDIdentifikator($virkning->getAktoerRef()->getUUIDIdentifikator())
                ->setVirkningAktoerRefURNIdentifikator($virkning->getAktoerRef()->getURNIdentifikator())
                ->setVirkningAktoerTypeKode($virkning->getAktoerTypeKode())
                ->setVirkningNoteTekst($virkning->getNoteTekst())
            ;

            // Reference id.
            $referenceId = $tilknyttedeBruger->getReferenceID();

            $organisationFunktionRegistreringTilknyttedeBrugere
                ->setReferenceIdUUIDIdentifikator($referenceId->getUUIDIdentifikator())
                ->setReferenceIdURNIdentifikator($referenceId->getURNIdentifikator())
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

            // Virkning.
            $virkning = $tilknyttedeEnhed->getVirkning();

            $organisationFunktionRegistreringTilknyttedeEnheder
                ->setVirkningFraTidsstempelDatoTid($virkning->getFraTidspunkt()->getTidsstempelDatoTid())
                ->setVirkningFraGraenseIndikator($virkning->getFraTidspunkt()->getGraenseIndikator())
                ->setVirkningTilTidsstempelDatoTid($virkning->getTilTidspunkt()->getTidsstempelDatoTid())
                ->setVirkningTilGraenseIndikator($virkning->getTilTidspunkt()->getGraenseIndikator())
                ->setVirkningAktoerRefUUIDIdentifikator($virkning->getAktoerRef()->getUUIDIdentifikator())
                ->setVirkningAktoerRefURNIdentifikator($virkning->getAktoerRef()->getURNIdentifikator())
                ->setVirkningAktoerTypeKode($virkning->getAktoerTypeKode())
                ->setVirkningNoteTekst($virkning->getNoteTekst())
            ;

            // Reference id.
            $referenceId = $tilknyttedeEnhed->getReferenceID();

            $organisationFunktionRegistreringTilknyttedeEnheder
                ->setReferenceIdUUIDIdentifikator($referenceId->getUUIDIdentifikator())
                ->setReferenceIdURNIdentifikator($referenceId->getURNIdentifikator())
            ;

            $this->entityManager->persist($organisationFunktionRegistreringTilknyttedeEnheder);
        }
    }

    private function handleTilknyttedeInteressefaellesskaber(OrganisationFunktionRegistrering $organisationFunktionRegistrering, ?array $tilknyttedeInteressefaellesskaber): void
    {
        if (null === $tilknyttedeInteressefaellesskaber) {
            return;
        } else {
            throw new UnhandledException(sprintf('Unhandled data in %s: %s.', __CLASS__, __FUNCTION__));
        }
    }

    private function handleTilknyttedeOrganisationer(OrganisationFunktionRegistrering $organisationFunktionRegistrering, ?array $tilknyttedeOrganisationer): void
    {
        return;

        if (null === $tilknyttedeOrganisationer) {
            return;
        }

        foreach ($tilknyttedeOrganisationer as /* @var OrganisationFlerRelationType $tilknyttedeOrganisation */ $tilknyttedeOrganisation) {
            $organisationFunktionRegistreringTilknyttedeOrganisationer = new OrganisationFunktionRegistreringTilknyttedeOrganisationer();
            $organisationFunktionRegistrering->addTilknyttedeOrganisationer($organisationFunktionRegistreringTilknyttedeOrganisationer);

            // Virkning.
            $virkning = $tilknyttedeOrganisation->getVirkning();

            $organisationFunktionRegistreringTilknyttedeOrganisationer
                ->setVirkningFraTidsstempelDatoTid($virkning->getFraTidspunkt()->getTidsstempelDatoTid())
                ->setVirkningFraGraenseIndikator($virkning->getFraTidspunkt()->getGraenseIndikator())
                ->setVirkningTilTidsstempelDatoTid($virkning->getTilTidspunkt()->getTidsstempelDatoTid())
                ->setVirkningTilGraenseIndikator($virkning->getTilTidspunkt()->getGraenseIndikator())
                ->setVirkningAktoerRefUUIDIdentifikator($virkning->getAktoerRef()->getUUIDIdentifikator())
                ->setVirkningAktoerRefURNIdentifikator($virkning->getAktoerRef()->getURNIdentifikator())
                ->setVirkningAktoerTypeKode($virkning->getAktoerTypeKode())
                ->setVirkningNoteTekst($virkning->getNoteTekst())
            ;

            // Reference id.
            $referenceId = $tilknyttedeOrganisation->getReferenceID();

            $organisationFunktionRegistreringTilknyttedeOrganisationer
                ->setReferenceIdUUIDIdentifikator($referenceId->getUUIDIdentifikator())
                ->setReferenceIdURNIdentifikator($referenceId->getURNIdentifikator())
            ;

            $this->entityManager->persist($organisationFunktionRegistreringTilknyttedeOrganisationer);
        }
    }

    private function handleTilknyttedePersoner(OrganisationFunktionRegistrering $organisationFunktionRegistrering, ?array $tilknyttedePersoner): void
    {
        if (null === $tilknyttedePersoner) {
            return;
        } else {
            throw new UnhandledException(sprintf('Unhandled data in %s: %s.', __CLASS__, __FUNCTION__));
        }
    }

    private function handleTilknyttedeItSystemer(OrganisationFunktionRegistrering $organisationFunktionRegistrering, ?array $tilknyttedeItSystemer): void
    {
        if (null === $tilknyttedeItSystemer) {
            return;
        } else {
            throw new UnhandledException(sprintf('Unhandled data in %s: %s.', __CLASS__, __FUNCTION__));
        }
    }

    private function handleLokalUdvidelse(OrganisationFunktionRegistrering $organisationFunktionRegistrering, ?LokalUdvidelseType $lokalUdvidelse): void
    {
        if (null === $lokalUdvidelse) {
            return;
        } else {
            throw new UnhandledException(sprintf('Unhandled data in %s: %s.', __CLASS__, __FUNCTION__));
        }
    }
}
