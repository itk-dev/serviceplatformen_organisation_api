<?php

namespace App\Entity\SF1500;

use App\Repository\SF1500\OrganisationEnhedRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrganisationEnhedRepository::class)]
class OrganisationEnhed
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    #[ORM\Column]
    private string $id;

    #[ORM\OneToMany(mappedBy: 'organisationEnhed', targetEntity: OrganisationEnhedRegistrering::class, orphanRemoval: true)]
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
     * @return Collection<int, OrganisationEnhedRegistrering>
     */
    public function getRegistreringer(): Collection
    {
        return $this->registreringer;
    }

    public function addRegistreringer(OrganisationEnhedRegistrering $registreringer): static
    {
        if (!$this->registreringer->contains($registreringer)) {
            $this->registreringer->add($registreringer);
            $registreringer->setOrganisationEnhed($this);
        }

        return $this;
    }

    public function removeRegistreringer(OrganisationEnhedRegistrering $registreringer): static
    {
        if ($this->registreringer->removeElement($registreringer)) {
            // set the owning side to null (unless already changed)
            if ($registreringer->getOrganisationEnhed() === $this) {
                $registreringer->setOrganisationEnhed(null);
            }
        }

        return $this;
    }
}
