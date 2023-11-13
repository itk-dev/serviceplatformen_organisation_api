<?php

namespace App\Entity;

use App\Repository\BrugerRegistreringEgenskabRepository;
use App\Trait\BrugervendtNoegleTekstTrait;
use App\Trait\VirkningTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV4;

#[ORM\Entity(repositoryClass: BrugerRegistreringEgenskabRepository::class)]
class BrugerRegistreringEgenskab
{
    use VirkningTrait;
    use BrugervendtNoegleTekstTrait;

    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    private UuidV4 $id;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $brugerNavn = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $brugerTypeTekst = null;

    #[ORM\ManyToOne(inversedBy: 'egenskaber')]
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

    public function getBrugerNavn(): ?string
    {
        return $this->brugerNavn;
    }

    public function setBrugerNavn(?string $brugerNavn): static
    {
        $this->brugerNavn = $brugerNavn;

        return $this;
    }

    public function getBrugerTypeTekst(): ?string
    {
        return $this->brugerTypeTekst;
    }

    public function setBrugerTypeTekst(?string $brugerTypeTekst): static
    {
        $this->brugerTypeTekst = $brugerTypeTekst;

        return $this;
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
