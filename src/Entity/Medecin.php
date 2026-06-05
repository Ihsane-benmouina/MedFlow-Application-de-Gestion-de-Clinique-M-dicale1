<?php

namespace App\Entity;


class Medecin extends Utilisateur
{
    private ?int $idMedecin;
    private string $matricule;
    private string $telephone;
    private bool $actif;
    private ?int $idSpecialite;

    public function __construct(
        ?int $id = null,
        string $nom = '',
        string $prenom = '',
        string $email = '',
        string $password = '',
        ?int $idMedecin = null,
        string $matricule = '',
        string $telephone = '',
        bool $actif = true,
        ?int $idSpecialite = null
    ) {
        parent::__construct($id, $nom, $prenom, $email, $password, 'medecin');
        $this->idMedecin = $idMedecin;
        $this->matricule = $matricule;
        $this->telephone = $telephone;
        $this->actif = $actif;
        $this->idSpecialite = $idSpecialite;
    }


    public function getIdMedecin(): ?int
    {
        return $this->idMedecin;
    }

    public function setIdMedecin(int $idMedecin): void
    {
        $this->idMedecin = $idMedecin;
    }

    public function getMatricule(): string
    {
        return $this->matricule;
    }

    public function setMatricule(string $matricule): void
    {
        $this->matricule = $matricule;
    }

    public function getTelephone(): string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): void
    {
        $this->telephone = $telephone;
    }

    public function isActif(): bool
    {
        return $this->actif;
    }

    public function setActif(bool $actif): void
    {
        $this->actif = $actif;
    }

    public function getIdSpecialite(): ?int
    {
        return $this->idSpecialite;
    }

    public function setIdSpecialite(int $idSpecialite): void
    {
        $this->idSpecialite = $idSpecialite;
    }

 
    public function visualiserPlanning(): void
    {
    }

    public function validerRendezVous(): void
    {
    }

    public function annulerRendezVous(): void
    {
    }

   
    public function terminerConsultation(): void
    {
    }
}
