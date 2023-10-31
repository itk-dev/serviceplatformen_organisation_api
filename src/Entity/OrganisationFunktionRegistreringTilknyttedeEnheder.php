<?php

namespace App\Entity;

use App\Repository\OrganisationFunktionRegistreringTilknyttedeEnhederRepository;
use App\Trait\ReferenceIdTrait;
use App\Trait\VirkningTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV4;

#[ORM\Entity(repositoryClass: OrganisationFunktionRegistreringTilknyttedeEnhederRepository::class)]
class OrganisationFunktionRegistreringTilknyttedeEnheder
{
    use VirkningTrait;
    use ReferenceIdTrait;

    #[ORM\Id]
    #[ORM\Column(type: "uuid", unique: true)]
    private UuidV4 $id;

    #[ORM\ManyToOne(inversedBy: 'tilknyttedeEnheder')]
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
