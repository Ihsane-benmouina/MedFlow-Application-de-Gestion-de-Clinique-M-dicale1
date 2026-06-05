<?php

namespace App\Repository;

use PDO;


class CreneauRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

   
    public function findByMedecin(int $idMedecin): array
    {
        $sql = "SELECT * FROM creneaux 
                WHERE id_medecin = :id_medecin 
                ORDER BY heure_debut ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id_medecin' => $idMedecin]);
        return $stmt->fetchAll();
    }

 
    public function findDisponiblesByMedecin(int $idMedecin): array
    {
        $sql = "SELECT * FROM creneaux 
                WHERE id_medecin = :id_medecin 
                AND disponible = TRUE
                AND heure_debut >= NOW()
                ORDER BY heure_debut ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id_medecin' => $idMedecin]);
        return $stmt->fetchAll();
    }

   
}
