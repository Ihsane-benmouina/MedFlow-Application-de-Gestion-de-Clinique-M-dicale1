<?php

namespace App\Entity;


class Creneau
{
    private ?int $id;
    private string $heureDebut;
    private string $heureFin;
    private bool $disponible;
    private ?int $idMedecin;

   
    public function __construct(
        ?int $id = null,
        string $heureDebut = '',
        string $heureFin = '',
        bool $disponible = true,
        ?int $idMedecin = null
    ) {
        $this->id = $id;
        $this->heureDebut = $heureDebut;
        $this->heureFin = $heureFin;
        $this->disponible = $disponible;
        $this->idMedecin = $idMedecin;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHeureDebut(): string
    {
        return $this->heureDebut;
    }

    public function getHeureFin(): string
    {
        return $this->heureFin;
    }

    public function isDisponible(): bool
    {
        return $this->disponible;
    }

    public function getIdMedecin(): ?int
    {
        return $this->idMedecin;
    }


    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setHeureDebut(string $heureDebut): void
    {
        $this->heureDebut = $heureDebut;
    }

    public function setHeureFin(string $heureFin): void
    {
        $this->heureFin = $heureFin;
    }

    public function setDisponible(bool $disponible): void
    {
        $this->disponible = $disponible;
    }

    public function setIdMedecin(int $idMedecin): void
    {
        $this->idMedecin = $idMedecin;
    }


    public function marquerIndisponible(): void
    {
        $this->disponible = false;
    }
}
