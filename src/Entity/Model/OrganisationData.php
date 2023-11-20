<?php

namespace App\Entity\Model;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use App\Repository\OrganisationDataRepository;
use App\State\OrganisationTreeProvider;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: OrganisationDataRepository::class)]
#[ORM\Table(name: 'organisation_data')]
#[ApiResource(
    operations: [
        new Get(
            uriTemplate: 'organisation/{id}',
            routePrefix: 'v1/',
            shortName: 'Organisation',
            normalizationContext: ['groups' => 'organisation:item']
        ),
        new Get(
            uriTemplate: 'organisation/{id}/tree',
            routePrefix: 'v1/',
            shortName: 'Organisation',
            normalizationContext: ['groups' => 'organisation:item'],
            provider: OrganisationTreeProvider::class
        ),
    ],
    paginationEnabled: false,
)]
class OrganisationData
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    #[ORM\Column]
    #[Groups(['organisation:item'])]
    private string $id;

    #[ORM\Column(length: 255)]
    #[Groups(['organisation:item'])]
    private string $enhedsnavn;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['organisation:item'])]
    private ?string $overordnetId;

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
