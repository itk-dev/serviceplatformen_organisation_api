<?php

namespace App\Entity;

use App\Repository\OrganisationEnhedRegistreringTilhoererRepository;
use App\Trait\ReferenceIdTrait;
use App\Trait\VirkningTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV4;

#[ORM\Entity(repositoryClass: OrganisationEnhedRegistreringTilhoererRepository::class)]
class OrganisationEnhedRegistreringTilhoerer
{
    use VirkningTrait;
    use ReferenceIdTrait;

    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    private UuidV4 $id;

    public function __construct()
    {
        $this->id = Uuid::v4();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }
}
