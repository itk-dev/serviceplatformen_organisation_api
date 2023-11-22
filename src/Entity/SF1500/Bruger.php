<?php

namespace App\Entity\SF1500;

use App\Repository\SF1500\BrugerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BrugerRepository::class)]
class Bruger
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    #[ORM\Column]
    private string $id;

    #[ORM\OneToMany(mappedBy: 'bruger', targetEntity: BrugerRegistrering::class, orphanRemoval: true)]
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
     * @return Collection<int, BrugerRegistrering>
     */
    public function getRegistreringer(): Collection
    {
        return $this->registreringer;
    }

    public function addRegistreringer(BrugerRegistrering $registreringer): static
    {
        if (!$this->registreringer->contains($registreringer)) {
            $this->registreringer->add($registreringer);
            $registreringer->setBruger($this);
        }

        return $this;
    }

    public function removeRegistreringer(BrugerRegistrering $registreringer): static
    {
        if ($this->registreringer->removeElement($registreringer)) {
            // set the owning side to null (unless already changed)
            if ($registreringer->getBruger() === $this) {
                $registreringer->setBruger(null);
            }
        }

        return $this;
    }
}
