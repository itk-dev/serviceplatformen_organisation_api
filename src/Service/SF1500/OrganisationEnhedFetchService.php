<?php

namespace App\Service\SF1500;

use App\Entity\Organisation\OrganisationEnhed;
use App\Entity\Organisation\OrganisationEnhedRegistrering;
use App\Entity\Organisation\OrganisationEnhedRegistreringAdresser;
use App\Entity\Organisation\OrganisationEnhedRegistreringEgenskab;
use App\Entity\Organisation\OrganisationEnhedRegistreringEnhedstype;
use App\Entity\Organisation\OrganisationEnhedRegistreringGyldighed;
use App\Entity\Organisation\OrganisationEnhedRegistreringOpgave;
use App\Entity\Organisation\OrganisationEnhedRegistreringOverordnet;
use App\Entity\Organisation\OrganisationEnhedRegistreringTilhoerer;
use App\Exception\UnhandledException;
use App\Service\SF1500Service;
use Doctrine\ORM\EntityManagerInterface;
use ItkDev\Serviceplatformen\SF1500\OrganisationEnhed\ServiceType\_List;
use ItkDev\Serviceplatformen\SF1500\OrganisationEnhed\ServiceType\Soeg;
use ItkDev\Serviceplatformen\SF1500\OrganisationEnhed\StructType\AdresseFlerRelationType;
use ItkDev\Serviceplatformen\SF1500\OrganisationEnhed\StructType\EgenskabType;
use ItkDev\Serviceplatformen\SF1500\OrganisationEnhed\StructType\FiltreretOejebliksbilledeType;
use ItkDev\Serviceplatformen\SF1500\OrganisationEnhed\StructType\GyldighedType;
use ItkDev\Serviceplatformen\SF1500\OrganisationEnhed\StructType\KlasseRelationType;
use ItkDev\Serviceplatformen\SF1500\OrganisationEnhed\StructType\ListInputType;
use ItkDev\Serviceplatformen\SF1500\OrganisationEnhed\StructType\LokalUdvidelseType;
use ItkDev\Serviceplatformen\SF1500\OrganisationEnhed\StructType\OpgaverFlerRelationType;
use ItkDev\Serviceplatformen\SF1500\OrganisationEnhed\StructType\OrganisationEnhedRelationType;
use ItkDev\Serviceplatformen\SF1500\OrganisationEnhed\StructType\OrganisationFlerRelationType;
use ItkDev\Serviceplatformen\SF1500\OrganisationEnhed\StructType\RegistreringType;
use ItkDev\Serviceplatformen\SF1500\OrganisationEnhed\StructType\RelationListeType;
use ItkDev\Serviceplatformen\SF1500\OrganisationEnhed\StructType\SoegInputType;
use ItkDev\Serviceplatformen\SF1500\OrganisationEnhed\StructType\SoegOutputType;
use ItkDev\Serviceplatformen\SF1500\OrganisationEnhed\StructType\TilstandListeType;
use ItkDev\Serviceplatformen\SF1500\OrganisationEnhed\StructType\VirksomhedRelationType;
use Psr\Log\LoggerAwareTrait;

class OrganisationEnhedFetchService implements FetchServiceInterface
{
    use LoggerAwareTrait;

    public function __construct(private readonly EntityManagerInterface $entityManager, private readonly SF1500Service $sf1500Service)
    {
    }

    public function fetch(int $pageSize, int $max): void
    {
        $total = 0;

        // TODO: REMOVE ONCE TESTED AND WORKING
        //        $attributListe = new AttributListeType();
        //        $attributListe->addToEgenskab((new EgenskabType())
        //            ->setBrugerNavn('az55488'));

        $tilstandListeType = new TilstandListeType();
        $tilstandListeType->addToGyldighed(
            new GyldighedType(
                null,
                'Aktiv'
            )
        );

        while (true) {
            $this->logger->debug(sprintf('Fetching organisation enhed data, offset: %d , max: %d', $total, $max));
            $this->logger->debug(sprintf('Memory used: %d ', memory_get_usage() / 1024 / 1024));
            $request = (new SoegInputType())
                ->setMaksimalAntalKvantitet(min($pageSize, $max - $total))
                ->setFoersteResultatReference($total)
                // Only want active objects.
                ->setTilstandListe($tilstandListeType)
//                ->setAttributListe($attributListe)
            ;

            /** @var SoegOutputType $data */
            $soeg = $this->clientSoeg()->soeg($request);

            $ids = $soeg->getIdListe()->getUUIDIdentifikator();

            if (!is_countable($ids) || empty($ids)) {
                break;
            }

            $brugerList = $this->clientList()->_list_9(new ListInputType($ids));

            foreach ($brugerList->getFiltreretOejebliksbillede() as /* @var FiltreretOejebliksbilledeType $oejebliksbillede */ $oejebliksbillede) {
                $this->handleOejebliksbillede($oejebliksbillede);
            }

            $this->entityManager->flush();
            $this->entityManager->clear();
            gc_collect_cycles();

            $total += count($ids);

            if ($total >= $max) {
                break;
            }
        }

        $this->logger->debug(sprintf('Finished fetching organisation enhed data'));
    }

