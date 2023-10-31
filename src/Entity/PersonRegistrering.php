<?php

namespace App\Entity;

use App\Repository\PersonRegistreringRepository;
use App\Trait\BrugerRefTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV4;

#[ORM\Entity(repositoryClass: PersonRegistreringRepository::class)]
class PersonRegistrering
{
    use BrugerRefTrait;

    #[ORM\Id]
    #[ORM\Column(type: "uuid", unique: true)]
    private UuidV4 $id;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $noteTekst = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $tidspunkt = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $livscyklusKode = null;

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

    public function getNoteTekst(): ?string
    {
        return $this->noteTekst;
    }

    public function setNoteTekst(?string $noteTekst): static
    {
        $this->noteTekst = $noteTekst;

        return $this;
    }

    public function getTidspunkt(): ?string
    {
        return $this->tidspunkt;
    }

    public function setTidspunkt(?string $tidspunkt): static
    {
        $this->tidspunkt = $tidspunkt;

        return $this;
    }

    public function getLivscyklusKode(): ?string
    {
        return $this->livscyklusKode;
    }

    public function setLivscyklusKode(?string $livscyklusKode): static
    {
        $this->livscyklusKode = $livscyklusKode;

        return $this;
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
