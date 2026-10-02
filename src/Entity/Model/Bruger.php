<?php

namespace App\Entity\Model;

use ApiPlatform\Doctrine\Orm\Filter\ExactFilter;
use ApiPlatform\Doctrine\Orm\Filter\PartialSearchFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\QueryParameter;
use App\Repository\Model\BrugerRepository;
use App\State\BrugerFunktionerProvider;
use App\State\BrugerLederFunktionerProvider;
use App\State\BrugerLederProvider;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

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
            parameters: [
                'navn' => new QueryParameter(property: 'navn', filter: new PartialSearchFilter()),
                'az' => new QueryParameter(property: 'az', filter: new ExactFilter()),
                'email' => new QueryParameter(property: 'email', filter: new ExactFilter()),
                'telefon' => new QueryParameter(property: 'telefon', filter: new ExactFilter()),
                'lokation' => new QueryParameter(property: 'lokation', filter: new ExactFilter()),
            ],
        ),
        new GetCollection(
            uriTemplate: 'bruger/{id}/funktioner',
            routePrefix: 'v1/',
            shortName: 'Bruger',
            normalizationContext: ['groups' => ['funktion:item']],
            provider: BrugerFunktionerProvider::class,
        ),
        new GetCollection(
            uriTemplate: 'bruger/{id}/leder-funktioner',
            routePrefix: 'v1/',
            shortName: 'Bruger',
            normalizationContext: ['groups' => ['funktion:item']],
            provider: BrugerLederFunktionerProvider::class,
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

    // Private constructor to ensure entity is read-only.
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
