<?php

namespace App\Repository;

use PDO;

class MedecinRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

        public function findAll(): array
    {
        $sql = "SELECT m.id as id_medecin, m.actif, m.id_specialite,
                       u.id as id_user, u.nom, u.prenom, u.email,
                       s.nom as specialite_nom
                FROM medecins m
                JOIN users u ON m.id_user = u.id
                JOIN specialites s ON m.id_specialite = s.id
                ORDER BY u.nom ASC";
        return $this->pdo->query($sql)->fetchAll();
    }
}