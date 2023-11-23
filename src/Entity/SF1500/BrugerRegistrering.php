<?php

namespace App\Entity\SF1500;

use App\Repository\SF1500\BrugerRegistreringRepository;
use App\Trait\BrugerRefTrait;
use App\Trait\RegistreringTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV4;

#[ORM\Entity(repositoryClass: BrugerRegistreringRepository::class)]
class BrugerRegistrering
{
    use RegistreringTrait;
    use BrugerRefTrait;

    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    private UuidV4 $id;

    #[ORM\Column(length: 255)]
    private string $brugerId;

    #[ORM\OneToMany(mappedBy: 'brugerRegistrering', targetEntity: BrugerRegistreringEgenskab::class, orphanRemoval: true)]
    private Collection $egenskaber;

    #[ORM\OneToMany(mappedBy: 'brugerRegistrering', targetEntity: BrugerRegistreringGyldighed::class, orphanRemoval: true)]
    private Collection $gyldigheder;

    #[ORM\OneToMany(mappedBy: 'brugerRegistrering', targetEntity: BrugerRegistreringAdresse::class, orphanRemoval: true)]
    private Collection $adresser;

    #[ORM\OneToMany(mappedBy: 'brugerRegistrering', targetEntity: BrugerRegistreringTilhoerer::class, orphanRemoval: true)]
    private Collection $tilhoerer;

    #[ORM\OneToMany(mappedBy: 'brugerRegistrering', targetEntity: BrugerRegistreringTilknyttedePersoner::class, orphanRemoval: true)]
    private Collection $tilknyttedePersoner;

    public function __construct()
    {
        $this->id = Uuid::v4();
        $this->egenskaber = new ArrayCollection();
        $this->gyldigheder = new ArrayCollection();
        $this->adresser = new ArrayCollection();
        $this->tilhoerer = new ArrayCollection();
        $this->tilknyttedePersoner = new ArrayCollection();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getBrugerId(): string
    {
        return $this->brugerId;
    }

    public function setBrugerId(string $brugerId): static
    {
        $this->brugerId = $brugerId;

        return $this;
    }

    /**
     * @return Collection<int, BrugerRegistreringEgenskab>
     */
    public function getEgenskaber(): Collection
    {
        return $this->egenskaber;
    }

    public function addEgenskaber(BrugerRegistreringEgenskab $egenskaber): static
    {
        if (!$this->egenskaber->contains($egenskaber)) {
            $this->egenskaber->add($egenskaber);
            $egenskaber->setBrugerRegistrering($this);
        }

        return $this;
    }

    public function removeEgenskaber(BrugerRegistreringEgenskab $egenskaber): static
    {
        if ($this->egenskaber->removeElement($egenskaber)) {
            // set the owning side to null (unless already changed)
            if ($egenskaber->getBrugerRegistrering() === $this) {
                $egenskaber->setBrugerRegistrering(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, BrugerRegistreringGyldighed>
     */
    public function getGyldigheder(): Collection
    {
        return $this->gyldigheder;
    }

    public function addGyldigheder(BrugerRegistreringGyldighed $gyldigheder): static
    {
        if (!$this->gyldigheder->contains($gyldigheder)) {
            $this->gyldigheder->add($gyldigheder);
            $gyldigheder->setBrugerRegistrering($this);
        }

        return $this;
    }

    public function removeGyldigheder(BrugerRegistreringGyldighed $gyldigheder): static
    {
        if ($this->gyldigheder->removeElement($gyldigheder)) {
            // set the owning side to null (unless already changed)
            if ($gyldigheder->getBrugerRegistrering() === $this) {
                $gyldigheder->setBrugerRegistrering(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, BrugerRegistreringAdresse>
     */
    public function getAdresser(): Collection
    {
        return $this->adresser;
    }

    public function addAdresser(BrugerRegistreringAdresse $adresser): static
    {
        if (!$this->adresser->contains($adresser)) {
            $this->adresser->add($adresser);
            $adresser->setBrugerRegistrering($this);
        }

        return $this;
    }

    public function removeAdresser(BrugerRegistreringAdresse $adresser): static
    {
        if ($this->adresser->removeElement($adresser)) {
            // set the owning side to null (unless already changed)
            if ($adresser->getBrugerRegistrering() === $this) {
                $adresser->setBrugerRegistrering(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, BrugerRegistreringTilhoerer>
     */
    public function getTilhoerer(): Collection
    {
        return $this->tilhoerer;
    }

    public function addTilhoerer(BrugerRegistreringTilhoerer $tilhoerer): static
    {
        if (!$this->tilhoerer->contains($tilhoerer)) {
            $this->tilhoerer->add($tilhoerer);
            $tilhoerer->setBrugerRegistrering($this);
        }

        return $this;
    }

    public function removeTilhoerer(BrugerRegistreringTilhoerer $tilhoerer): static
    {
        if ($this->tilhoerer->removeElement($tilhoerer)) {
            // set the owning side to null (unless already changed)
            if ($tilhoerer->getBrugerRegistrering() === $this) {
                $tilhoerer->setBrugerRegistrering(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, BrugerRegistreringTilknyttedePersoner>
     */
    public function getTilknyttedePersoner(): Collection
    {
        return $this->tilknyttedePersoner;
    }

    public function addTilknyttedePersoner(BrugerRegistreringTilknyttedePersoner $tilknyttedePersoner): static
    {
        if (!$this->tilknyttedePersoner->contains($tilknyttedePersoner)) {
            $this->tilknyttedePersoner->add($tilknyttedePersoner);
            $tilknyttedePersoner->setBrugerRegistrering($this);
        }

        return $this;
    }

    public function removeTilknyttedePersoner(BrugerRegistreringTilknyttedePersoner $tilknyttedePersoner): static
    {
        if ($this->tilknyttedePersoner->removeElement($tilknyttedePersoner)) {
            // set the owning side to null (unless already changed)
            if ($tilknyttedePersoner->getBrugerRegistrering() === $this) {
                $tilknyttedePersoner->setBrugerRegistrering(null);
            }
        }

        return $this;
    }
}
