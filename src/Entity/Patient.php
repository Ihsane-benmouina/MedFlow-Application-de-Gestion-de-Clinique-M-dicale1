<?php

namespace App\Entity;

class Patient extends Utilisateur
{
    private string $numeroDePatient;
    private string $dateNaissance;

   
    public function __construct(
        ?int $id = null,
        string $nom = '',
        string $prenom = '',
        string $email = '',
        string $password = '',
        string $numeroDePatient = '',
        string $dateNaissance = ''
    ) {
        parent::__construct($id, $nom, $prenom, $email, $password, 'patient');
        $this->numeroDePatient = $numeroDePatient;
        $this->dateNaissance = $dateNaissance;
    }


    public function getNumeroDePatient(): string
    {
        return $this->numeroDePatient;
    }

    public function setNumeroDePatient(string $numero): void
    {
        $this->numeroDePatient = $numero;
    }

    public function getDateNaissance(): string
    {
        return $this->dateNaissance;
    }

    public function setDateNaissance(string $date): void
    {
        $this->dateNaissance = $date;
    }


   
    public function rechercherMedecin(): void
    {
    }

  
    public function reserverRendezVous(): void
    {
    }

   
    public function consulterTableauDeBord(): void
    {
    }

    
    public function telechargerOrdonnance(): void
    {
    }
}
