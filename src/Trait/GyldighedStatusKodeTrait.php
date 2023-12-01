<?php

namespace App\Trait;

use Doctrine\ORM\Mapping as ORM;

trait GyldighedStatusKodeTrait
{
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $gyldighedStatusKode = null;

    public function getGyldighedStatusKode(): ?string
    {
        return $this->gyldighedStatusKode;
    }

    public function setGyldighedStatusKode(?string $gyldighedStatusKode): static
    {
        $this->gyldighedStatusKode = $gyldighedStatusKode;

        return $this;
    }
}
