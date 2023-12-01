<?php

namespace App\Trait;

use Doctrine\ORM\Mapping as ORM;

trait IndeksTrait
{
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $indeks = null;

    public function getIndeks(): ?string
    {
        return $this->indeks;
    }

    public function setIndeks(?string $indeks): static
    {
        $this->indeks = $indeks;

        return $this;
    }
}
