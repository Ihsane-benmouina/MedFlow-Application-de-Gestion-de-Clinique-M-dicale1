<?php

namespace App\Repository;

use PDO;

class SpecialiteRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findAll(): array
    {
        $sql = "SELECT * FROM specialites ORDER BY nom ASC";
        return $this->pdo->query($sql)->fetchAll();
    }

        public function findById(int $id): ?array
    {
        $sql = "SELECT * FROM specialites WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

        public function create(string $nom, string $description): int
    {
        $sql = "INSERT INTO specialites (nom, description) VALUES (:nom, :description)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'nom' => $nom,
            'description' => $description,
        ]);
        return (int) $this->pdo->lastInsertId();
    }

       public function countMedecinsParSpecialite(): array
    {
        $sql = "SELECT s.id, s.nom, COUNT(m.id) as total_medecins
                FROM specialites s
                LEFT JOIN medecins m ON s.id = m.id_specialite AND m.actif = TRUE
                GROUP BY s.id, s.nom
                ORDER BY s.nom ASC";
        return $this->pdo->query($sql)->fetchAll();
    }

    

}