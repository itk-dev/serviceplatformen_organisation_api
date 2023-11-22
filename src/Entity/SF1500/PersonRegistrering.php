<?php

namespace App\Entity\SF1500;

use App\Repository\SF1500\PersonRegistreringRepository;
use App\Trait\BrugerRefTrait;
use App\Trait\RegistreringTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV4;

#[ORM\Entity(repositoryClass: PersonRegistreringRepository::class)]
class PersonRegistrering
{
    use RegistreringTrait;
    use BrugerRefTrait;

    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    private UuidV4 $id;

    #[ORM\ManyToOne(inversedBy: 'registreringer')]
    #[ORM\JoinColumn(nullable: false)]
    private Person $person;

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

    public function getPerson(): Person
    {
        return $this->person;
    }

    public function setPerson(?Person $person): static
    {
        $this->person = $person;

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
