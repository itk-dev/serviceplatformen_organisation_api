<?php

namespace App\Entity\SF1500;

use App\Repository\SF1500\OrganisationEnhedRegistreringGyldighedRepository;
use App\Trait\GyldighedStatusKodeTrait;
use App\Trait\VirkningTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV4;

#[ORM\Entity(repositoryClass: OrganisationEnhedRegistreringGyldighedRepository::class)]
class OrganisationEnhedRegistreringGyldighed
{
    use VirkningTrait;
    use GyldighedStatusKodeTrait;

    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    private UuidV4 $id;

    #[ORM\ManyToOne(inversedBy: 'gyldigheder')]
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
