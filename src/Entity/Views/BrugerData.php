<?php

namespace App\Entity\Views;

use App\Repository\BrugerDataRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BrugerDataRepository::class, readOnly: true)]
#[ORM\Table(name: 'bruger_data')]
class BrugerData
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    #[ORM\Column]
    private string $id;

    #[ORM\Column(length: 255)]
    private string $navn;

    #[ORM\Column(length: 255)]
    private string $az;

    #[ORM\Column(length: 255)]
    private string $email;

    #[ORM\Column(length: 255)]
    private string $telefon;

    #[ORM\Column(length: 255)]
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
