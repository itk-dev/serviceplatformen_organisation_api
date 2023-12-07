<?php

namespace App\Trait;

use Doctrine\ORM\Mapping as ORM;

trait RolleTrait
{
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $rolleLabel = null;

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
