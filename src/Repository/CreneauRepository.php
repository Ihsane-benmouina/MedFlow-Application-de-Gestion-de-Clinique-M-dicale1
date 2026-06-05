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

    
    public function findById(int $id): ?array
    {
        $sql = "SELECT * FROM creneaux WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    
    public function create(string $heureDebut, string $heureFin, int $idMedecin): int
    {
        $sql = "INSERT INTO creneaux (heure_debut, heure_fin, disponible, id_medecin) 
                VALUES (:heure_debut, :heure_fin, TRUE, :id_medecin)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'heure_debut' => $heureDebut,
            'heure_fin' => $heureFin,
            'id_medecin' => $idMedecin,
        ]);
        return (int) $this->pdo->lastInsertId();
    }

   
}
