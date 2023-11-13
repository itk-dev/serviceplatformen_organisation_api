<?php

namespace App\Entity;

use App\Repository\BrugerRegistreringAdresseRepository;
use App\Trait\IndeksTrait;
use App\Trait\ReferenceIdTrait;
use App\Trait\RolleTrait;
use App\Trait\TypeTrait;
use App\Trait\VirkningTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV4;

#[ORM\Entity(repositoryClass: BrugerRegistreringAdresseRepository::class)]
class BrugerRegistreringAdresse
{
    use VirkningTrait;
    use ReferenceIdTrait;
    use RolleTrait;
    use TypeTrait;
    use IndeksTrait;

    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    private UuidV4 $id;

    #[ORM\ManyToOne(inversedBy: 'adresser')]
    #[ORM\JoinColumn(nullable: false)]
    private ?BrugerRegistrering $brugerRegistrering = null;

    public function __construct()
    {
        $this->id = Uuid::v4();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getBrugerRegistrering(): ?BrugerRegistrering
    {
        return $this->brugerRegistrering;
    }

    public function setBrugerRegistrering(?BrugerRegistrering $brugerRegistrering): static
    {
        $this->brugerRegistrering = $brugerRegistrering;

        return $this;
    }
}
