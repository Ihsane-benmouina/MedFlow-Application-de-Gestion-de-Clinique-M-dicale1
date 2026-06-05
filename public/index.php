<?php


ob_start();

require_once __DIR__ . '/../config/security.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$pdo = require_once __DIR__ . '/../config/database.php';

require_once __DIR__ . '/../src/Middleware/AuthMiddleware.php';

require_once __DIR__ . '/../src/Repository/UtilisateurRepository.php';
require_once __DIR__ . '/../src/Repository/SpecialiteRepository.php';
require_once __DIR__ . '/../src/Repository/MedecinRepository.php';
require_once __DIR__ . '/../src/Repository/CreneauRepository.php';
require_once __DIR__ . '/../src/Repository/RendezVousRepository.php';
require_once __DIR__ . '/../src/Repository/OrdonnanceRepository.php';

require_once __DIR__ . '/../src/Controller/AuthController.php';
require_once __DIR__ . '/../src/Controller/AdminController.php';
require_once __DIR__ . '/../src/Controller/DoctorController.php';
require_once __DIR__ . '/../src/Controller/PatientController.php';

use App\Controller\AuthController;
use App\Controller\AdminController;
use App\Controller\DoctorController;
use App\Controller\PatientController;

$authController = new AuthController($pdo);
$adminController = new AdminController($pdo);
$doctorController = new DoctorController($pdo);
$patientController = new PatientController($pdo);

$action = $_GET['action'] ?? 'home';

switch ($action) {

    case 'home':
        $patientController->index();
        break;

    case 'login':
        $authController->loginAction();
        break;

    case 'register':
        $authController->registerAction();
        break;

    case 'logout':
        $authController->logoutAction();
        break;

    case 'patient_dashboard':
        $patientController->dashboard();
        break;

    case 'reserver_rdv':
        $patientController->reserver();
        break;

    case 'telecharger_ordonnance':
        $patientController->telechargerOrdonnance();
        break;

    case 'doctor_dashboard':
        $doctorController->dashboard();
        break;

    case 'doctor_update_statut':
        $doctorController->updateStatutAction();
        break;

    case 'terminer_consultation':
        $doctorController->terminerConsultation();
        break;

    case 'ajouter_creneau':
        $doctorController->ajouterCreneau();
        break;

    case 'admin_dashboard':
        $adminController->dashboard();
        break;

    case 'admin_creer_medecin':
        $adminController->creerMedecin();
        break;

    case 'admin_modifier_medecin':
        $adminController->modifierMedecin();
        break;

    case 'admin_toggle_medecin':
        $adminController->toggleMedecin();
        break;

    default:
        $patientController->index();
        break;
}

ob_end_flush();
