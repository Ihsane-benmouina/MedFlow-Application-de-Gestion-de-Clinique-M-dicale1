<?php

namespace App\Repository;

use PDO;


class UtilisateurRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findByEmail(string $email): ?array
    {
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['email' => $email]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

   
    public function findById(int $id): ?array
    {
        $sql = "SELECT * FROM users WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function create(string $nom, string $prenom, string $email, string $password, string $role): int
    {
        $sql = "INSERT INTO users (nom, prenom, email, password, role) 
                VALUES (:nom, :prenom, :email, :password, :role)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'nom' => $nom,
            'prenom' => $prenom,
            'email' => $email,
            'password' => $password,
            'role' => $role,
        ]);
        return (int) $this->pdo->lastInsertId();
    }

 
    public function update(int $id, string $nom, string $prenom, string $email): bool
    {
        $sql = "UPDATE users SET nom = :nom, prenom = :prenom, email = :email WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'id' => $id,
            'nom' => $nom,
            'prenom' => $prenom,
            'email' => $email,
        ]);
    }


  //control
    public function countByRole(string $role): int
    {
        $sql = "SELECT COUNT(*) as total FROM users WHERE role = :role";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['role' => $role]);
        return (int) $stmt->fetch()['total'];
    }
}