<?php

namespace App\Entity\SF1500;

use App\Repository\SF1500\OrganisationFunktionRegistreringRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV4;

#[ORM\Entity(repositoryClass: OrganisationFunktionRegistreringRepository::class)]
class OrganisationFunktionRegistrering
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    private UuidV4 $id;

    #[ORM\Column(length: 255)]
    private string $organisationFunktionId;

    #[ORM\OneToMany(mappedBy: 'organisationFunktionRegistrering', targetEntity: OrganisationFunktionRegistreringEgenskab::class, orphanRemoval: true)]
    private Collection $egenskaber;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?OrganisationFunktionRegistreringFunktionstype $funktionstype = null;

    #[ORM\OneToMany(mappedBy: 'organisationFunktionRegistrering', targetEntity: OrganisationFunktionRegistreringTilknyttedeBrugere::class, orphanRemoval: true)]
    private Collection $tilknyttedeBrugere;

    #[ORM\OneToMany(mappedBy: 'organisationFunktionRegistrering', targetEntity: OrganisationFunktionRegistreringTilknyttedeEnheder::class, orphanRemoval: true)]
    private Collection $tilknyttedeEnheder;

    public function __construct()
    {
        $this->id = Uuid::v4();
        $this->egenskaber = new ArrayCollection();
        $this->tilknyttedeBrugere = new ArrayCollection();
        $this->tilknyttedeEnheder = new ArrayCollection();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getOrganisationFunktionId(): string
    {
        return $this->organisationFunktionId;
    }

    public function setOrganisationFunktionId(string $organisationFunktionId): static
    {
        $this->organisationFunktionId = $organisationFunktionId;

        return $this;
    }

    /**
     * @return Collection<int, OrganisationFunktionRegistreringEgenskab>
     */
    public function getEgenskaber(): Collection
    {
        return $this->egenskaber;
    }

    public function addEgenskaber(OrganisationFunktionRegistreringEgenskab $egenskaber): static
    {
        if (!$this->egenskaber->contains($egenskaber)) {
            $this->egenskaber->add($egenskaber);
            $egenskaber->setOrganisationFunktionRegistrering($this);
        }

        return $this;
    }

    public function removeEgenskaber(OrganisationFunktionRegistreringEgenskab $egenskaber): static
    {
        if ($this->egenskaber->removeElement($egenskaber)) {
            // set the owning side to null (unless already changed)
            if ($egenskaber->getOrganisationFunktionRegistrering() === $this) {
                $egenskaber->setOrganisationFunktionRegistrering(null);
            }
        }

        return $this;
    }

    public function getFunktionstype(): ?OrganisationFunktionRegistreringFunktionstype
    {
        return $this->funktionstype;
    }

    public function setFunktionstype(?OrganisationFunktionRegistreringFunktionstype $funktionstype): static
    {
        $this->funktionstype = $funktionstype;

        return $this;
    }

    /**
     * @return Collection<int, OrganisationFunktionRegistreringTilknyttedeBrugere>
     */
    public function getTilknyttedeBrugere(): Collection
    {
        return $this->tilknyttedeBrugere;
    }

    public function addTilknyttedeBrugere(OrganisationFunktionRegistreringTilknyttedeBrugere $tilknyttedeBrugere): static
    {
        if (!$this->tilknyttedeBrugere->contains($tilknyttedeBrugere)) {
            $this->tilknyttedeBrugere->add($tilknyttedeBrugere);
            $tilknyttedeBrugere->setOrganisationFunktionRegistrering($this);
        }

        return $this;
    }

    public function removeTilknyttedeBrugere(OrganisationFunktionRegistreringTilknyttedeBrugere $tilknyttedeBrugere): static
    {
        if ($this->tilknyttedeBrugere->removeElement($tilknyttedeBrugere)) {
            // set the owning side to null (unless already changed)
            if ($tilknyttedeBrugere->getOrganisationFunktionRegistrering() === $this) {
                $tilknyttedeBrugere->setOrganisationFunktionRegistrering(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, OrganisationFunktionRegistreringTilknyttedeEnheder>
     */
    public function getTilknyttedeEnheder(): Collection
    {
        return $this->tilknyttedeEnheder;
    }

    public function addTilknyttedeEnheder(OrganisationFunktionRegistreringTilknyttedeEnheder $tilknyttedeEnheder): static
    {
        if (!$this->tilknyttedeEnheder->contains($tilknyttedeEnheder)) {
            $this->tilknyttedeEnheder->add($tilknyttedeEnheder);
            $tilknyttedeEnheder->setOrganisationFunktionRegistrering($this);
        }

        return $this;
    }

    public function removeTilknyttedeEnheder(OrganisationFunktionRegistreringTilknyttedeEnheder $tilknyttedeEnheder): static
    {
        if ($this->tilknyttedeEnheder->removeElement($tilknyttedeEnheder)) {
            // set the owning side to null (unless already changed)
            if ($tilknyttedeEnheder->getOrganisationFunktionRegistrering() === $this) {
                $tilknyttedeEnheder->setOrganisationFunktionRegistrering(null);
            }
        }

        return $this;
    }
}
