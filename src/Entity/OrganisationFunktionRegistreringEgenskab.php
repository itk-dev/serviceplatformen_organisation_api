<?php

namespace App\Entity;

use App\Repository\OrganisationFunktionRegistreringEgenskabRepository;
use App\Trait\BrugervendtNoegleTekstTrait;
use App\Trait\VirkningTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV4;

#[ORM\Entity(repositoryClass: OrganisationFunktionRegistreringEgenskabRepository::class)]
class OrganisationFunktionRegistreringEgenskab
{
    use VirkningTrait;
    use BrugervendtNoegleTekstTrait;

    #[ORM\Id]
    #[ORM\Column(type: "uuid", unique: true)]
    private UuidV4 $id;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $funktionNavn = null;

    #[ORM\ManyToOne(inversedBy: 'egenskaber')]
    #[ORM\JoinColumn(nullable: false)]
    private ?OrganisationFunktionRegistrering $organisationFunktionRegistrering = null;

    public function __construct()
    {
        $this->id = Uuid::v4();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getFunktionNavn(): ?string
    {
        return $this->funktionNavn;
    }

    public function setFunktionNavn(?string $funktionNavn): static
    {
        $this->funktionNavn = $funktionNavn;

        return $this;
    }

    public function getOrganisationFunktionRegistrering(): ?OrganisationFunktionRegistrering
    {
        return $this->organisationFunktionRegistrering;
    }

    public function setOrganisationFunktionRegistrering(?OrganisationFunktionRegistrering $organisationFunktionRegistrering): static
    {
        $this->organisationFunktionRegistrering = $organisationFunktionRegistrering;

        return $this;
    }
}
