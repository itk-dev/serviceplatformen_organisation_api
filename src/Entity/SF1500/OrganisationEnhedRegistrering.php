<?php

namespace App\Entity\SF1500;

use App\Repository\SF1500\OrganisationEnhedRegistreringRepository;
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

    #[ORM\Column(length: 255)]
    private string $organisationEnhedId;

    #[ORM\OneToMany(mappedBy: 'organisationEnhedRegistrering', targetEntity: OrganisationEnhedRegistreringEgenskab::class, orphanRemoval: true)]
    private Collection $egenskaber;

    #[ORM\OneToMany(mappedBy: 'organisationEnhedRegistrering', targetEntity: OrganisationEnhedRegistreringAdresser::class, orphanRemoval: true)]
    private Collection $adresser;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?OrganisationEnhedRegistreringOverordnet $overordnet = null;

    public function __construct()
    {
        $this->id = Uuid::v4();
        $this->egenskaber = new ArrayCollection();
        $this->adresser = new ArrayCollection();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getOrganisationEnhedId(): string
    {
        return $this->organisationEnhedId;
    }

    public function setOrganisationEnhedId(string $organisationEnhedId): static
    {
        $this->organisationEnhedId = $organisationEnhedId;

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

    public function getOverordnet(): ?OrganisationEnhedRegistreringOverordnet
    {
        return $this->overordnet;
    }

    public function setOverordnet(?OrganisationEnhedRegistreringOverordnet $overordnet): static
    {
        $this->overordnet = $overordnet;

        return $this;
    }
}
