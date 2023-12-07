<?php

namespace App\Entity\SF1500;

use App\Repository\SF1500\AdresseRegistreringEgenskabRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV4;

#[ORM\Entity(repositoryClass: AdresseRegistreringEgenskabRepository::class)]
class AdresseRegistreringEgenskab
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    private UuidV4 $id;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $adresseTekst = null;

    #[ORM\ManyToOne(inversedBy: 'egenskaber')]
    #[ORM\JoinColumn(nullable: false)]
    private ?AdresseRegistrering $adresseRegistrering = null;

    public function __construct()
    {
        $this->id = Uuid::v4();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getAdresseTekst(): ?string
    {
        return $this->adresseTekst;
    }

    public function setAdresseTekst(?string $adresseTekst): static
    {
        $this->adresseTekst = $adresseTekst;

        return $this;
    }

    public function getAdresseRegistrering(): ?AdresseRegistrering
    {
        return $this->adresseRegistrering;
    }

    public function setAdresseRegistrering(?AdresseRegistrering $adresseRegistrering): static
    {
        $this->adresseRegistrering = $adresseRegistrering;

        return $this;
    }
}
