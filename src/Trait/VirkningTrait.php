<?php

namespace App\Trait;

use Doctrine\ORM\Mapping as ORM;

trait VirkningTrait
{
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $virkningFraTidsstempelDatoTid = null;

    #[ORM\Column(nullable: true)]
    private ?bool $virkningFraGraenseIndikator = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $virkningTilTidsstempelDatoTid = null;

    #[ORM\Column(nullable: true)]
    private ?bool $virkningTilGraenseIndikator = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $virkningAktoerRefUUIDIdentifikator = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $virkningAktoerRefURNIdentifikator = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $virkningAktoerTypeKode = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $virkningNoteTekst = null;

    public function getVirkningFraTidsstempelDatoTid(): ?string
    {
        return $this->virkningFraTidsstempelDatoTid;
    }

    public function setVirkningFraTidsstempelDatoTid(?string $virkningFraTidsstempelDatoTid): static
    {
        $this->virkningFraTidsstempelDatoTid = $virkningFraTidsstempelDatoTid;

        return $this;
    }

    public function isVirkningFraGraenseIndikator(): ?bool
    {
        return $this->virkningFraGraenseIndikator;
    }

    public function setVirkningFraGraenseIndikator(?bool $virkningFraGraenseIndikator): static
    {
        $this->virkningFraGraenseIndikator = $virkningFraGraenseIndikator;

        return $this;
    }

    public function getVirkningTilTidsstempelDatoTid(): ?string
    {
        return $this->virkningTilTidsstempelDatoTid;
    }

    public function setVirkningTilTidsstempelDatoTid(?string $virkningTilTidsstempelDatoTid): static
    {
        $this->virkningTilTidsstempelDatoTid = $virkningTilTidsstempelDatoTid;

        return $this;
    }

    public function isVirkningTilGraenseIndikator(): ?bool
    {
        return $this->virkningTilGraenseIndikator;
    }

    public function setVirkningTilGraenseIndikator(?bool $virkningTilGraenseIndikator): static
    {
        $this->virkningTilGraenseIndikator = $virkningTilGraenseIndikator;

        return $this;
    }

    public function getVirkningAktoerRefUUIDIdentifikator(): ?string
    {
        return $this->virkningAktoerRefUUIDIdentifikator;
    }

    public function setVirkningAktoerRefUUIDIdentifikator(?string $virkningAktoerRefUUIDIdentifikator): static
    {
        $this->virkningAktoerRefUUIDIdentifikator = $virkningAktoerRefUUIDIdentifikator;

        return $this;
    }

    public function getVirkningAktoerRefURNIdentifikator(): ?string
    {
        return $this->virkningAktoerRefURNIdentifikator;
    }

    public function setVirkningAktoerRefURNIdentifikator(?string $virkningAktoerRefURNIdentifikator): static
    {
        $this->virkningAktoerRefURNIdentifikator = $virkningAktoerRefURNIdentifikator;

        return $this;
    }

    public function getVirkningAktoerTypeKode(): ?string
    {
        return $this->virkningAktoerTypeKode;
    }

    public function setVirkningAktoerTypeKode(?string $virkningAktoerTypeKode): static
    {
        $this->virkningAktoerTypeKode = $virkningAktoerTypeKode;

        return $this;
    }

    public function getVirkningNoteTekst(): ?string
    {
        return $this->virkningNoteTekst;
    }

    public function setVirkningNoteTekst(?string $virkningNoteTekst): static
    {
        $this->virkningNoteTekst = $virkningNoteTekst;

        return $this;
    }
}