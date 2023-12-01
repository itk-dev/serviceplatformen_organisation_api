<?php

namespace App\Trait;

use Doctrine\ORM\Mapping as ORM;

trait RolleTrait
{
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $rolleUUIDIdentifikator = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $rolleURNIdentifikator = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $rolleLabel = null;

    public function getRolleUUIDIdentifikator(): ?string
    {
        return $this->rolleUUIDIdentifikator;
    }

    public function setRolleUUIDIdentifikator(?string $rolleUUIDIdentifikator): static
    {
        $this->rolleUUIDIdentifikator = $rolleUUIDIdentifikator;

        return $this;
    }

    public function getRolleURNIdentifikator(): ?string
    {
        return $this->rolleURNIdentifikator;
    }

    public function setRolleURNIdentifikator(?string $rolleURNIdentifikator): static
    {
        $this->rolleURNIdentifikator = $rolleURNIdentifikator;

        return $this;
    }

    public function getRolleLabel(): ?string
    {
        return $this->rolleLabel;
    }

    public function setRolleLabel(?string $rolleLabel): static
    {
        $this->rolleLabel = $rolleLabel;

        return $this;
    }
}