    public function clientSoeg(array $options = []): Soeg
    {
        $client = $this->sf1500Service->getSF1500()->getClient(Soeg::class, $options);
        assert($client instanceof Soeg);

        return $client;
    }

    public function clientList(array $options = []): _List
    {
        $client = $this->sf1500Service->getSF1500()->getClient(_List::class, $options);
        assert($client instanceof _List);

        return $client;
    }

    private function handleOejebliksbillede(FiltreretOejebliksbilledeType $oejebliksbillede)
    {
        $organisationEnhed = new OrganisationEnhed();
        $organisationEnhed->setId($oejebliksbillede->getObjektType()->getUUIDIdentifikator());

        $this->entityManager->persist($organisationEnhed);

        $this->handleRegistrering($organisationEnhed, $oejebliksbillede->getRegistrering());
    }

    private function handleRegistrering(OrganisationEnhed $organisationEnhed, ?array $registreringer)
    {
        foreach ($registreringer as /* @var RegistreringType $registrering */ $registrering) {
            $organisationEnhedRegistrering = new OrganisationEnhedRegistrering();
            $organisationEnhed->addRegistreringer($organisationEnhedRegistrering);

            $organisationEnhedRegistrering
                ->setTidspunkt($registrering->getTidspunkt())
                ->setNoteTekst($registrering->getNoteTekst())
                ->setLivscyklusKode($registrering->getLivscyklusKode())
                ->setBrugerRefUUIDIdentifikator($registrering->getBrugerRef()->getUUIDIdentifikator())
                ->setBrugerRefURNIdentifikator($registrering->getBrugerRef()->getURNIdentifikator())
            ;

            $this->entityManager->persist($organisationEnhedRegistrering);

            $this->handleEgenskab($organisationEnhedRegistrering, $registrering->getAttributListe()->getEgenskab());
            $this->handleGyldighed($organisationEnhedRegistrering, $registrering->getTilstandListe()->getGyldighed());
            $this->handleRelation($organisationEnhedRegistrering, $registrering->getRelationListe());
        }
    }

    private function handleEgenskab(OrganisationEnhedRegistrering $organisationEnhedRegistrering, ?array $egenskaber)
    {
        if (null === $egenskaber) {
            return;
        }

        foreach ($egenskaber as /* @var EgenskabType $egenskab */ $egenskab) {
            $organisationEnhedRegistreringEgenskab = new OrganisationEnhedRegistreringEgenskab();
            $organisationEnhedRegistrering->addEgenskaber($organisationEnhedRegistreringEgenskab);

            $organisationEnhedRegistreringEgenskab
                ->setEnhedNavn($egenskab->getEnhedNavn())
                ->setBrugervendtNoegleTekst($egenskab->getBrugervendtNoegleTekst())
            ;

            // Virkning.
            $virkning = $egenskab->getVirkning();

            $organisationEnhedRegistreringEgenskab
                ->setVirkningFraTidsstempelDatoTid($virkning->getFraTidspunkt()->getTidsstempelDatoTid())
                ->setVirkningFraGraenseIndikator($virkning->getFraTidspunkt()->getGraenseIndikator())
                ->setVirkningTilTidsstempelDatoTid($virkning->getTilTidspunkt()->getTidsstempelDatoTid())
                ->setVirkningTilGraenseIndikator($virkning->getTilTidspunkt()->getGraenseIndikator())
                ->setVirkningAktoerRefUUIDIdentifikator($virkning->getAktoerRef()->getUUIDIdentifikator())
                ->setVirkningAktoerRefURNIdentifikator($virkning->getAktoerRef()->getURNIdentifikator())
                ->setVirkningAktoerTypeKode($virkning->getAktoerTypeKode())
                ->setVirkningNoteTekst($virkning->getNoteTekst())
            ;

            $this->entityManager->persist($organisationEnhedRegistreringEgenskab);
        }
    }

