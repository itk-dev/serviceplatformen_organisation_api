<?php

namespace App\Trait;

use Doctrine\ORM\Mapping as ORM;

trait BrugervendtNoegleTekstTrait
{
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $brugervendtNoegleTekst = null;

    public function getBrugervendtNoegleTekst(): ?string
    {
        return $this->brugervendtNoegleTekst;
    }

    public function setBrugervendtNoegleTekst(?string $brugervendtNoegleTekst): static
    {
        $this->brugervendtNoegleTekst = $brugervendtNoegleTekst;

        return $this;
    }
}
