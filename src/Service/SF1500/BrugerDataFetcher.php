<?php

namespace App\Service\SF1500;

use App\Entity\SF1500\BrugerRegistrering;
use App\Entity\SF1500\BrugerRegistreringAdresse;
use App\Entity\SF1500\BrugerRegistreringEgenskab;
use App\Entity\SF1500\BrugerRegistreringGyldighed;
use App\Entity\SF1500\BrugerRegistreringTilhoerer;
use App\Entity\SF1500\BrugerRegistreringTilknyttedePersoner;
use App\Exception\UnhandledException;
use ItkDev\Serviceplatformen\SF1500\Bruger\ServiceType\_List;
use ItkDev\Serviceplatformen\SF1500\Bruger\ServiceType\Soeg;
use ItkDev\Serviceplatformen\SF1500\Bruger\StructType\AdresseFlerRelationType;
use ItkDev\Serviceplatformen\SF1500\Bruger\StructType\EgenskabType;
use ItkDev\Serviceplatformen\SF1500\Bruger\StructType\FiltreretOejebliksbilledeType;
use ItkDev\Serviceplatformen\SF1500\Bruger\StructType\GyldighedType;
use ItkDev\Serviceplatformen\SF1500\Bruger\StructType\ListInputType;
use ItkDev\Serviceplatformen\SF1500\Bruger\StructType\LokalUdvidelseType;
use ItkDev\Serviceplatformen\SF1500\Bruger\StructType\OrganisationFlerRelationType;
use ItkDev\Serviceplatformen\SF1500\Bruger\StructType\PersonFlerRelationType;
use ItkDev\Serviceplatformen\SF1500\Bruger\StructType\RegistreringType;
use ItkDev\Serviceplatformen\SF1500\Bruger\StructType\RelationListeType;
use ItkDev\Serviceplatformen\SF1500\Bruger\StructType\SoegInputType;
use ItkDev\Serviceplatformen\SF1500\Bruger\StructType\SoegOutputType;
use ItkDev\Serviceplatformen\SF1500\Bruger\StructType\TilstandListeType;

class BrugerDataFetcher extends AbstractDataFetcher
{
    protected const DATA_TYPE = 'bruger';
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

