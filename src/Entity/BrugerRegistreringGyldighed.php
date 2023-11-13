<?php

namespace App\Entity;

use App\Repository\BrugerRegistreringGyldighedRepository;
use App\Trait\GyldighedStatusKodeTrait;
use App\Trait\VirkningTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV4;

#[ORM\Entity(repositoryClass: BrugerRegistreringGyldighedRepository::class)]
class BrugerRegistreringGyldighed
{
    use VirkningTrait;
    use GyldighedStatusKodeTrait;

    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    private UuidV4 $id;

    #[ORM\ManyToOne(inversedBy: 'gyldigheder')]
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
