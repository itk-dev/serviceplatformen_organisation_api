<?php

namespace App\Entity\SF1500;

use App\Repository\SF1500\OrganisationFunktionRegistreringTilknyttedeEnhederRepository;
use App\Trait\ReferenceIdTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV4;

#[ORM\Entity(repositoryClass: OrganisationFunktionRegistreringTilknyttedeEnhederRepository::class)]
#[ORM\Index(columns: ['reference_id_uuididentifikator'], name: 'reference_id_uuididentifikator_idx')]
class OrganisationFunktionRegistreringTilknyttedeEnheder
{
    use ReferenceIdTrait;

    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
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
