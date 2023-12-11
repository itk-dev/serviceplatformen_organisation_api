<?php

namespace App\Entity\SF1500;

use App\Repository\SF1500\OrganisationFunktionRegistreringFunktionstypeRepository;
use App\Trait\ReferenceIdTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV4;

#[ORM\Entity(repositoryClass: OrganisationFunktionRegistreringFunktionstypeRepository::class)]
#[ORM\Index(columns: ['reference_id_uuididentifikator'], name: 'reference_id_uuididentifikator_idx')]
class OrganisationFunktionRegistreringFunktionstype
{
    use ReferenceIdTrait;

    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    private UuidV4 $id;

    public function __construct()
    {
        $this->id = Uuid::v4();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }
}