    private function handleGyldighed(OrganisationEnhedRegistrering $organisationEnhedRegistrering, ?array $gyldigheder)
    {
        if (null === $gyldigheder) {
            return;
        }

        foreach ($gyldigheder as /* @var GyldighedType $gyldighed */ $gyldighed) {
            $organisationEnhedRegistreringGyldighed = new OrganisationEnhedRegistreringGyldighed();

            $organisationEnhedRegistrering->addGyldigheder($organisationEnhedRegistreringGyldighed);

            $organisationEnhedRegistreringGyldighed
                ->setGyldighedStatusKode($gyldighed->getGyldighedStatusKode())
            ;

            // Virkning.
            $virkning = $gyldighed->getVirkning();

            $organisationEnhedRegistreringGyldighed
                ->setVirkningFraTidsstempelDatoTid($virkning->getFraTidspunkt()->getTidsstempelDatoTid())
                ->setVirkningFraGraenseIndikator($virkning->getFraTidspunkt()->getGraenseIndikator())
                ->setVirkningTilTidsstempelDatoTid($virkning->getTilTidspunkt()->getTidsstempelDatoTid())
                ->setVirkningTilGraenseIndikator($virkning->getTilTidspunkt()->getGraenseIndikator())
                ->setVirkningAktoerRefUUIDIdentifikator($virkning->getAktoerRef()->getUUIDIdentifikator())
                ->setVirkningAktoerRefURNIdentifikator($virkning->getAktoerRef()->getURNIdentifikator())
                ->setVirkningAktoerTypeKode($virkning->getAktoerTypeKode())
                ->setVirkningNoteTekst($virkning->getNoteTekst())
            ;

            $this->entityManager->persist($organisationEnhedRegistreringGyldighed);
        }
    }

    private function handleRelation(OrganisationEnhedRegistrering $organisationEnhedRegistrering, ?RelationListeType $relation)
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

    private function handleAdresser(OrganisationEnhedRegistrering $organisationEnhedRegistrering, ?array $adresser)
    {
        if (null === $adresser) {
            return;
        }

        foreach ($adresser as /* @var AdresseFlerRelationType $adresse */ $adresse) {
            $organisationEnhedRegistreringAdresse = new OrganisationEnhedRegistreringAdresser();
            $organisationEnhedRegistrering->addAdresser($organisationEnhedRegistreringAdresse);

            $organisationEnhedRegistreringAdresse
                ->setIndeks($adresse->getIndeks())
            ;

            // Virkning.
            $virkning = $adresse->getVirkning();

            $organisationEnhedRegistreringAdresse
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

            $organisationEnhedRegistreringAdresse
                ->setReferenceIdUUIDIdentifikator($referenceId->getUUIDIdentifikator())
                ->setReferenceIdURNIdentifikator($referenceId->getURNIdentifikator())
            ;

            // Rolle.
            $rolle = $adresse->getRolle();

            $organisationEnhedRegistreringAdresse
                ->setRolleUUIDIdentifikator($rolle->getUUIDIdentifikator())
                ->setRolleURNIdentifikator($rolle->getURNIdentifikator())
                ->setRolleLabel($rolle->getLabel())
            ;

            // Type.
            $type = $adresse->getType();

            $organisationEnhedRegistreringAdresse
                ->setTypeUUIDIdentifikator($type->getUUIDIdentifikator())
                ->setTypeURNIdentifikator($type->getURNIdentifikator())
                ->setTypeLabel($type->getLabel())
            ;

            $this->entityManager->persist($organisationEnhedRegistreringAdresse);
        }
    }

    private function handleAnsatte(OrganisationEnhedRegistrering $organisationEnhedRegistrering, ?array $ansatte)
    {
        if (null === $ansatte) {
            return;
        } else {
            throw new UnhandledException(sprintf('Unhandled data in %s: %s.', __CLASS__, __FUNCTION__));
        }
    }

