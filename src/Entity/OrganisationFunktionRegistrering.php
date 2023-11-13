<?php

namespace App\Entity;

use App\Repository\OrganisationFunktionRegistreringRepository;
use App\Trait\BrugerRefTrait;
use App\Trait\RegistreringTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV4;

#[ORM\Entity(repositoryClass: OrganisationFunktionRegistreringRepository::class)]
class OrganisationFunktionRegistrering
{
    use RegistreringTrait;
    use BrugerRefTrait;

    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    private UuidV4 $id;

    #[ORM\ManyToOne(inversedBy: 'registreringer')]
    #[ORM\JoinColumn(nullable: false)]
    private ?OrganisationFunktion $organisationFunktion = null;

    #[ORM\OneToMany(mappedBy: 'organisationFunktionRegistrering', targetEntity: OrganisationFunktionRegistreringEgenskab::class, orphanRemoval: true)]
    private Collection $egenskaber;

    #[ORM\OneToMany(mappedBy: 'organisationFunktionRegistrering', targetEntity: OrganisationFunktionRegistreringGyldighed::class, orphanRemoval: true)]
    private Collection $gyldigheder;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?OrganisationFunktionRegistreringFunktionstype $funktionstype = null;

    #[ORM\OneToMany(mappedBy: 'organisationFunktionRegistrering', targetEntity: OrganisationFunktionRegistreringTilknyttedeBrugere::class, orphanRemoval: true)]
    private Collection $tilknyttedeBrugere;

    #[ORM\OneToMany(mappedBy: 'organisationFunktionRegistrering', targetEntity: OrganisationFunktionRegistreringTilknyttedeEnheder::class, orphanRemoval: true)]
    private Collection $tilknyttedeEnheder;

    #[ORM\OneToMany(mappedBy: 'organisationFunktionRegistrering', targetEntity: OrganisationFunktionRegistreringTilknyttedeOrganisationer::class, orphanRemoval: true)]
    private Collection $tilknyttedeOrganisationer;

    public function __construct()
    {
        $this->id = Uuid::v4();
        $this->egenskaber = new ArrayCollection();
        $this->gyldigheder = new ArrayCollection();
        $this->tilknyttedeBrugere = new ArrayCollection();
        $this->tilknyttedeEnheder = new ArrayCollection();
        $this->tilknyttedeOrganisationer = new ArrayCollection();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getOrganisationFunktion(): ?OrganisationFunktion
    {
        return $this->organisationFunktion;
    }

    public function setOrganisationFunktion(?OrganisationFunktion $organisationFunktion): static
    {
        $this->organisationFunktion = $organisationFunktion;

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

    /**
     * @return Collection<int, OrganisationFunktionRegistreringGyldighed>
     */
    public function getGyldigheder(): Collection
    {
        return $this->gyldigheder;
    }

    public function addGyldigheder(OrganisationFunktionRegistreringGyldighed $gyldigheder): static
    {
        if (!$this->gyldigheder->contains($gyldigheder)) {
            $this->gyldigheder->add($gyldigheder);
            $gyldigheder->setOrganisationFunktionRegistrering($this);
        }

        return $this;
    }

    public function removeGyldigheder(OrganisationFunktionRegistreringGyldighed $gyldigheder): static
    {
        if ($this->gyldigheder->removeElement($gyldigheder)) {
            // set the owning side to null (unless already changed)
            if ($gyldigheder->getOrganisationFunktionRegistrering() === $this) {
                $gyldigheder->setOrganisationFunktionRegistrering(null);
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

    /**
     * @return Collection<int, OrganisationFunktionRegistreringTilknyttedeOrganisationer>
     */
    public function getTilknyttedeOrganisationer(): Collection
    {
        return $this->tilknyttedeOrganisationer;
    }

    public function addTilknyttedeOrganisationer(OrganisationFunktionRegistreringTilknyttedeOrganisationer $tilknyttedeOrganisationer): static
    {
        if (!$this->tilknyttedeOrganisationer->contains($tilknyttedeOrganisationer)) {
            $this->tilknyttedeOrganisationer->add($tilknyttedeOrganisationer);
            $tilknyttedeOrganisationer->setOrganisationFunktionRegistrering($this);
        }

        return $this;
    }

    public function removeTilknyttedeOrganisationer(OrganisationFunktionRegistreringTilknyttedeOrganisationer $tilknyttedeOrganisationer): static
    {
        if ($this->tilknyttedeOrganisationer->removeElement($tilknyttedeOrganisationer)) {
            // set the owning side to null (unless already changed)
            if ($tilknyttedeOrganisationer->getOrganisationFunktionRegistrering() === $this) {
                $tilknyttedeOrganisationer->setOrganisationFunktionRegistrering(null);
            }
        }

        return $this;
    }
}
