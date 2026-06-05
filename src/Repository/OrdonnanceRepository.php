<?php

namespace App\Repository;

use PDO;

/**
 * Repository pour la table 'ordonnances'
 * Toutes les requêtes SQL liées aux ordonnances sont ici
 */
class OrdonnanceRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Créer une ordonnance pour un rendez-vous
     */
    public function create(int $idRendezVous, string $description): int
    {
        $sql = "INSERT INTO ordonnances (id_rendez_vous, description) 
                VALUES (:id_rendez_vous, :description)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'id_rendez_vous' => $idRendezVous,
            'description' => $description,
        ]);
        return (int) $this->pdo->lastInsertId();
    }



    /**
     * Trouver une ordonnance par son ID
     */
    public function findById(int $id): ?array
    {
        $sql = "SELECT o.*, r.id_patient, r.id_medecin
                FROM ordonnances o
                JOIN rendez_vous r ON o.id_rendez_vous = r.id
                WHERE o.id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }


    /**
     * Récupérer les ordonnances d'un patient
     */
    public function findByPatient(int $idPatient): array
    {
        $sql = "SELECT o.id, o.description as contenu, 
                       c.heure_debut as date_rdv,
                       u.nom as medecin_nom, u.prenom as medecin_prenom,
                       s.nom as specialite_nom
                FROM ordonnances o
                JOIN rendez_vous r ON o.id_rendez_vous = r.id
                JOIN creneaux c ON r.id_creneau = c.id
                JOIN medecins m ON r.id_medecin = m.id
                JOIN users u ON m.id_user = u.id
                JOIN specialites s ON m.id_specialite = s.id
                WHERE r.id_patient = :id_patient
                ORDER BY c.heure_debut DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id_patient' => $idPatient]);
        return $stmt->fetchAll();
    }


    /**
     * Trouver une ordonnance par son rendez-vous
     */
    public function findByRendezVous(int $idRendezVous): ?array
    {
        $sql = "SELECT * FROM ordonnances WHERE id_rendez_vous = :id_rdv";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id_rdv' => $idRendezVous]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    
    /**
     * Modifier le contenu d'une ordonnance
     */
    public function updateContenu(int $id, string $description): bool
    {
        $sql = "UPDATE ordonnances SET description = :description WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'description' => $description,
            'id' => $id,
        ]);
    }
}