    private function handleBranche(OrganisationEnhedRegistrering $organisationEnhedRegistrering, ?KlasseRelationType $branche)
    {
        if (null === $branche) {
            return;
        } else {
            throw new UnhandledException(sprintf('Unhandled data in %s: %s.', __CLASS__, __FUNCTION__));
        }
    }

    private function handleEnhedstype(OrganisationEnhedRegistrering $organisationEnhedRegistrering, ?KlasseRelationType $enhedstype)
    {
        if (null === $enhedstype) {
            return;
        }

        $organisationEnhedRegistreringEnhedstype = new OrganisationEnhedRegistreringEnhedstype();
        $organisationEnhedRegistrering->setEnhedstype($organisationEnhedRegistreringEnhedstype);

        // Virkning.
        $virkning = $enhedstype->getVirkning();

        $organisationEnhedRegistreringEnhedstype
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
        $referenceId = $enhedstype->getReferenceID();

        $organisationEnhedRegistreringEnhedstype
            ->setReferenceIdUUIDIdentifikator($referenceId->getUUIDIdentifikator())
            ->setReferenceIdURNIdentifikator($referenceId->getURNIdentifikator())
        ;

        $this->entityManager->persist($organisationEnhedRegistreringEnhedstype);
    }

    private function handleOpgaver(OrganisationEnhedRegistrering $organisationEnhedRegistrering, ?array $opgaver)
    {
        if (null === $opgaver) {
            return;
        }

        foreach ($opgaver as /* @var OpgaverFlerRelationType $opgaver */ $opgave) {
            $organisationEnhedRegistreringOpgave = new OrganisationEnhedRegistreringOpgave();
            $organisationEnhedRegistrering->addOpgaver($organisationEnhedRegistreringOpgave);

            $organisationEnhedRegistreringOpgave
                ->setIndeks($opgave->getIndeks())
            ;

            // Virkning.
            $virkning = $opgave->getVirkning();

            $organisationEnhedRegistreringOpgave
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
            $referenceId = $opgave->getReferenceID();

            $organisationEnhedRegistreringOpgave
                ->setReferenceIdUUIDIdentifikator($referenceId->getUUIDIdentifikator())
                ->setReferenceIdURNIdentifikator($referenceId->getURNIdentifikator())
            ;

            // Rolle.
            $rolle = $opgave->getRolle();

            $organisationEnhedRegistreringOpgave
                ->setRolleUUIDIdentifikator($rolle->getUUIDIdentifikator())
                ->setRolleURNIdentifikator($rolle->getURNIdentifikator())
                ->setRolleLabel($rolle->getLabel())
            ;

            // Type.
            $type = $opgave->getType();

            $organisationEnhedRegistreringOpgave
                ->setTypeUUIDIdentifikator($type->getUUIDIdentifikator())
                ->setTypeURNIdentifikator($type->getURNIdentifikator())
                ->setTypeLabel($type->getLabel())
            ;

            $this->entityManager->persist($organisationEnhedRegistreringOpgave);
        }
    }

    private function handleOverordnet(OrganisationEnhedRegistrering $organisationEnhedRegistrering, ?OrganisationEnhedRelationType $overordnet)
    {
        if (null === $overordnet) {
            return;
        }

        $organisationEnhedRegistreringOverordnet = new OrganisationEnhedRegistreringOverordnet();
        $organisationEnhedRegistrering->setOverordnet($organisationEnhedRegistreringOverordnet);

        // Virkning.
        $virkning = $overordnet->getVirkning();

        $organisationEnhedRegistreringOverordnet
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
        $referenceId = $overordnet->getReferenceID();

        $organisationEnhedRegistreringOverordnet
            ->setReferenceIdUUIDIdentifikator($referenceId->getUUIDIdentifikator())
            ->setReferenceIdURNIdentifikator($referenceId->getURNIdentifikator())
        ;

        $this->entityManager->persist($organisationEnhedRegistreringOverordnet);
    }

    private function handleProduktionsenhed(OrganisationEnhedRegistrering $organisationEnhedRegistrering, ?VirksomhedRelationType $produktionsenhed)
    {
        if (null === $produktionsenhed) {
            return;
        } else {
            throw new UnhandledException(sprintf('Unhandled data in %s: %s.', __CLASS__, __FUNCTION__));
        }
    }

