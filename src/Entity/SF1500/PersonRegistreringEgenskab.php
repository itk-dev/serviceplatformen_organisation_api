<?php

namespace App\Entity\SF1500;

use App\Repository\SF1500\PersonRegistreringEgenskabRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV4;

#[ORM\Entity(repositoryClass: PersonRegistreringEgenskabRepository::class)]
class PersonRegistreringEgenskab
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    private UuidV4 $id;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $cprNummerTekst = null;

    #[ORM\Column(length: 255)]
    private string $navnTekst;

    #[ORM\ManyToOne(inversedBy: 'egenskaber')]
    #[ORM\JoinColumn(nullable: false)]
    private PersonRegistrering $personRegistrering;

    public function __construct()
    {
        $this->id = Uuid::v4();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getCprNummerTekst(): ?string
    {
        return $this->cprNummerTekst;
    }

    public function setCprNummerTekst(?string $cprNummerTekst): static
    {
        $this->cprNummerTekst = $cprNummerTekst;

        return $this;
    }

    public function getNavnTekst(): string
    {
        return $this->navnTekst;
    }

    public function setNavnTekst(string $navnTekst): static
    {
        $this->navnTekst = $navnTekst;

        return $this;
    }

    public function getPersonRegistrering(): PersonRegistrering
    {
        return $this->personRegistrering;
    }

    public function setPersonRegistrering(?PersonRegistrering $personRegistrering): static
    {
        $this->personRegistrering = $personRegistrering;

        return $this;
    }
}
