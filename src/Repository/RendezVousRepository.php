<?php

namespace App\Repository;

use PDO;

/**
 * Repository pour la table 'rendez_vous'
 * Toutes les requêtes SQL liées aux rendez-vous sont ici
 */
class RendezVousRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Créer un nouveau rendez-vous
     */
    public function create(int $idPatient, int $idMedecin, int $idCreneau): int
    {
        $sql = "INSERT INTO rendez_vous (id_patient, id_medecin, id_creneau, statut) 
                VALUES (:id_patient, :id_medecin, :id_creneau, 'En attente')";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'id_patient' => $idPatient,
            'id_medecin' => $idMedecin,
            'id_creneau' => $idCreneau,
        ]);
        return (int) $this->pdo->lastInsertId();
    }
    

    /**
     * Récupérer les rendez-vous d'un patient avec détails
     */
    public function findByPatient(int $idPatient): array
    {
        $sql = "SELECT r.id as id_rdv, r.statut, 
                       c.heure_debut, c.heure_fin,
                       u.nom as medecin_nom, u.prenom as medecin_prenom,
                       s.nom as specialite_nom
                FROM rendez_vous r
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
     * Récupérer les rendez-vous d'un médecin avec détails
     */
    public function findByMedecin(int $idMedecin): array
    {
        $sql = "SELECT r.id as id_rdv, r.statut,
                       c.heure_debut, c.heure_fin,
                       u.nom as patient_nom, u.prenom as patient_prenom,
                       u.email as patient_email
                FROM rendez_vous r
                JOIN creneaux c ON r.id_creneau = c.id
                JOIN users u ON r.id_patient = u.id
                WHERE r.id_medecin = :id_medecin
                ORDER BY c.heure_debut ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id_medecin' => $idMedecin]);
        return $stmt->fetchAll();
    }

    /**
     * Mettre à jour le statut d'un rendez-vous
     */
    public function updateStatut(int $idRdv, string $statut): bool
    {
        $sql = "UPDATE rendez_vous SET statut = :statut WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'statut' => $statut,
            'id' => $idRdv,
        ]);
    }

    /**
     * Trouver un rendez-vous par son ID
     */
    public function findById(int $id): ?array
    {
        $sql = "SELECT r.*, c.heure_debut, c.heure_fin, c.id_medecin
                FROM rendez_vous r
                JOIN creneaux c ON r.id_creneau = c.id
                WHERE r.id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * Compter les rendez-vous par statut
     */
    public function countByStatut(string $statut): int
    {
        $sql = "SELECT COUNT(*) as total FROM rendez_vous WHERE statut = :statut";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['statut' => $statut]);
        return (int) $stmt->fetch()['total'];
    }

    /**
     * Compter le total des rendez-vous
     */
    public function countAll(): int
    {
        $sql = "SELECT COUNT(*) as total FROM rendez_vous";
        return (int) $this->pdo->query($sql)->fetch()['total'];
    }
}