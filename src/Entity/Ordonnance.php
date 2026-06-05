<?php

namespace App\Entity;


class Ordonnance
{
    private ?int $id;
    private string $contenu;
    private string $dateCreation;
    private ?int $idRendezVous;

    
    public function __construct(
        ?int $id = null,
        string $contenu = '',
        string $dateCreation = '',
        ?int $idRendezVous = null
    ) {
        $this->id = $id;
        $this->contenu = $contenu;
        $this->dateCreation = $dateCreation;
        $this->idRendezVous = $idRendezVous;
    }

   

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContenu(): string
    {
        return $this->contenu;
    }

    public function getDateCreation(): string
    {
        return $this->dateCreation;
    }

    public function getIdRendezVous(): ?int
    {
        return $this->idRendezVous;
    }


    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setContenu(string $contenu): void
    {
        $this->contenu = $contenu;
    }

    public function setIdRendezVous(int $idRendezVous): void
    {
        $this->idRendezVous = $idRendezVous;
    }


   
    public function modifierContenu(string $nouveauContenu): void
    {
        $this->contenu = $nouveauContenu;
    }
}
