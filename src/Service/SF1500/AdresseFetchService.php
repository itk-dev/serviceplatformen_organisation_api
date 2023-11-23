<?php

namespace App\Service\SF1500;

use App\Entity\SF1500\Adresse;
use App\Entity\SF1500\AdresseRegistrering;
use App\Entity\SF1500\AdresseRegistreringEgenskab;
use App\Exception\UnhandledException;
use App\Service\SF1500Service;
use Doctrine\ORM\EntityManagerInterface;
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
use Psr\Log\LoggerAwareTrait;

class AdresseFetchService implements FetchServiceInterface
{
    use LoggerAwareTrait;

    public function __construct(private readonly EntityManagerInterface $entityManager, private readonly SF1500Service $sf1500Service)
    {
    }

    public function fetch(int $pageSize, int $max): void
    {
        $total = 0;

        while (true) {
            $this->logger->debug(sprintf('Fetching adresse data, offset: %d , max: %d', $total, $max));
            $this->logger->debug(sprintf('Memory used: %d ', memory_get_usage() / 1024 / 1024));
            $request = (new SoegInputType())
                ->setMaksimalAntalKvantitet(min($pageSize, $max - $total))
                ->setFoersteResultatReference($total)
            ;

            /** @var SoegOutputType $data */
            $soeg = $this->clientSoeg()->soeg($request);

            $ids = $soeg->getIdListe()->getUUIDIdentifikator();

            if (!is_countable($ids) || empty($ids)) {
                break;
            }

            $brugerList = $this->clientList()->_list(new ListInputType($ids));

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

        $this->logger->debug(sprintf('Finished fetching adresse data'));
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
        $this->handleRegistrering($oejebliksbillede->getObjektType()->getUUIDIdentifikator(), $oejebliksbillede->getRegistrering());
    }

    private function handleRegistrering(string $adresseId, ?array $registreringer)
    {
        foreach ($registreringer as /* @var RegistreringType $registrering */ $registrering) {
            $adresseRegistrering = new AdresseRegistrering();

            $adresseRegistrering
                ->setAdresseId($adresseId)
                ->setTidspunkt($registrering->getTidspunkt())
                ->setNoteTekst($registrering->getNoteTekst())
                ->setLivscyklusKode($registrering->getLivscyklusKode())
                ->setBrugerRefUUIDIdentifikator($registrering->getBrugerRef()->getUUIDIdentifikator())
                ->setBrugerRefURNIdentifikator($registrering->getBrugerRef()->getURNIdentifikator())
            ;

            $this->entityManager->persist($adresseRegistrering);

            $this->handleEgenskab($adresseRegistrering, $registrering->getAttributListe()->getEgenskab());
            $this->handleRelation($adresseRegistrering, $registrering->getRelationListe());
            $this->handleTilstand($adresseRegistrering, $registrering->getTilstandListe());
        }
    }

    private function handleEgenskab(AdresseRegistrering $adresseRegistrering, ?array $egenskaber)
    {
        if (null === $egenskaber) {
            return;
        }

        foreach ($egenskaber as /* @var EgenskabType $egenskab */ $egenskab) {
            $adresseRegistreringEgenskab = new AdresseRegistreringEgenskab();
            $adresseRegistrering->addEgenskaber($adresseRegistreringEgenskab);

            $adresseRegistreringEgenskab
                ->setAdresseTekst($egenskab->getAdresseTekst())
                ->setBrugervendtNoegleTekst($egenskab->getBrugervendtNoegleTekst())
            ;

            // Virkning.
            $virkning = $egenskab->getVirkning();

            $adresseRegistreringEgenskab
                ->setVirkningFraTidsstempelDatoTid($virkning->getFraTidspunkt()->getTidsstempelDatoTid())
                ->setVirkningFraGraenseIndikator($virkning->getFraTidspunkt()->getGraenseIndikator())
                ->setVirkningTilTidsstempelDatoTid($virkning->getTilTidspunkt()->getTidsstempelDatoTid())
                ->setVirkningTilGraenseIndikator($virkning->getTilTidspunkt()->getGraenseIndikator())
                ->setVirkningAktoerRefUUIDIdentifikator($virkning->getAktoerRef()->getUUIDIdentifikator())
                ->setVirkningAktoerRefURNIdentifikator($virkning->getAktoerRef()->getURNIdentifikator())
                ->setVirkningAktoerTypeKode($virkning->getAktoerTypeKode())
                ->setVirkningNoteTekst($virkning->getNoteTekst())
            ;

            $this->entityManager->persist($adresseRegistreringEgenskab);
        }
    }

    private function handleRelation(AdresseRegistrering $adresseRegistrering, ?RelationListeType $relationListeType)
    {
        if (empty($relationListeType->jsonSerialize())) {
            return;
        } else {
            throw new UnhandledException(sprintf('Unhandled data in %s: %s.', __CLASS__, __FUNCTION__));
        }
    }

    private function handleTilstand(AdresseRegistrering $adresseRegistrering, ?TilstandListeType $tilstandListeType)
    {
        if (empty($tilstandListeType->jsonSerialize())) {
            return;
        } else {
            throw new UnhandledException(sprintf('Unhandled data in %s: %s.', __CLASS__, __FUNCTION__));
        }
    }
}
