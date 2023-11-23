<?php

namespace App\Entity\SF1500;

use App\Repository\SF1500\AdresseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AdresseRepository::class)]
class Adresse
{
    #[ORM\Id]
    #[ORM\Column]
    private string $id;

    #[ORM\OneToMany(mappedBy: 'adresse', targetEntity: AdresseRegistrering::class, orphanRemoval: true)]
    private Collection $registreringer;

    public function __construct()
    {
        $this->registreringer = new ArrayCollection();
    }

    public function setId(string $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return Collection<int, AdresseRegistrering>
     */
    public function getRegistreringer(): Collection
    {
        return $this->registreringer;
    }

    public function addRegistreringer(AdresseRegistrering $registreringer): static
    {
        if (!$this->registreringer->contains($registreringer)) {
            $this->registreringer->add($registreringer);
            $registreringer->setAdresse($this);
        }

        return $this;
    }

    public function removeRegistreringer(AdresseRegistrering $registreringer): static
    {
        if ($this->registreringer->removeElement($registreringer)) {
            // set the owning side to null (unless already changed)
            if ($registreringer->getAdresse() === $this) {
                $registreringer->setAdresse(null);
            }
        }

        return $this;
    }
}
