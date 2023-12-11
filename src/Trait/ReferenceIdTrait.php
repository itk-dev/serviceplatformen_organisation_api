<?php

namespace App\Trait;

use Doctrine\ORM\Mapping as ORM;

trait ReferenceIdTrait
{
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $referenceIdUUIDIdentifikator = null;

    public function getReferenceIdUUIDIdentifikator(): ?string
    {
        return $this->referenceIdUUIDIdentifikator;
    }

    public function setReferenceIdUUIDIdentifikator(?string $referenceIdUUIDIdentifikator): static
    {
        $this->referenceIdUUIDIdentifikator = $referenceIdUUIDIdentifikator;

        return $this;
    }
}
