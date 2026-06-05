<?php

namespace App\Controller;

use App\Repository\MedecinRepository;
use App\Repository\SpecialiteRepository;
use App\Repository\RendezVousRepository;
use App\Repository\OrdonnanceRepository;
use App\Repository\CreneauRepository;
use App\Middleware\AuthMiddleware;
use PDO;


class PatientController
{
    private MedecinRepository $medecinRepository;
    private SpecialiteRepository $specialiteRepository;
    private RendezVousRepository $rendezVousRepository;
    private OrdonnanceRepository $ordonnanceRepository;
    private CreneauRepository $creneauRepository;

    public function __construct(PDO $pdo)
    {
        $this->medecinRepository = new MedecinRepository($pdo);
        $this->specialiteRepository = new SpecialiteRepository($pdo);
        $this->rendezVousRepository = new RendezVousRepository($pdo);
        $this->ordonnanceRepository = new OrdonnanceRepository($pdo);
        $this->creneauRepository = new CreneauRepository($pdo);
    }

    
    public function index(): void
    {
        $specialites = $this->specialiteRepository->findAll();
        $medecins = $this->medecinRepository->findActifsAvecCreneaux();

        include __DIR__ . '/../../templates/patient/recherche.php';
    }

    
    public function dashboard(): void
    {
        AuthMiddleware::requireRole('patient');

        $idPatient = $_SESSION['user']['id'];

        $specialites = $this->specialiteRepository->findAll();
        $medecins = $this->medecinRepository->findActifsAvecCreneaux();
        $mesRendezVous = $this->rendezVousRepository->findByPatient($idPatient);
        $mesOrdonnances = $this->ordonnanceRepository->findByPatient($idPatient);

        include __DIR__ . '/../../templates/patient/dashboard.php';
    }

    
   
    public function reserver(): void
    {
        AuthMiddleware::requireRole('patient');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!validateCsrfToken()) {
                $_SESSION['error_msg'] = "Session expirée. Veuillez réessayer.";
                header('Location: index.php?action=patient_dashboard');
                exit();
            }

            $idPatient = $_SESSION['user']['id'];
            $idCreneau = (int) ($_POST['id_creneau'] ?? 0);
            $idMedecin = (int) ($_POST['id_medecin'] ?? 0);

            if ($idCreneau > 0 && $idMedecin > 0) {
                $creneau = $this->creneauRepository->findById($idCreneau);

                if ($creneau && $creneau['disponible']) {
                    $this->rendezVousRepository->create($idPatient, $idMedecin, $idCreneau);

                    $this->creneauRepository->marquerIndisponible($idCreneau);

                    $_SESSION['success_msg'] = "Rendez-vous réservé avec succès !";
                } else {
                    $_SESSION['error_msg'] = "Ce créneau n'est plus disponible.";
                }
            }
        }

        header('Location: index.php?action=patient_dashboard');
        exit();
    }


    
    public function telechargerOrdonnance(): void
    {
        AuthMiddleware::requireRole('patient');

        $idOrdonnance = (int) ($_GET['id'] ?? 0);
        $idPatient = $_SESSION['user']['id'];

        if ($idOrdonnance > 0) {
            $ordonnance = $this->ordonnanceRepository->findById($idOrdonnance);

            if ($ordonnance && $ordonnance['id_patient'] == $idPatient) {
                header('Content-Type: text/plain; charset=utf-8');
                header('Content-Disposition: attachment; filename="ordonnance_' . $idOrdonnance . '.txt"');
                echo "=== ORDONNANCE MÉDICALE ===\n";
                echo "MedFlow - Clinique Médicale\n";
                echo "Date: " . date('d/m/Y') . "\n";
                echo "===========================\n\n";
                echo $ordonnance['description'];
                echo "\n\n===========================\n";
                echo "Document généré par MedFlow\n";
                exit();
            }
        }

        $_SESSION['error_msg'] = "Ordonnance introuvable.";
        header('Location: index.php?action=patient_dashboard');
        exit();
    }
}