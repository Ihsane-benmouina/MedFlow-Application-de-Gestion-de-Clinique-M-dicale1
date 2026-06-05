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

}