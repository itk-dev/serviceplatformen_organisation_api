<?php

namespace App\Service;

use App\Entity\Person;
use App\Entity\PersonRegistrering;
use App\Entity\PersonRegistreringEgenskab;
use Doctrine\ORM\EntityManagerInterface;
use ItkDev\Serviceplatformen\SF1500\Person\ServiceType\_List;
use ItkDev\Serviceplatformen\SF1500\Person\ServiceType\Soeg;
use ItkDev\Serviceplatformen\SF1500\Person\StructType\EgenskabType;
use ItkDev\Serviceplatformen\SF1500\Person\StructType\FiltreretOejebliksbilledeType;
use ItkDev\Serviceplatformen\SF1500\Person\StructType\ListInputType;
use ItkDev\Serviceplatformen\SF1500\Person\StructType\RegistreringType;
use ItkDev\Serviceplatformen\SF1500\Person\StructType\SoegInputType;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;
use Symfony\Component\HttpFoundation\Response;

class FetchData
{
    use LoggerAwareTrait;

    public function __construct(private readonly EntityManagerInterface $entityManager, private readonly SF1500Service $sf1500Service)
    {
        $this->setLogger(new NullLogger());
    }

    public function fetch($pageSize = 1000, $max = null): Response
    {
        $this->fetchPerson($pageSize, $max);

        return new Response(
            '<html lang="en"><body>Lucky number: '.'</body></html>'
        );
    }

    private function clientSoeg(array $options = []): Soeg
    {
        $client = $this->sf1500Service->getSF1500()->getClient(Soeg::class, $options);
        assert($client instanceof Soeg);

        return $client;
    }

    private function clientList(array $options = []): _List
    {
        $client = $this->sf1500Service->getSF1500()->getClient(_List::class, $options);
        assert($client instanceof _List);

        return $client;
    }

    private function handleOejebliksbillede(FiltreretOejebliksbilledeType $oejebliksbillede)
    {
        $person = new Person();
        $person->setId($oejebliksbillede->getObjektType()->getUUIDIdentifikator());

        $this->entityManager->persist($person);

        $this->handleRegistrering($person, $oejebliksbillede->getRegistrering());
        $person = null;
    }

    private function handleRegistrering(Person $person, array $registreringer)
    {
        foreach ($registreringer as /** @var RegistreringType $registrering */ $registrering) {

            $personRegistrering = new PersonRegistrering();
            $person->addRegistreringer($personRegistrering);

            $personRegistrering->setTidspunkt($registrering->getTidspunkt());
            $personRegistrering->setNoteTekst($registrering->getNoteTekst());
            $personRegistrering->setLivscyklusKode($registrering->getLivscyklusKode());

            $this->entityManager->persist($personRegistrering);

            $this->handleEgenskab($personRegistrering, $registrering->getAttributListe()->getEgenskab());
            $personRegistrering = null;

        }
    }

    private function handleEgenskab(PersonRegistrering $personRegistrering, array $egenskaber)
    {
        foreach ($egenskaber as /** @var EgenskabType $egenskab */ $egenskab) {
            $personRegistreringEgenskab = new PersonRegistreringEgenskab();
            $personRegistrering->addEgenskaber($personRegistreringEgenskab);

            $personRegistreringEgenskab->setNavnTekst($egenskab->getNavnTekst());
            // Commented out due to undefined property.
            // Warning: Undefined property: ItkDev\Serviceplatformen\SF1500\Person\StructType\EgenskabType::$CPR-NummerTekst
            // $personRegistreringEgenskab->setCprNummerTekst($egenskab->getCPR_NummerTekst());
            $personRegistreringEgenskab->setBrugervendtNoegleTekst($egenskab->getBrugervendtNoegleTekst());

            $this->entityManager->persist($personRegistreringEgenskab);
            $personRegistreringEgenskab = null;
        }
    }

    private function fetchPerson(mixed $pageSize, mixed $max)
    {
        $total = 0;

        // TODO: REMOVE ONCE TESTED AND WOKRING
//        $attributListe = new AttributListeType();
//        $attributListe->addToEgenskab((new EgenskabType())
//            ->setNavnTekst('Jeppe Kuhl*'));

        while(true) {
            $this->logger->debug(sprintf('Fetching data, offset: %d , max: %d', $total, $max));
            $this->logger->debug(sprintf('Memory used: %d ', memory_get_usage()/1024/1024));
            $request = (new SoegInputType())
                ->setMaksimalAntalKvantitet(min($pageSize, $max))
                ->setFoersteResultatReference($total)
//                ->setAttributListe($attributListe)
            ;

            /** @var \ItkDev\Serviceplatformen\SF1500\Person\StructType\SoegOutputType $data */
            $soeg = $this->clientSoeg()->soeg($request);


            $ids = $soeg->getIdListe()->getUUIDIdentifikator();

            $total += count($ids);

            if (empty($ids) || $total > $max) {
                break;
            }

            $personList = $this->clientList()->_list_11(new ListInputType($ids));

            $this->entityManager->getConnection()->beginTransaction();

            foreach ($personList->getFiltreretOejebliksbillede() as /** @var FiltreretOejebliksbilledeType $oejebliksbillede */ $oejebliksbillede) {
                $this->handleOejebliksbillede($oejebliksbillede);
            }

            $this->entityManager->flush();
            $this->entityManager->getConnection()->commit();
            $this->entityManager->clear();
            gc_collect_cycles();
        }

        $this->logger->debug(sprintf('Finished fetching dataFetching data'));
    }
}