<?php

namespace App\Entity\Organisation;

use App\Repository\OrganisationFunktionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrganisationFunktionRepository::class)]
class OrganisationFunktion
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    #[ORM\Column]
    private string $id;

    #[ORM\OneToMany(mappedBy: 'organisationFunktion', targetEntity: OrganisationFunktionRegistrering::class, orphanRemoval: true)]
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
     * @return Collection<int, OrganisationFunktionRegistrering>
     */
    public function getRegistreringer(): Collection
    {
        return $this->registreringer;
    }

    public function addRegistreringer(OrganisationFunktionRegistrering $registreringer): static
    {
        if (!$this->registreringer->contains($registreringer)) {
            $this->registreringer->add($registreringer);
            $registreringer->setOrganisationFunktion($this);
        }

        return $this;
    }

    public function removeRegistreringer(OrganisationFunktionRegistrering $registreringer): static
    {
        if ($this->registreringer->removeElement($registreringer)) {
            // set the owning side to null (unless already changed)
            if ($registreringer->getOrganisationFunktion() === $this) {
                $registreringer->setOrganisationFunktion(null);
            }
        }

        return $this;
    }
}
