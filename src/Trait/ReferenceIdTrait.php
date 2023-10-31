<?php

namespace App\Trait;

use Doctrine\ORM\Mapping as ORM;

trait ReferenceIdTrait
{
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $referenceIdUUIDIdentifikator = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $referenceIdURNIdentifikator = null;

    public function getReferenceIdUUIDIdentifikator(): ?string
    {
        return $this->referenceIdUUIDIdentifikator;
    }

    public function setReferenceIdUUIDIdentifikator(?string $referenceIdUUIDIdentifikator): static
    {
        $this->referenceIdUUIDIdentifikator = $referenceIdUUIDIdentifikator;

        return $this;
    }

    public function getReferenceIdURNIdentifikator(): ?string
    {
        return $this->referenceIdURNIdentifikator;
    }

    public function setReferenceIdURNIdentifikator(?string $referenceIdURNIdentifikator): static
    {
        $this->referenceIdURNIdentifikator = $referenceIdURNIdentifikator;

        return $this;
    }
}