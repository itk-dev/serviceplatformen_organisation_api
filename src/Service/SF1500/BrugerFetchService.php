<?php

namespace App\Service\SF1500;

use App\Entity\Organisation\Bruger;
use App\Entity\Organisation\BrugerRegistrering;
use App\Entity\Organisation\BrugerRegistreringAdresse;
use App\Entity\Organisation\BrugerRegistreringEgenskab;
use App\Entity\Organisation\BrugerRegistreringGyldighed;
use App\Entity\Organisation\BrugerRegistreringTilhoerer;
use App\Entity\Organisation\BrugerRegistreringTilknyttedePersoner;
use App\Exception\UnhandledException;
use App\Service\SF1500Service;
use Doctrine\ORM\EntityManagerInterface;
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
use Psr\Log\LoggerAwareTrait;

class BrugerFetchService implements FetchServiceInterface
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
            $this->logger->debug(sprintf('Fetching bruger data, offset: %d , max: %d', $total, $max));
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

            $brugerList = $this->clientList()->_list_1(new ListInputType($ids));

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

        $this->logger->debug(sprintf('Finished fetching bruger data'));
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

    private function handleOejebliksbillede(FiltreretOejebliksbilledeType $oejebliksbillede): void
    {
        $bruger = new Bruger();
        $bruger->setId($oejebliksbillede->getObjektType()->getUUIDIdentifikator());

        $this->entityManager->persist($bruger);

        $this->handleRegistrering($bruger, $oejebliksbillede->getRegistrering());
    }

    private function handleRegistrering(Bruger $bruger, array $registreringer): void
    {
        foreach ($registreringer as /* @var RegistreringType $registrering */ $registrering) {
            $brugerRegistrering = new BrugerRegistrering();
            $bruger->addRegistreringer($brugerRegistrering);

            $brugerRegistrering
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

    private function handleGyldighed(BrugerRegistrering $brugerRegistrering, ?array $gyldigheder)
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

    private function handleRelation(BrugerRegistrering $brugerRegistrering, ?RelationListeType $relation)
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

    private function handleAdresser(BrugerRegistrering $brugerRegistrering, ?array $adresser)
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

    private function handleBrugerTyper(BrugerRegistrering $brugerRegistrering, ?array $brugerTyper)
    {
        if (null === $brugerTyper) {
            return;
        } else {
            throw new UnhandledException(sprintf('Unhandled data in %s: %s.', __CLASS__, __FUNCTION__));
        }
    }

    private function handleTilknyttedeOpgaver(BrugerRegistrering $brugerRegistrering, ?array $tilknyttedeOpgaver)
    {
        if (null === $tilknyttedeOpgaver) {
            return;
        } else {
            throw new UnhandledException(sprintf('Unhandled data in %s: %s.', __CLASS__, __FUNCTION__));
        }
    }

    private function handleTilhoerer(BrugerRegistrering $brugerRegistrering, ?OrganisationFlerRelationType $tilhoerer)
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

    private function handleTilknyttedeEnheder(BrugerRegistrering $brugerRegistrering, ?array $tilknyttedeEnheder)
    {
        if (null === $tilknyttedeEnheder) {
            return;
        } else {
            throw new UnhandledException(sprintf('Unhandled data in %s: %s.', __CLASS__, __FUNCTION__));
        }
    }

    private function handleTilknyttedeInteressefaellesskaber(BrugerRegistrering $brugerRegistrering, ?array $tilknyttedeInteressefaellesskaber)
    {
        if (null === $tilknyttedeInteressefaellesskaber) {
            return;
        } else {
            throw new UnhandledException(sprintf('Unhandled data in %s: %s.', __CLASS__, __FUNCTION__));
        }
    }

    private function handleTilknyttedeOrganisationer(BrugerRegistrering $brugerRegistrering, ?array $tilknyttedeOrganisationer)
    {
        if (null === $tilknyttedeOrganisationer) {
            return;
        } else {
            throw new UnhandledException(sprintf('Unhandled data in %s: %s.', __CLASS__, __FUNCTION__));
        }
    }

    private function handleTilknyttedePersoner(BrugerRegistrering $brugerRegistrering, ?array $tilknyttedePersoner)
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

    private function handleTilknyttedeItSystemer(BrugerRegistrering $brugerRegistrering, ?array $tilknyttedeItSystemer)
    {
        if (null === $tilknyttedeItSystemer) {
            return;
        } else {
            throw new UnhandledException(sprintf('Unhandled data in %s: %s.', __CLASS__, __FUNCTION__));
        }
    }

    private function handleLokalUdvidelse(BrugerRegistrering $brugerRegistrering, ?LokalUdvidelseType $lokalUdvidelse)
    {
        if (null === $lokalUdvidelse) {
            return;
        } else {
            throw new UnhandledException(sprintf('Unhandled data in %s: %s.', __CLASS__, __FUNCTION__));
        }
    }
}
