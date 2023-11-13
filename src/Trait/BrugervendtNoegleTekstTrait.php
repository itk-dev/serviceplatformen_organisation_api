<?php

namespace App\Trait;

use Doctrine\ORM\Mapping as ORM;

trait BrugervendtNoegleTekstTrait
{
    #[ORM\Column(length: 255)]
    private string $brugervendtNoegleTekst;

    public function getBrugervendtNoegleTekst(): string
    {
        return $this->brugervendtNoegleTekst;
    }

    public function setBrugervendtNoegleTekst(string $brugervendtNoegleTekst): static
    {
        $this->brugervendtNoegleTekst = $brugervendtNoegleTekst;

        return $this;
    }
}