    protected function fetchData(int $pageSize, int $total, int $max): int|false
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
            return false;
        }

        $brugerList = $this->clientList()->_list_1(new ListInputType($ids));

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

    private function handleRegistrering(string $brugerId, array $registreringer): void
    {
        foreach ($registreringer as /* @var RegistreringType $registrering */ $registrering) {
            $brugerRegistrering = new BrugerRegistrering();

            $brugerRegistrering
                ->setBrugerId($brugerId)
                ->setTidspunkt($registrering->getTidspunkt())
                ->setNoteTekst($registrering->getNoteTekst())
                ->setLivscyklusKode($registrering->getLivscyklusKode())
                ->setBrugerRefUUIDIdentifikator($registrering->getBrugerRef()->getUUIDIdentifikator())
                ->setBrugerRefURNIdentifikator($registrering->getBrugerRef()->getURNIdentifikator())
            ;

            $this->entityManager->persist($brugerRegistrering);

            $this->handleEgenskab($brugerRegistrering, $registrering->getAttributListe()->getEgenskab());
            $this->handleGyldighed($brugerRegistrering, $registrering->getTilstandListe()->getGyldighed());
            $this->handleRelation($brugerRegistrering, $registrering->getRelationListe());
        }
    }

    private function handleEgenskab(BrugerRegistrering $brugerRegistrering, ?array $egenskaber): void
    {
        if (null === $egenskaber) {
            return;
        }

        foreach ($egenskaber as /* @var EgenskabType $egenskab */ $egenskab) {
            $brugerRegistreringEgenskab = new BrugerRegistreringEgenskab();

            $brugerRegistrering->addEgenskaber($brugerRegistreringEgenskab);

            $brugerRegistreringEgenskab
                ->setBrugerNavn($egenskab->getBrugerNavn())
                ->setBrugervendtNoegleTekst($egenskab->getBrugervendtNoegleTekst())
                ->setBrugerTypeTekst($egenskab->getBrugerTypeTekst())
            ;

            // Virkning.
            $virkning = $egenskab->getVirkning();

            $brugerRegistreringEgenskab
                ->setVirkningFraTidsstempelDatoTid($virkning->getFraTidspunkt()->getTidsstempelDatoTid())
                ->setVirkningFraGraenseIndikator($virkning->getFraTidspunkt()->getGraenseIndikator())
                ->setVirkningTilTidsstempelDatoTid($virkning->getTilTidspunkt()->getTidsstempelDatoTid())
                ->setVirkningTilGraenseIndikator($virkning->getTilTidspunkt()->getGraenseIndikator())
                ->setVirkningAktoerRefUUIDIdentifikator($virkning->getAktoerRef()->getUUIDIdentifikator())
                ->setVirkningAktoerRefURNIdentifikator($virkning->getAktoerRef()->getURNIdentifikator())
                ->setVirkningAktoerTypeKode($virkning->getAktoerTypeKode())
                ->setVirkningNoteTekst($virkning->getNoteTekst())
            ;

            $this->entityManager->persist($brugerRegistreringEgenskab);
        }
    }

    private function handleGyldighed(BrugerRegistrering $brugerRegistrering, ?array $gyldigheder): void
    {
        if (null === $gyldigheder) {
            return;
        }

        foreach ($gyldigheder as /* @var GyldighedType $gyldighed */ $gyldighed) {
            $brugerRegistreringGyldighed = new BrugerRegistreringGyldighed();

            $brugerRegistrering->addGyldigheder($brugerRegistreringGyldighed);

            $brugerRegistreringGyldighed
                ->setGyldighedStatusKode($gyldighed->getGyldighedStatusKode())
            ;

            // Virkning.
            $virkning = $gyldighed->getVirkning();

            $brugerRegistreringGyldighed
                ->setVirkningFraTidsstempelDatoTid($virkning->getFraTidspunkt()->getTidsstempelDatoTid())
                ->setVirkningFraGraenseIndikator($virkning->getFraTidspunkt()->getGraenseIndikator())
                ->setVirkningTilTidsstempelDatoTid($virkning->getTilTidspunkt()->getTidsstempelDatoTid())
                ->setVirkningTilGraenseIndikator($virkning->getTilTidspunkt()->getGraenseIndikator())
                ->setVirkningAktoerRefUUIDIdentifikator($virkning->getAktoerRef()->getUUIDIdentifikator())
                ->setVirkningAktoerRefURNIdentifikator($virkning->getAktoerRef()->getURNIdentifikator())
                ->setVirkningAktoerTypeKode($virkning->getAktoerTypeKode())
                ->setVirkningNoteTekst($virkning->getNoteTekst())
            ;

            $this->entityManager->persist($brugerRegistreringGyldighed);
        }
    }

    private function handleRelation(BrugerRegistrering $brugerRegistrering, ?RelationListeType $relation): void
    {
        if (null === $relation) {
            return;
        }

        $this->handleAdresser($brugerRegistrering, $relation->getAdresser());
        $this->handleBrugerTyper($brugerRegistrering, $relation->getBrugerTyper());
        $this->handleTilknyttedeOpgaver($brugerRegistrering, $relation->getTilknyttedeOpgaver());
        $this->handleTilhoerer($brugerRegistrering, $relation->getTilhoerer());
        $this->handleTilknyttedeEnheder($brugerRegistrering, $relation->getTilknyttedeEnheder());
        $this->handleTilknyttedeInteressefaellesskaber($brugerRegistrering, $relation->getTilknyttedeInteressefaellesskaber());
        $this->handleTilknyttedeOrganisationer($brugerRegistrering, $relation->getTilknyttedeOrganisationer());
        $this->handleTilknyttedePersoner($brugerRegistrering, $relation->getTilknyttedePersoner());
        $this->handleTilknyttedeItSystemer($brugerRegistrering, $relation->getTilknyttedeItSystemer());
        $this->handleLokalUdvidelse($brugerRegistrering, $relation->getLokalUdvidelse());
    }

    private function handleAdresser(BrugerRegistrering $brugerRegistrering, ?array $adresser): void
    {
        if (null === $adresser) {
            return;
        }

        foreach ($adresser as /* @var AdresseFlerRelationType $adresse */ $adresse) {
            $brugerRegistreringAdresse = new BrugerRegistreringAdresse();
            $brugerRegistrering->addAdresser($brugerRegistreringAdresse);

            $brugerRegistreringAdresse
                ->setIndeks($adresse->getIndeks())
            ;

            // Virkning.
            $virkning = $adresse->getVirkning();

            $brugerRegistreringAdresse
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
            $referenceId = $adresse->getReferenceID();

            $brugerRegistreringAdresse
                ->setReferenceIdUUIDIdentifikator($referenceId->getUUIDIdentifikator())
                ->setReferenceIdURNIdentifikator($referenceId->getURNIdentifikator())
            ;

            // Rolle.
            $rolle = $adresse->getRolle();

            $brugerRegistreringAdresse
                ->setRolleUUIDIdentifikator($rolle->getUUIDIdentifikator())
                ->setRolleURNIdentifikator($rolle->getURNIdentifikator())
                ->setRolleLabel($rolle->getLabel())
            ;

            // Type.
            $type = $adresse->getType();

            $brugerRegistreringAdresse
                ->setTypeUUIDIdentifikator($type->getUUIDIdentifikator())
                ->setTypeURNIdentifikator($type->getURNIdentifikator())
                ->setTypeLabel($type->getLabel())
            ;

            $this->entityManager->persist($brugerRegistreringAdresse);
        }
    }

    private function handleBrugerTyper(BrugerRegistrering $brugerRegistrering, ?array $brugerTyper): void
    {
        if (null === $brugerTyper) {
            return;
        } else {
            throw new UnhandledException(sprintf('Unhandled data in %s: %s.', __CLASS__, __FUNCTION__));
        }
    }

    private function handleTilknyttedeOpgaver(BrugerRegistrering $brugerRegistrering, ?array $tilknyttedeOpgaver): void
    {
        if (null === $tilknyttedeOpgaver) {
            return;
        } else {
            throw new UnhandledException(sprintf('Unhandled data in %s: %s.', __CLASS__, __FUNCTION__));
        }
    }

    private function handleTilhoerer(BrugerRegistrering $brugerRegistrering, ?OrganisationFlerRelationType $tilhoerer): void
    {
        if (null === $tilhoerer) {
            return;
        }

        $brugerRegistreringTilhoerer = new BrugerRegistreringTilhoerer();
        $brugerRegistrering->addTilhoerer($brugerRegistreringTilhoerer);

        // Virkning.
        $virkning = $tilhoerer->getVirkning();

        $brugerRegistreringTilhoerer
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
        $referenceId = $tilhoerer->getReferenceID();

        $brugerRegistreringTilhoerer
            ->setReferenceIdUUIDIdentifikator($referenceId->getUUIDIdentifikator())
            ->setReferenceIdURNIdentifikator($referenceId->getURNIdentifikator())
        ;

        $this->entityManager->persist($brugerRegistreringTilhoerer);
    }

    private function handleTilknyttedeEnheder(BrugerRegistrering $brugerRegistrering, ?array $tilknyttedeEnheder): void
    {
        if (null === $tilknyttedeEnheder) {
            return;
        } else {
            throw new UnhandledException(sprintf('Unhandled data in %s: %s.', __CLASS__, __FUNCTION__));
        }
    }

    private function handleTilknyttedeInteressefaellesskaber(BrugerRegistrering $brugerRegistrering, ?array $tilknyttedeInteressefaellesskaber): void
    {
        if (null === $tilknyttedeInteressefaellesskaber) {
            return;
        } else {
            throw new UnhandledException(sprintf('Unhandled data in %s: %s.', __CLASS__, __FUNCTION__));
        }
    }

    private function handleTilknyttedeOrganisationer(BrugerRegistrering $brugerRegistrering, ?array $tilknyttedeOrganisationer): void
    {
        if (null === $tilknyttedeOrganisationer) {
            return;
        } else {
            throw new UnhandledException(sprintf('Unhandled data in %s: %s.', __CLASS__, __FUNCTION__));
        }
    }

    private function handleTilknyttedePersoner(BrugerRegistrering $brugerRegistrering, ?array $tilknyttedePersoner): void
    {
        if (null === $tilknyttedePersoner) {
            return;
        }

        foreach ($tilknyttedePersoner as /* @var PersonFlerRelationType $tilknyttedePerson */ $tilknyttedePerson) {
            $brugerRegistreringTilknyttedePersoner = new BrugerRegistreringTilknyttedePersoner();
            $brugerRegistrering->addTilknyttedePersoner($brugerRegistreringTilknyttedePersoner);

            // Virkning.
            $virkning = $tilknyttedePerson->getVirkning();

            $brugerRegistreringTilknyttedePersoner
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
            $referenceId = $tilknyttedePerson->getReferenceID();

            $brugerRegistreringTilknyttedePersoner
                ->setReferenceIdUUIDIdentifikator($referenceId->getUUIDIdentifikator())
                ->setReferenceIdURNIdentifikator($referenceId->getURNIdentifikator())
            ;

            $this->entityManager->persist($brugerRegistreringTilknyttedePersoner);
        }
    }

    private function handleTilknyttedeItSystemer(BrugerRegistrering $brugerRegistrering, ?array $tilknyttedeItSystemer): void
    {
        if (null === $tilknyttedeItSystemer) {
            return;
        } else {
            throw new UnhandledException(sprintf('Unhandled data in %s: %s.', __CLASS__, __FUNCTION__));
        }
    }

    private function handleLokalUdvidelse(BrugerRegistrering $brugerRegistrering, ?LokalUdvidelseType $lokalUdvidelse): void
    {
        if (null === $lokalUdvidelse) {
            return;
        } else {
            throw new UnhandledException(sprintf('Unhandled data in %s: %s.', __CLASS__, __FUNCTION__));
        }
    }
}
