<?php

namespace App\Trait;

use Doctrine\ORM\Mapping as ORM;

trait RegistreringTrait
{
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $noteTekst = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $tidspunkt = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $livscyklusKode = null;

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
}
