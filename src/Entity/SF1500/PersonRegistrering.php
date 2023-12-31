<?php

namespace App\Entity\SF1500;

use App\Repository\SF1500\PersonRegistreringRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV4;

#[ORM\Entity(repositoryClass: PersonRegistreringRepository::class)]
#[ORM\Index(columns: ['person_id'], name: 'person_id_idx')]
class PersonRegistrering
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    private UuidV4 $id;

    #[ORM\Column(length: 255)]
    private string $personId;

    #[ORM\OneToMany(mappedBy: 'personRegistrering', targetEntity: PersonRegistreringEgenskab::class, orphanRemoval: true)]
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

    public function getPersonId(): string
    {
        return $this->personId;
    }

    public function setPersonId(string $personId): static
    {
        $this->personId = $personId;

        return $this;
    }

    /**
     * @return Collection<int, PersonRegistreringEgenskab>
     */
    public function getEgenskaber(): Collection
    {
        return $this->egenskaber;
    }

    public function addEgenskaber(PersonRegistreringEgenskab $egenskaber): static
    {
        if (!$this->egenskaber->contains($egenskaber)) {
            $this->egenskaber->add($egenskaber);
            $egenskaber->setPersonRegistrering($this);
        }

        return $this;
    }

    public function removeEgenskaber(PersonRegistreringEgenskab $egenskaber): static
    {
        if ($this->egenskaber->removeElement($egenskaber)) {
            // set the owning side to null (unless already changed)
            if ($egenskaber->getPersonRegistrering() === $this) {
                $egenskaber->setPersonRegistrering(null);
            }
        }

        return $this;
    }
}
