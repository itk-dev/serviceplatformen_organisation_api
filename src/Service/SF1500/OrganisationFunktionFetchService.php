<?php

namespace App\Service\SF1500;

use App\Entity\Organisation\OrganisationFunktion;
use App\Entity\Organisation\OrganisationFunktionRegistrering;
use App\Entity\Organisation\OrganisationFunktionRegistreringEgenskab;
use App\Entity\Organisation\OrganisationFunktionRegistreringFunktionstype;
use App\Entity\Organisation\OrganisationFunktionRegistreringGyldighed;
use App\Entity\Organisation\OrganisationFunktionRegistreringTilknyttedeBrugere;
use App\Entity\Organisation\OrganisationFunktionRegistreringTilknyttedeEnheder;
use App\Entity\Organisation\OrganisationFunktionRegistreringTilknyttedeOrganisationer;
use App\Exception\UnhandledException;
use App\Service\SF1500Service;
use Doctrine\ORM\EntityManagerInterface;
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
use Psr\Log\LoggerAwareTrait;

class OrganisationFunktionFetchService implements FetchServiceInterface
{
    use LoggerAwareTrait;

    public function __construct(private readonly EntityManagerInterface $entityManager, private readonly SF1500Service $sf1500Service)
    {
    }

    public function fetch(int $pageSize, int $max): void
    {
        $total = 0;

        // TODO: REMOVE ONCE TESTED AND WORKING
        //        $relationListeType = new RelationListeType();
        //        $relationListeType->addToTilknyttedeBrugere(
        //            new BrugerFlerRelationType(
        //                null,
        //                new UnikIdType('ffdb7559-2ad3-4662-9fd4-d69849939b66', null)
        //            )
        //        );


        $tilstandListeType = new TilstandListeType();
        $tilstandListeType->addToGyldighed(
            new GyldighedType(
                null,
                'Aktiv'
            )
        );

        while (true) {
            $this->logger->debug(sprintf('Fetching organisation funktion data, offset: %d , max: %d', $total, $max));
            $this->logger->debug(sprintf('Memory used: %d ', memory_get_usage() / 1024 / 1024));
            $request = (new SoegInputType())
                ->setMaksimalAntalKvantitet(min($pageSize, $max - $total))
                ->setFoersteResultatReference($total)
                // Only want active objects.
                ->setTilstandListe($tilstandListeType)
//                ->setRelationListe($relationListeType)
            ;

            /** @var SoegOutputType $data */
            $soeg = $this->clientSoeg()->soeg($request);

            $ids = $soeg->getIdListe()->getUUIDIdentifikator();

            if (!is_countable($ids) || empty($ids)) {
                break;
            }

            $brugerList = $this->clientList()->_list_10(new ListInputType($ids));

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

        $this->logger->debug(sprintf('Finished fetching organisation funktion data'));
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
        $organisationFunktion = new OrganisationFunktion();
        $organisationFunktion->setId($oejebliksbillede->getObjektType()->getUUIDIdentifikator());

        $this->entityManager->persist($organisationFunktion);

        $this->handleRegistrering($organisationFunktion, $oejebliksbillede->getRegistrering());
    }

    private function handleRegistrering(OrganisationFunktion $organisationFunktion, array $registreringer)
    {
        foreach ($registreringer as /* @var RegistreringType $registrering */ $registrering) {
            $organisationFunktionRegistrering = new OrganisationFunktionRegistrering();
            $organisationFunktion->addRegistreringer($organisationFunktionRegistrering);

            $organisationFunktionRegistrering
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

    private function handleEgenskab(OrganisationFunktionRegistrering $organisationFunktionRegistrering, ?array $egenskaber)
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

    private function handleGyldighed(OrganisationFunktionRegistrering $organisationFunktionRegistrering, ?array $gyldigheder)
    {
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

    private function handleRelation(OrganisationFunktionRegistrering $organisationFunktionRegistrering, ?RelationListeType $relation)
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

    private function handleAdresser(OrganisationFunktionRegistrering $organisationFunktionRegistrering, ?array $adresser)
    {
        if (null === $adresser) {
            return;
        } else {
            throw new UnhandledException(sprintf('Unhandled data in %s: %s.', __CLASS__, __FUNCTION__));
        }
    }

    private function handleFunktionstype(OrganisationFunktionRegistrering $organisationFunktionRegistrering, ?KlasseRelationType $funktionstype)
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

    private function handleTilknyttedeOpgaver(OrganisationFunktionRegistrering $organisationFunktionRegistrering, ?array $tilknyttedeOpgaver)
    {
        if (null === $tilknyttedeOpgaver) {
            return;
        } else {
            throw new UnhandledException(sprintf('Unhandled data in %s: %s.', __CLASS__, __FUNCTION__));
        }
    }

    private function handleTilknyttedeBrugere(OrganisationFunktionRegistrering $organisationFunktionRegistrering, ?array $tilknyttedeBrugere)
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

    private function handleTilknyttedeEnheder(OrganisationFunktionRegistrering $organisationFunktionRegistrering, ?array $tilknyttedeEnheder)
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

    private function handleTilknyttedeInteressefaellesskaber(OrganisationFunktionRegistrering $organisationFunktionRegistrering, ?array $tilknyttedeInteressefaellesskaber)
    {
        if (null === $tilknyttedeInteressefaellesskaber) {
            return;
        } else {
            throw new UnhandledException(sprintf('Unhandled data in %s: %s.', __CLASS__, __FUNCTION__));
        }
    }

    private function handleTilknyttedeOrganisationer(OrganisationFunktionRegistrering $organisationFunktionRegistrering, ?array $tilknyttedeOrganisationer)
    {
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

    private function handleTilknyttedePersoner(OrganisationFunktionRegistrering $organisationFunktionRegistrering, ?array $tilknyttedePersoner)
    {
        if (null === $tilknyttedePersoner) {
            return;
        } else {
            throw new UnhandledException(sprintf('Unhandled data in %s: %s.', __CLASS__, __FUNCTION__));
        }
    }

    private function handleTilknyttedeItSystemer(OrganisationFunktionRegistrering $organisationFunktionRegistrering, ?array $tilknyttedeItSystemer)
    {
        if (null === $tilknyttedeItSystemer) {
            return;
        } else {
            throw new UnhandledException(sprintf('Unhandled data in %s: %s.', __CLASS__, __FUNCTION__));
        }
    }

    private function handleLokalUdvidelse(OrganisationFunktionRegistrering $organisationFunktionRegistrering, ?LokalUdvidelseType $lokalUdvidelse)
    {
        if (null === $lokalUdvidelse) {
            return;
        } else {
            throw new UnhandledException(sprintf('Unhandled data in %s: %s.', __CLASS__, __FUNCTION__));
        }
    }
}
