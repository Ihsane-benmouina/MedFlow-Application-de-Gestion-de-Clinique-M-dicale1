<?php

namespace App\Controller;

use App\Repository\RendezVousRepository;
use App\Repository\OrdonnanceRepository;
use App\Repository\CreneauRepository;
use App\Middleware\AuthMiddleware;
use PDO;


class DoctorController
{
    private RendezVousRepository $rendezVousRepository;
    private OrdonnanceRepository $ordonnanceRepository;
    private CreneauRepository $creneauRepository;

    public function __construct(PDO $pdo)
    {
        $this->rendezVousRepository = new RendezVousRepository($pdo);
        $this->ordonnanceRepository = new OrdonnanceRepository($pdo);
        $this->creneauRepository = new CreneauRepository($pdo);
    }

   
    public function dashboard(): void
    {
        AuthMiddleware::requireRole('medecin');

        $idMedecin = $_SESSION['user']['id_medecin'] ?? 0;

        $appointments = $this->rendezVousRepository->findByMedecin($idMedecin);

        $creneaux = $this->creneauRepository->findByMedecin($idMedecin);

       
        include __DIR__ . '/../../templates/doctor/dashboard.php';
    }

    public function updateStatutAction(): void
    {
        AuthMiddleware::requireRole('medecin');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!validateCsrfToken()) {
                header('Location: index.php?action=doctor_dashboard');
                exit();
            }

            $idRdv = (int) ($_POST['id_rdv'] ?? 0);
            $statut = $_POST['statut_action'] ?? '';

            if ($idRdv > 0 && in_array($statut, ['Confirmé', 'Annulé'])) {
                $this->rendezVousRepository->updateStatut($idRdv, $statut);

                // Si annulé, rendre le créneau disponible
                if ($statut === 'Annulé') {
                    $rdv = $this->rendezVousRepository->findById($idRdv);
                    if ($rdv) {
                        $this->creneauRepository->marquerDisponible($rdv['id_creneau']);
                    }
                }
            }
        }

        header('Location: index.php?action=doctor_dashboard');
        exit();
    }

 
    public function terminerConsultation(): void
    {
        AuthMiddleware::requireRole('medecin');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!validateCsrfToken()) {
                header('Location: index.php?action=doctor_dashboard');
                exit();
            }

            $idRdv = (int) ($_POST['id_rdv'] ?? 0);
            $ordonnanceContenu = trim($_POST['ordonnance'] ?? '');

            if ($idRdv > 0 && !empty($ordonnanceContenu)) {
                $this->ordonnanceRepository->create($idRdv, $ordonnanceContenu);

                $this->rendezVousRepository->updateStatut($idRdv, 'Terminé');

                $_SESSION['success_msg'] = "Consultation terminée et ordonnance créée.";
            }
        }

        header('Location: index.php?action=doctor_dashboard');
        exit();
    }

    public function ajouterCreneau(): void
    {
        AuthMiddleware::requireRole('medecin');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!validateCsrfToken()) {
                header('Location: index.php?action=doctor_dashboard');
                exit();
            }

            $idMedecin = $_SESSION['user']['id_medecin'] ?? 0;
            $heureDebut = $_POST['heure_debut'] ?? '';
            $heureFin = $_POST['heure_fin'] ?? '';

            if ($idMedecin > 0 && !empty($heureDebut) && !empty($heureFin)) {
                $this->creneauRepository->create($heureDebut, $heureFin, $idMedecin);
                $_SESSION['success_msg'] = "Créneau ajouté avec succès.";
            }
        }

        header('Location: index.php?action=doctor_dashboard');
        exit();
    }
}
