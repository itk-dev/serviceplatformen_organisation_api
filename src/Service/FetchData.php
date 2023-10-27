<?php

namespace App\Service;

use App\Entity\Person;
use App\Entity\PersonRegistrering;
use App\Entity\PersonRegistreringEgenskab;
use Doctrine\DBAL\Connection;
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
use Symfony\Component\Uid\UuidV4;

class FetchData
{
    use LoggerAwareTrait;

    public function __construct(private readonly EntityManagerInterface $entityManager, private readonly SF1500Service $sf1500Service)
    {
        $this->setLogger(new NullLogger());
    }

    public function fetch($pageSize = 1000, $max = null): Response
    {
        $total = 0;

        // TODO: REMOVE ONCE TESTED AND WOKRING
//        $attributListe = new AttributListeType();
//        $attributListe->addToEgenskab((new EgenskabType())
//            ->setNavnTekst('Jeppe Kuhl*'));

        while(true) {
            $this->logger->debug(sprintf('Fetching data, offset: %d , max: %d', $total, $pageSize));
            $this->logger->debug(sprintf('Memory used: %d ', memory_get_usage()/1024/1024));
            $request = (new SoegInputType())
                ->setMaksimalAntalKvantitet(min($pageSize, $max))
                ->setFoersteResultatReference($total)
//                ->setAttributListe($attributListe)
            ;

            /** @var \ItkDev\Serviceplatformen\SF1500\Person\StructType\SoegOutputType $data */
            $soeg = $this->clientSoeg()->soeg($request);


            $ids = $soeg->getIdListe()->getUUIDIdentifikator();

            // To limit memory we set request and response to null.
            $request = null;
            $soeg = null;

            $total += count($ids);

            if (empty($ids) || $total > $max) {
                break;
            }


            $personList = $this->clientList()->_list_11(new ListInputType($ids));

            foreach ($personList->getFiltreretOejebliksbillede() as /** @var FiltreretOejebliksbilledeType $oejebliksbillede */ $oejebliksbillede) {
                $this->handleOejebliksbillede($oejebliksbillede);
            }

            // To limit memory we set request and response to null.
            $personList = null;

//            $this->entityManager->flush();
//
//            $this->entityManager->clear();
//            gc_collect_cycles();
        }

        $this->logger->debug(sprintf('Finished fetching dataFetching data'));
        

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
        $id = $oejebliksbillede->getObjektType()->getUUIDIdentifikator();

        $this->insert('person', ['id' => $id]);

        $this->handleRegistrering($id, $oejebliksbillede->getRegistrering());
    }

    private function handleRegistrering(string $personId, array $registreringer)
    {
        foreach ($registreringer as /** @var RegistreringType $registrering */ $registrering) {

            $registreringsId = (new UuidV4())->toBinary();

            $this->insert('person_registrering', [
                'id' => $registreringsId,
                'person_id' => $personId,
                'note_tekst' =>  $registrering->getNoteTekst(),
                'tidspunkt' => $registrering->getTidspunkt(),
                'livscyklus_kode' => $registrering->getLivscyklusKode()
            ]);

            $this->handleEgenskab($registreringsId, $registrering->getAttributListe()->getEgenskab());

        }
    }

    private function handleEgenskab(string $registreringsId, array $egenskaber)
    {
        foreach ($egenskaber as /** @var EgenskabType $egenskab */ $egenskab) {

            $egenskabId = (new UuidV4())->toBinary();

            $this->insert('person_registrering_egenskab', [
                'id' => $egenskabId,
                'person_registrering_id' => $registreringsId,
                'brugervendt_noegle_tekst' => $egenskab->getBrugervendtNoegleTekst(),
//                'cpr_nummer_tekst' => $egenskab->getCPR_NummerTekst(),
                'navn_tekst' => $egenskab->getNavnTekst(),
            ]);
        }
    }

    private function insert(string $table, array $data)
    {
        $connection = $this->entityManager->getConnection();

        $connection->insert($table, $data);
    }
}