    private function handleSkatteenhed(OrganisationEnhedRegistrering $organisationEnhedRegistrering, ?VirksomhedRelationType $skatteenhed)
    {
        if (null === $skatteenhed) {
            return;
        } else {
            throw new UnhandledException(sprintf('Unhandled data in %s: %s.', __CLASS__, __FUNCTION__));
        }
    }

    private function handleTilhoerer(OrganisationEnhedRegistrering $organisationEnhedRegistrering, ?OrganisationFlerRelationType $tilhoerer)
    {
        if (null === $tilhoerer) {
            return;
        }

        $organisationEnhedRegistreringTilhoerer = new OrganisationEnhedRegistreringTilhoerer();
        $organisationEnhedRegistrering->setTilhoerer($organisationEnhedRegistreringTilhoerer);

        // Virkning.
        $virkning = $tilhoerer->getVirkning();

        $organisationEnhedRegistreringTilhoerer
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

        $organisationEnhedRegistreringTilhoerer
            ->setReferenceIdUUIDIdentifikator($referenceId->getUUIDIdentifikator())
            ->setReferenceIdURNIdentifikator($referenceId->getURNIdentifikator())
        ;

        $this->entityManager->persist($organisationEnhedRegistreringTilhoerer);
    }

    private function handleTilknyttedeBrugere(OrganisationEnhedRegistrering $organisationEnhedRegistrering, ?array $tilknyttedeBrugere)
    {
        if (null === $tilknyttedeBrugere) {
            return;
        } else {
            throw new UnhandledException(sprintf('Unhandled data in %s: %s.', __CLASS__, __FUNCTION__));
        }
    }

    private function handleTilknyttedeEnheder(OrganisationEnhedRegistrering $organisationEnhedRegistrering, ?array $tilknyttedeEnheder)
    {
        if (null === $tilknyttedeEnheder) {
            return;
        } else {
            throw new UnhandledException(sprintf('Unhandled data in %s: %s.', __CLASS__, __FUNCTION__));
        }
    }

    private function handleTilknyttedeFunktioner(OrganisationEnhedRegistrering $organisationEnhedRegistrering, ?array $tilknyttedeFunktioner)
    {
        if (null === $tilknyttedeFunktioner) {
            return;
        } else {
            throw new UnhandledException(sprintf('Unhandled data in %s: %s.', __CLASS__, __FUNCTION__));
        }
    }

    private function handleTilknyttedeInteressefaellesskaber(OrganisationEnhedRegistrering $organisationEnhedRegistrering, ?array $tilknyttedeInteressefaellesskaber)
    {
        if (null === $tilknyttedeInteressefaellesskaber) {
            return;
        } else {
            throw new UnhandledException(sprintf('Unhandled data in %s: %s.', __CLASS__, __FUNCTION__));
        }
    }

    private function handleTilknyttedeOrganisationer(OrganisationEnhedRegistrering $organisationEnhedRegistrering, ?array $tilknyttedeOrganisationer)
    {
        if (null === $tilknyttedeOrganisationer) {
            return;
        } else {
            throw new UnhandledException(sprintf('Unhandled data in %s: %s.', __CLASS__, __FUNCTION__));
        }
    }

    private function handleTilknyttedePersoner(OrganisationEnhedRegistrering $organisationEnhedRegistrering, ?array $tilknyttedePersoner)
    {
        if (null === $tilknyttedePersoner) {
            return;
        } else {
            throw new UnhandledException(sprintf('Unhandled data in %s: %s.', __CLASS__, __FUNCTION__));
        }
    }

    private function handleTilknyttedeItSystemer(OrganisationEnhedRegistrering $organisationEnhedRegistrering, ?array $tilknyttedeItSystemer)
    {
        if (null === $tilknyttedeItSystemer) {
            return;
        } else {
            throw new UnhandledException(sprintf('Unhandled data in %s: %s.', __CLASS__, __FUNCTION__));
        }
    }

    private function handleLokalUdvidelse(OrganisationEnhedRegistrering $organisationEnhedRegistrering, ?LokalUdvidelseType $lokalUdvidelse)
    {
        if (null === $lokalUdvidelse) {
            return;
        } else {
            throw new UnhandledException(sprintf('Unhandled data in %s: %s.', __CLASS__, __FUNCTION__));
        }
    }
}
