<?php

namespace App\Entity\SF1500;

use App\Repository\SF1500\AdresseRegistreringRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV4;

#[ORM\Entity(repositoryClass: AdresseRegistreringRepository::class)]
#[ORM\Index(columns: ['adresse_id'], name: 'adresse_id_idx')]
class AdresseRegistrering
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    private UuidV4 $id;

    #[ORM\Column(length: 255)]
    private string $adresseId;

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

    public function getAdresseId(): string
    {
        return $this->adresseId;
    }

    public function setAdresseId(string $adresseId): static
    {
        $this->adresseId = $adresseId;

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
