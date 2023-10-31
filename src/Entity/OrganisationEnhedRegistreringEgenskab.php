<?php

namespace App\Entity;

use App\Repository\OrganisationEnhedRegistreringEgenskabRepository;
use App\Trait\BrugervendtNoegleTekstTrait;
use App\Trait\VirkningTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV4;

#[ORM\Entity(repositoryClass: OrganisationEnhedRegistreringEgenskabRepository::class)]
class OrganisationEnhedRegistreringEgenskab
{
    use VirkningTrait;
    use BrugervendtNoegleTekstTrait;

    #[ORM\Id]
    #[ORM\Column(type: "uuid", unique: true)]
    private UuidV4 $id;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $enhedNavn = null;

    #[ORM\ManyToOne(inversedBy: 'egenskaber')]
    #[ORM\JoinColumn(nullable: false)]
    private ?OrganisationEnhedRegistrering $organisationEnhedRegistrering = null;

    public function __construct()
    {
        $this->id = Uuid::v4();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getEnhedNavn(): ?string
    {
        return $this->enhedNavn;
    }

    public function setEnhedNavn(?string $enhedNavn): static
    {
        $this->enhedNavn = $enhedNavn;

        return $this;
    }

    public function getOrganisationEnhedRegistrering(): ?OrganisationEnhedRegistrering
    {
        return $this->organisationEnhedRegistrering;
    }

    public function setOrganisationEnhedRegistrering(?OrganisationEnhedRegistrering $organisationEnhedRegistrering): static
    {
        $this->organisationEnhedRegistrering = $organisationEnhedRegistrering;

        return $this;
    }
}
