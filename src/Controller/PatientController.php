<?php

namespace App\Controller;

use App\Repository\MedecinRepository;
use App\Repository\SpecialiteRepository;
use App\Repository\RendezVousRepository;
use App\Repository\OrdonnanceRepository;
use App\Repository\CreneauRepository;
use App\Middleware\AuthMiddleware;
use PDO;

/**
 * Contrôleur Patient
 * Gère la recherche de médecins, la réservation et le suivi des RDV
 */
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

    /**
     * Page d'accueil - recherche de médecins (accessible sans login)
     */
    public function index(): void
    {
        // Récupérer les spécialités et médecins pour la recherche
        $specialites = $this->specialiteRepository->findAll();
        $medecins = $this->medecinRepository->findActifsAvecCreneaux();

        // Afficher la vue de recherche
        include __DIR__ . '/../../templates/patient/recherche.php';
    }

    /**
     * Tableau de bord du patient
     */
    public function dashboard(): void
    {
        // Vérifier que l'utilisateur est un patient
        AuthMiddleware::requireRole('patient');

        $idPatient = $_SESSION['user']['id'];

        // Récupérer les données du patient
        $specialites = $this->specialiteRepository->findAll();
        $medecins = $this->medecinRepository->findActifsAvecCreneaux();
        $mesRendezVous = $this->rendezVousRepository->findByPatient($idPatient);
        $mesOrdonnances = $this->ordonnanceRepository->findByPatient($idPatient);

        // Afficher la vue
        include __DIR__ . '/../../templates/patient/dashboard.php';
    }

    
    /**
     * Réserver un rendez-vous
     */
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
                // Vérifier que le créneau est disponible
                $creneau = $this->creneauRepository->findById($idCreneau);

                if ($creneau && $creneau['disponible']) {
                    // Créer le rendez-vous
                    $this->rendezVousRepository->create($idPatient, $idMedecin, $idCreneau);

                    // Marquer le créneau comme indisponible
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

    
    /**
     * Télécharger une ordonnance en PDF simplifié (texte)
     */
    public function telechargerOrdonnance(): void
    {
        AuthMiddleware::requireRole('patient');

        $idOrdonnance = (int) ($_GET['id'] ?? 0);
        $idPatient = $_SESSION['user']['id'];

        if ($idOrdonnance > 0) {
            $ordonnance = $this->ordonnanceRepository->findById($idOrdonnance);

            // Vérifier que l'ordonnance appartient au patient
            if ($ordonnance && $ordonnance['id_patient'] == $idPatient) {
                // Télécharger en tant que fichier texte
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