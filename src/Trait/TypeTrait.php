<?php

namespace App\Trait;

use Doctrine\ORM\Mapping as ORM;

trait TypeTrait
{
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $typeUUIDIdentifikator = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $typeURNIdentifikator = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $typeLabel = null;

    public function getTypeUUIDIdentifikator(): ?string
    {
        return $this->typeUUIDIdentifikator;
    }

    public function setTypeUUIDIdentifikator(?string $typeUUIDIdentifikator): static
    {
        $this->typeUUIDIdentifikator = $typeUUIDIdentifikator;

        return $this;
    }

    public function getTypeURNIdentifikator(): ?string
    {
        return $this->typeURNIdentifikator;
    }

    public function setTypeURNIdentifikator(?string $typeURNIdentifikator): static
    {
        $this->typeURNIdentifikator = $typeURNIdentifikator;

        return $this;
    }

    public function getTypeLabel(): ?string
    {
        return $this->typeLabel;
    }

    public function setTypeLabel(?string $typeLabel): static
    {
        $this->typeLabel = $typeLabel;

        return $this;
    }
}
