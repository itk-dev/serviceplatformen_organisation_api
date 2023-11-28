<?php

namespace App\Entity\Model;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use App\Repository\Model\OrganisationRepository;
use App\State\OrganisationPathProvider;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: OrganisationRepository::class)]
#[ORM\Table(name: 'organisation')]
#[ApiResource(
    operations: [
        new Get(
            uriTemplate: 'organisation/{id}',
            routePrefix: 'v1/',
            shortName: 'Organisation',
            normalizationContext: ['groups' => 'organisation:item']
        ),
        new Get(
            uriTemplate: 'organisation/{id}/path',
            routePrefix: 'v1/',
            shortName: 'Organisation',
            normalizationContext: ['groups' => 'organisation:item'],
            provider: OrganisationPathProvider::class
        ),
    ],
    paginationEnabled: false,
)]
class Organisation
{
    #[ORM\Id]
    #[ORM\Column]
    #[Groups(['organisation:item'])]
    private string $id;

    #[ORM\Column(length: 255)]
    #[Groups(['organisation:item'])]
    private string $enhedsnavn;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['organisation:item'])]
    private ?string $overordnetId;

    // Private constructor to ensure entity is read-only.
    private function __construct()
    {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getEnhedsnavn(): string
    {
        return $this->enhedsnavn;
    }

    public function getOverordnetId(): ?string
    {
        return $this->overordnetId;
    }
}
