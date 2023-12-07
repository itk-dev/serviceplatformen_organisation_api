<?php

namespace App\Entity\SF1500;

use App\Repository\SF1500\BrugerRegistreringAdresseRepository;
use App\Trait\ReferenceIdTrait;
use App\Trait\RolleTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV4;

#[ORM\Entity(repositoryClass: BrugerRegistreringAdresseRepository::class)]
#[ORM\Index(columns: ["reference_id_uuididentifikator"], name: "bruger_ref_uuid_idx")]
class BrugerRegistreringAdresse
{
    use ReferenceIdTrait;
    use RolleTrait;

    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    private UuidV4 $id;

    #[ORM\ManyToOne(inversedBy: 'adresser')]
    #[ORM\JoinColumn(nullable: false)]
    private ?BrugerRegistrering $brugerRegistrering = null;

    public function __construct()
    {
        $this->id = Uuid::v4();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getBrugerRegistrering(): ?BrugerRegistrering
    {
        return $this->brugerRegistrering;
    }

    public function setBrugerRegistrering(?BrugerRegistrering $brugerRegistrering): static
    {
        $this->brugerRegistrering = $brugerRegistrering;

        return $this;
    }
}
