<?php

namespace App\Service\SF1500;

use App\Entity\Organisation\Person;
use App\Entity\Organisation\PersonRegistrering;
use App\Entity\Organisation\PersonRegistreringEgenskab;
use App\Service\SF1500Service;
use Doctrine\ORM\EntityManagerInterface;
use ItkDev\Serviceplatformen\SF1500\Person\ServiceType\_List;
use ItkDev\Serviceplatformen\SF1500\Person\ServiceType\Soeg;
use ItkDev\Serviceplatformen\SF1500\Person\StructType\EgenskabType;
use ItkDev\Serviceplatformen\SF1500\Person\StructType\FiltreretOejebliksbilledeType;
use ItkDev\Serviceplatformen\SF1500\Person\StructType\ListInputType;
use ItkDev\Serviceplatformen\SF1500\Person\StructType\RegistreringType;
use ItkDev\Serviceplatformen\SF1500\Person\StructType\SoegInputType;
use Psr\Log\LoggerAwareTrait;

class PersonFetchService implements FetchServiceInterface
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
        //            ->setNavnTekst('Jeppe Kuhl*'));

        while (true) {
            $this->logger->debug(sprintf('Fetching person data, offset: %d , max: %d', $total, $max));
            $this->logger->debug(sprintf('Memory used: %d ', memory_get_usage() / 1024 / 1024));
            $request = (new SoegInputType())
                ->setMaksimalAntalKvantitet(min($pageSize, $max - $total))
                ->setFoersteResultatReference($total)
//                ->setAttributListe($attributListe)
            ;

            /** @var \ItkDev\Serviceplatformen\SF1500\Person\StructType\SoegOutputType $data */
            $soeg = $this->clientSoeg()->soeg($request);

            $ids = $soeg->getIdListe()->getUUIDIdentifikator();

            if (!is_countable($ids) || empty($ids)) {
                break;
            }

            $personList = $this->clientList()->_list_11(new ListInputType($ids));

            foreach ($personList->getFiltreretOejebliksbillede() as /* @var FiltreretOejebliksbilledeType $oejebliksbillede */ $oejebliksbillede) {
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

        $this->logger->debug(sprintf('Finished fetching person data'));
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
        $person = new Person();
        $person->setId($oejebliksbillede->getObjektType()->getUUIDIdentifikator());

        $this->entityManager->persist($person);

        $this->handleRegistrering($person, $oejebliksbillede->getRegistrering());
    }

    private function handleRegistrering(Person $person, array $registreringer): void
    {
        foreach ($registreringer as /* @var RegistreringType $registrering */ $registrering) {
            $personRegistrering = new PersonRegistrering();
            $person->addRegistreringer($personRegistrering);

            $personRegistrering
                ->setTidspunkt($registrering->getTidspunkt())
                ->setNoteTekst($registrering->getNoteTekst())
                ->setLivscyklusKode($registrering->getLivscyklusKode())
                ->setBrugerRefUUIDIdentifikator($registrering->getBrugerRef()->getUUIDIdentifikator())
                ->setBrugerRefURNIdentifikator($registrering->getBrugerRef()->getURNIdentifikator())
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
                ->setBrugervendtNoegleTekst($egenskab->getBrugervendtNoegleTekst())
            ;
            // Left out due to undefined property.
            // Warning: Undefined property: ItkDev\Serviceplatformen\SF1500\Person\StructType\EgenskabType::$CPR-NummerTekst
            // $personRegistreringEgenskab->setCprNummerTekst($egenskab->getCPR_NummerTekst());

            // Virkning.
            $virkning = $egenskab->getVirkning();

            $personRegistreringEgenskab
                ->setVirkningFraTidsstempelDatoTid($virkning->getFraTidspunkt()->getTidsstempelDatoTid())
                ->setVirkningFraGraenseIndikator($virkning->getFraTidspunkt()->getGraenseIndikator())
                ->setVirkningTilTidsstempelDatoTid($virkning->getTilTidspunkt()->getTidsstempelDatoTid())
                ->setVirkningTilGraenseIndikator($virkning->getTilTidspunkt()->getGraenseIndikator())
                ->setVirkningAktoerRefUUIDIdentifikator($virkning->getAktoerRef()->getUUIDIdentifikator())
                ->setVirkningAktoerRefURNIdentifikator($virkning->getAktoerRef()->getURNIdentifikator())
                ->setVirkningAktoerTypeKode($virkning->getAktoerTypeKode())
                ->setVirkningNoteTekst($virkning->getNoteTekst())
            ;

            $this->entityManager->persist($personRegistreringEgenskab);
        }
    }
}
