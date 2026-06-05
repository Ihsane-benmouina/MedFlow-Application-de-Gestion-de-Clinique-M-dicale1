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

}