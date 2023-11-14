<?php

namespace App\Entity\Views;

use App\Repository\FunktionsDataRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FunktionsDataRepository::class, readOnly: true)]
#[ORM\Table(name: 'funktions_data')]
class FunktionsData
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    #[ORM\Column]
    private string $id;

    #[ORM\Column(length: 255)]
    private string $funktionsnavn;

    #[ORM\Column(length: 255)]
    private string $enhedsnavn;

    #[ORM\Column(length: 255)]
    private string $adresse;

    private function __construct()
    {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getFunktionsnavn(): string
    {
        return $this->funktionsnavn;
    }

    public function getEnhedsnavn(): string
    {
        return $this->enhedsnavn;
    }

    public function getAdresse(): string
    {
        return $this->adresse;
    }
}
