<?php

namespace App\Entity\Model;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\Model\BrugerRepository;
use App\State\BrugerFunktionerProvider;
use App\State\BrugerLederProvider;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: BrugerRepository::class, readOnly: true)]
#[ORM\Table(name: 'bruger')]
#[ApiResource(
    operations: [
        new Get(
            uriTemplate: 'bruger/{id}',
            routePrefix: 'v1/',
            shortName: 'Bruger',
            normalizationContext: ['groups' => 'bruger:item']
        ),
        new GetCollection(
            uriTemplate: 'bruger',
            routePrefix: 'v1/',
            shortName: 'Bruger',
            normalizationContext: ['groups' => 'bruger:item'],
        ),
        new GetCollection(
            uriTemplate: 'bruger/{id}/funktioner',
            routePrefix: 'v1/',
            shortName: 'Bruger',
            normalizationContext: ['groups' => ['funktion:item']],
            provider: BrugerFunktionerProvider::class,
        ),
        new Get(
            uriTemplate: 'bruger/{id}/leder',
            routePrefix: 'v1/',
            shortName: 'Bruger',
            normalizationContext: ['groups' => 'bruger:item'],
            provider: BrugerLederProvider::class,
        ),
    ],
)]
#[ApiFilter(SearchFilter::class, properties: [
    'navn' => 'partial',
    'az' => 'exact',
    'email' => 'exact',
    'telefon' => 'exact',
    'lokation' => 'exact',
])]
class Bruger
{
    #[ORM\Id]
    #[ORM\Column]
    #[Groups(['bruger:item'])]
    private string $id;

    #[ORM\Column(length: 255)]
    #[Groups(['bruger:item'])]
    private string $navn;

    #[ORM\Column(length: 255)]
    #[Groups(['bruger:item'])]
    private string $az;

    #[ORM\Column(length: 255)]
    #[Groups(['bruger:item'])]
    private string $email;

    #[ORM\Column(length: 255)]
    #[Groups(['bruger:item'])]
    private string $telefon;

    #[ORM\Column(length: 255)]
    #[Groups(['bruger:item'])]
    private string $lokation;

    private function __construct()
    {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getNavn(): string
    {
        return $this->navn;
    }

    public function getAz(): string
    {
        return $this->az;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getTelefon(): string
    {
        return $this->telefon;
    }

    public function getLokation(): string
    {
        return $this->lokation;
    }
}
