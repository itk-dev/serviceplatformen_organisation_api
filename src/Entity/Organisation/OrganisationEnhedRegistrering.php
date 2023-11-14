<?php

namespace App\Entity\Organisation;

use App\Repository\OrganisationEnhedRegistreringRepository;
use App\Trait\BrugerRefTrait;
use App\Trait\RegistreringTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV4;

#[ORM\Entity(repositoryClass: OrganisationEnhedRegistreringRepository::class)]
class OrganisationEnhedRegistrering
{
    use RegistreringTrait;
    use BrugerRefTrait;

    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    private UuidV4 $id;

    #[ORM\ManyToOne(inversedBy: 'registreringer')]
    #[ORM\JoinColumn(nullable: false)]
    private ?OrganisationEnhed $organisationEnhed = null;

    #[ORM\OneToMany(mappedBy: 'organisationEnhedRegistrering', targetEntity: OrganisationEnhedRegistreringEgenskab::class, orphanRemoval: true)]
    private Collection $egenskaber;

    #[ORM\OneToMany(mappedBy: 'organisationEnhedRegistrering', targetEntity: OrganisationEnhedRegistreringGyldighed::class, orphanRemoval: true)]
    private Collection $gyldigheder;

    #[ORM\OneToMany(mappedBy: 'organisationEnhedRegistrering', targetEntity: OrganisationEnhedRegistreringAdresser::class, orphanRemoval: true)]
    private Collection $adresser;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?OrganisationEnhedRegistreringEnhedstype $enhedstype = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?OrganisationEnhedRegistreringOverordnet $overordnet = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?OrganisationEnhedRegistreringTilhoerer $tilhoerer = null;

    #[ORM\OneToMany(mappedBy: 'organisationEnhedRegistrering', targetEntity: OrganisationEnhedRegistreringOpgave::class, orphanRemoval: true)]
    private Collection $opgaver;

    public function __construct()
    {
        $this->id = Uuid::v4();
        $this->egenskaber = new ArrayCollection();
        $this->gyldigheder = new ArrayCollection();
        $this->adresser = new ArrayCollection();
        $this->opgaver = new ArrayCollection();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getOrganisationEnhed(): ?OrganisationEnhed
    {
        return $this->organisationEnhed;
    }

    public function setOrganisationEnhed(?OrganisationEnhed $organisationEnhed): static
    {
        $this->organisationEnhed = $organisationEnhed;

        return $this;
    }

    /**
     * @return Collection<int, OrganisationEnhedRegistreringEgenskab>
     */
    public function getEgenskaber(): Collection
    {
        return $this->egenskaber;
    }

    public function addEgenskaber(OrganisationEnhedRegistreringEgenskab $egenskaber): static
    {
        if (!$this->egenskaber->contains($egenskaber)) {
            $this->egenskaber->add($egenskaber);
            $egenskaber->setOrganisationEnhedRegistrering($this);
        }

        return $this;
    }

    public function removeEgenskaber(OrganisationEnhedRegistreringEgenskab $egenskaber): static
    {
        if ($this->egenskaber->removeElement($egenskaber)) {
            // set the owning side to null (unless already changed)
            if ($egenskaber->getOrganisationEnhedRegistrering() === $this) {
                $egenskaber->setOrganisationEnhedRegistrering(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, OrganisationEnhedRegistreringGyldighed>
     */
    public function getGyldigheder(): Collection
    {
        return $this->gyldigheder;
    }

    public function addGyldigheder(OrganisationEnhedRegistreringGyldighed $gyldigheder): static
    {
        if (!$this->gyldigheder->contains($gyldigheder)) {
            $this->gyldigheder->add($gyldigheder);
            $gyldigheder->setOrganisationEnhedRegistrering($this);
        }

        return $this;
    }

    public function removeGyldigheder(OrganisationEnhedRegistreringGyldighed $gyldigheder): static
    {
        if ($this->gyldigheder->removeElement($gyldigheder)) {
            // set the owning side to null (unless already changed)
            if ($gyldigheder->getOrganisationEnhedRegistrering() === $this) {
                $gyldigheder->setOrganisationEnhedRegistrering(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, OrganisationEnhedRegistreringAdresser>
     */
    public function getAdresser(): Collection
    {
        return $this->adresser;
    }

    public function addAdresser(OrganisationEnhedRegistreringAdresser $adresser): static
    {
        if (!$this->adresser->contains($adresser)) {
            $this->adresser->add($adresser);
            $adresser->setOrganisationEnhedRegistrering($this);
        }

        return $this;
    }

    public function removeAdresser(OrganisationEnhedRegistreringAdresser $adresser): static
    {
        if ($this->adresser->removeElement($adresser)) {
            // set the owning side to null (unless already changed)
            if ($adresser->getOrganisationEnhedRegistrering() === $this) {
                $adresser->setOrganisationEnhedRegistrering(null);
            }
        }

        return $this;
    }

    public function getEnhedstype(): ?OrganisationEnhedRegistreringEnhedstype
    {
        return $this->enhedstype;
    }

    public function setEnhedstype(?OrganisationEnhedRegistreringEnhedstype $enhedstype): static
    {
        $this->enhedstype = $enhedstype;

        return $this;
    }

    public function getOverordnet(): ?OrganisationEnhedRegistreringOverordnet
    {
        return $this->overordnet;
    }

    public function setOverordnet(?OrganisationEnhedRegistreringOverordnet $overordnet): static
    {
        $this->overordnet = $overordnet;

        return $this;
    }

    public function getTilhoerer(): ?OrganisationEnhedRegistreringTilhoerer
    {
        return $this->tilhoerer;
    }

    public function setTilhoerer(?OrganisationEnhedRegistreringTilhoerer $tilhoerer): static
    {
        $this->tilhoerer = $tilhoerer;

        return $this;
    }

    /**
     * @return Collection<int, OrganisationEnhedRegistreringOpgave>
     */
    public function getOpgaver(): Collection
    {
        return $this->opgaver;
    }

    public function addOpgaver(OrganisationEnhedRegistreringOpgave $opgaver): static
    {
        if (!$this->opgaver->contains($opgaver)) {
            $this->opgaver->add($opgaver);
            $opgaver->setOrganisationEnhedRegistrering($this);
        }

        return $this;
    }

    public function removeOpgaver(OrganisationEnhedRegistreringOpgave $opgaver): static
    {
        if ($this->opgaver->removeElement($opgaver)) {
            // set the owning side to null (unless already changed)
            if ($opgaver->getOrganisationEnhedRegistrering() === $this) {
                $opgaver->setOrganisationEnhedRegistrering(null);
            }
        }

        return $this;
    }
}
