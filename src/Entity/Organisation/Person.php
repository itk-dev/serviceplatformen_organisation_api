<?php

namespace App\Entity\Organisation;

use App\Repository\PersonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PersonRepository::class)]
class Person
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    #[ORM\Column]
    private string $id;

    #[ORM\OneToMany(mappedBy: 'person', targetEntity: PersonRegistrering::class, orphanRemoval: true)]
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
     * @return Collection<int, PersonRegistrering>
     */
    public function getRegistreringer(): Collection
    {
        return $this->registreringer;
    }

    public function addRegistreringer(PersonRegistrering $registreringer): static
    {
        if (!$this->registreringer->contains($registreringer)) {
            $this->registreringer->add($registreringer);
            $registreringer->setPerson($this);
        }

        return $this;
    }

    public function removeRegistreringer(PersonRegistrering $registreringer): static
    {
        if ($this->registreringer->removeElement($registreringer)) {
            // set the owning side to null (unless already changed)
            if ($registreringer->getPerson() === $this) {
                $registreringer->setPerson(null);
            }
        }

        return $this;
    }
}
