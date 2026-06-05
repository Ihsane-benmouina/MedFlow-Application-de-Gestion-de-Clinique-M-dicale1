<?php

namespace App\Entity;


class RendezVous
{
  
    private ?int $id;
    private ?int $idPatient;
    private ?int $idMedecin;
    private ?int $idCreneau;
    private string $statut;
    private string $dateCreation;

  
    public function __construct(
        ?int $id = null,
        ?int $idPatient = null,
        ?int $idMedecin = null,
        ?int $idCreneau = null,
        string $statut = 'En attente',
        string $dateCreation = ''
    ) {
        $this->id = $id;
        $this->idPatient = $idPatient;
        $this->idMedecin = $idMedecin;
        $this->idCreneau = $idCreneau;
        $this->statut = $statut;
        $this->dateCreation = $dateCreation;
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdPatient(): ?int
    {
        return $this->idPatient;
    }

    public function getIdMedecin(): ?int
    {
        return $this->idMedecin;
    }

    public function getIdCreneau(): ?int
    {
        return $this->idCreneau;
    }

    public function getStatut(): string
    {
        return $this->statut;
    }

    public function getDateCreation(): string
    {
        return $this->dateCreation;
    }


    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setIdPatient(int $idPatient): void
    {
        $this->idPatient = $idPatient;
    }

    public function setIdMedecin(int $idMedecin): void
    {
        $this->idMedecin = $idMedecin;
    }

    public function setIdCreneau(int $idCreneau): void
    {
        $this->idCreneau = $idCreneau;
    }

    public function setStatut(string $statut): void
    {
        $this->statut = $statut;
    }

    public function confirmer(): void
    {
        $this->statut = 'Confirmé';
    }

    public function annuler(): void
    {
        $this->statut = 'Annulé';
    }

    public function terminer(): void
    {
        $this->statut = 'Terminé';
    }

  
    public function verifierDisponibilite(): bool
    {
        return true;
    }
}
