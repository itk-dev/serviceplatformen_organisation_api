<?php

namespace App\Entity;

use App\Repository\AdresseRegistreringRepository;
use App\Trait\BrugerRefTrait;
use App\Trait\RegistreringTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV4;

#[ORM\Entity(repositoryClass: AdresseRegistreringRepository::class)]
class AdresseRegistrering
{
    use RegistreringTrait;
    use BrugerRefTrait;

    #[ORM\Id]
    #[ORM\Column(type: "uuid", unique: true)]
    private UuidV4 $id;

    #[ORM\ManyToOne(inversedBy: 'registreringer')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Adresse $adresse = null;

    #[ORM\OneToMany(mappedBy: 'adresseRegistrering', targetEntity: AdresseRegistreringEgenskab::class, orphanRemoval: true)]
    private Collection $egenskaber;

    public function __construct()
    {
        $this->id = Uuid::v4();
        $this->egenskaber = new ArrayCollection();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getAdresse(): ?Adresse
    {
        return $this->adresse;
    }

    public function setAdresse(?Adresse $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * @return Collection<int, AdresseRegistreringEgenskab>
     */
    public function getEgenskaber(): Collection
    {
        return $this->egenskaber;
    }

    public function addEgenskaber(AdresseRegistreringEgenskab $egenskaber): static
    {
        if (!$this->egenskaber->contains($egenskaber)) {
            $this->egenskaber->add($egenskaber);
            $egenskaber->setAdresseRegistrering($this);
        }

        return $this;
    }

    public function removeEgenskaber(AdresseRegistreringEgenskab $egenskaber): static
    {
        if ($this->egenskaber->removeElement($egenskaber)) {
            // set the owning side to null (unless already changed)
            if ($egenskaber->getAdresseRegistrering() === $this) {
                $egenskaber->setAdresseRegistrering(null);
            }
        }

        return $this;
    }
}
