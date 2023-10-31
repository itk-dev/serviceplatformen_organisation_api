<?php

namespace App\Trait;

use Doctrine\ORM\Mapping as ORM;

trait BrugerRefTrait
{
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $brugerRefUUIDIdentifikator = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $brugerRefURNIdentifikator = null;

    public function getBrugerRefUUIDIdentifikator(): ?string
    {
        return $this->brugerRefUUIDIdentifikator;
    }

    public function setBrugerRefUUIDIdentifikator(?string $brugerRefUUIDIdentifikator): static
    {
        $this->brugerRefUUIDIdentifikator = $brugerRefUUIDIdentifikator;

        return $this;
    }

    public function getBrugerRefURNIdentifikator(): ?string
    {
        return $this->brugerRefURNIdentifikator;
    }

    public function setBrugerRefURNIdentifikator(?string $brugerRefURNIdentifikator): static
    {
        $this->brugerRefURNIdentifikator = $brugerRefURNIdentifikator;

        return $this;
    }
}