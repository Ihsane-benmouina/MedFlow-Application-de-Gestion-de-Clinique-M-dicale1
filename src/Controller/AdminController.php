<?php

namespace App\Controller;

use App\Repository\MedecinRepository;
use App\Repository\SpecialiteRepository;
use App\Repository\UtilisateurRepository;
use App\Repository\RendezVousRepository;
use App\Middleware\AuthMiddleware;
use PDO;

/**
 * Contrôleur Admin
 * Gère la création, modification et désactivation des médecins
 */
class AdminController
{
    private MedecinRepository $medecinRepository;
    private SpecialiteRepository $specialiteRepository;
    private UtilisateurRepository $utilisateurRepository;
    private RendezVousRepository $rendezVousRepository;

    public function __construct(PDO $pdo)
    {
        $this->medecinRepository = new MedecinRepository($pdo);
        $this->specialiteRepository = new SpecialiteRepository($pdo);
        $this->utilisateurRepository = new UtilisateurRepository($pdo);
        $this->rendezVousRepository = new RendezVousRepository($pdo);
    }


        /**
     * Afficher le tableau de bord admin
     */
    public function dashboard(): void
    {
        // Vérifier que l'utilisateur est admin
        AuthMiddleware::requireRole('admin');

        // Statistiques pour le dashboard
        $totalMedecins = $this->medecinRepository->countActifs();
        $totalPatients = $this->utilisateurRepository->countByRole('patient');
        $totalRdv = $this->rendezVousRepository->countAll();
        $rdvEnAttente = $this->rendezVousRepository->countByStatut('En attente');

        // Liste des médecins
        $medecins = $this->medecinRepository->findAll();

        // Spécialités avec comptage
        $specialites = $this->specialiteRepository->countMedecinsParSpecialite();

        // Liste des spécialités pour le formulaire
        $listeSpecialites = $this->specialiteRepository->findAll();

        // Afficher la vue
        include __DIR__ . '/../../templates/admin/dashboard.php';
    }

     public function creerMedecin(): void
    {
        AuthMiddleware::requireRole('admin');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!validateCsrfToken()) {
                header('Location: index.php?action=admin_dashboard');
                exit();
            }

            $nom = trim($_POST['nom'] ?? '');
            $prenom = trim($_POST['prenom'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $idSpecialite = (int) ($_POST['id_specialite'] ?? 0);

            // Vérifier que l'email n'existe pas
            $existing = $this->utilisateurRepository->findByEmail($email);
            if ($existing) {
                $_SESSION['error_msg'] = "Cet email est déjà utilisé.";
                header('Location: index.php?action=admin_dashboard');
                exit();
            }

            // Hacher le mot de passe
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            // Créer l'utilisateur avec le rôle 'medecin'
            $idUser = $this->utilisateurRepository->create($nom, $prenom, $email, $hashedPassword, 'medecin');

            // Créer l'entrée dans la table medecins
            $this->medecinRepository->create($idUser, $idSpecialite);

            $_SESSION['success_msg'] = "Médecin créé avec succès.";
            header('Location: index.php?action=admin_dashboard');
            exit();
        }
        }


         public function modifierMedecin(): void
    {
        AuthMiddleware::requireRole('admin');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!validateCsrfToken()) {
                header('Location: index.php?action=admin_dashboard');
                exit();
            }

            $idMedecin = (int) ($_POST['id_medecin'] ?? 0);
            $nom = trim($_POST['nom'] ?? '');
            $prenom = trim($_POST['prenom'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $idSpecialite = (int) ($_POST['id_specialite'] ?? 0);

            // Récupérer le médecin pour avoir l'id_user
            $medecin = $this->medecinRepository->findById($idMedecin);
            if ($medecin) {
                // Mettre à jour les infos utilisateur
                $this->utilisateurRepository->update($medecin['id_user'], $nom, $prenom, $email);
                // Mettre à jour la spécialité
                $this->medecinRepository->updateSpecialite($idMedecin, $idSpecialite);

                $_SESSION['success_msg'] = "Médecin modifié avec succès.";
            }

            header('Location: index.php?action=admin_dashboard');
            exit();
        }
    }

        public function toggleMedecin(): void
    {
        AuthMiddleware::requireRole('admin');

        $idMedecin = (int) ($_GET['id'] ?? 0);
        $action = $_GET['toggle'] ?? '';

        if ($idMedecin > 0) {
            $actif = ($action === 'activer');
            $this->medecinRepository->toggleActif($idMedecin, $actif);
            $_SESSION['success_msg'] = $actif ? "Médecin activé." : "Médecin désactivé.";
        }

        header('Location: index.php?action=admin_dashboard');
        exit();
    }

     public function creerSpecialite(): void
    {
        AuthMiddleware::requireRole('admin');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Vérification CSRF (ila knti khdam biha f l-projet)
            if (function_exists('validateCsrfToken') && !validateCsrfToken()) {
                header('Location: index.php?action=admin_dashboard');
                exit();
            }

            $nom = trim($_POST['nom'] ?? '');
            $description = trim($_POST['description'] ?? '');

            if (empty($nom)) {
                $_SESSION['error_msg'] = "Le nom de la spécialité est obligatoire.";
                header('Location: index.php?action=admin_dashboard');
                exit();
            }

            // Appeler directement la méthode create du SpecialiteRepository déjà injecté
            $this->specialiteRepository->create($nom, $description);

            $_SESSION['success_msg'] = "La spécialité '$nom' a été ajoutée avec succès.";
            header('Location: index.php?action=admin_dashboard');
            exit();
        }
    }
    /**
     * 🌟 Modifier une spécialité existante
     */
    public function modifierSpecialite(): void
    {
        AuthMiddleware::requireRole('admin');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = intval($_POST['id_specialite'] ?? 0);
            $nom = trim($_POST['nom'] ?? '');
            $description = trim($_POST['description'] ?? '');

            if ($id <= 0 || empty($nom)) {
                $_SESSION['error_msg'] = "Données invalides pour la modification.";
                header('Location: index.php?action=admin_dashboard');
                exit();
            }

            // Hna t9der t-khdem b query direct f Repository dyalk
            // b7al: $this->specialiteRepository->update($id, $nom, $description);
            // ghadi n-ktbha lik b update standard 3la 7sab l-méthode dyalk:
            $this->specialiteRepository->update($id, $nom, $description);

            $_SESSION['success_msg'] = "La spécialité a été modifiée avec succès.";
            header('Location: index.php?action=admin_dashboard');
            exit();
        }
    }

    /**
     * 🌟 Supprimer une spécialité
     */
    public function supprimerSpecialite(): void
    {
        AuthMiddleware::requireRole('admin');

        $id = intval($_GET['id'] ?? 0);

        if ($id <= 0) {
            $_SESSION['error_msg'] = "ID de spécialité invalide.";
            header('Location: index.php?action=admin_dashboard');
            exit();
        }

        // Appel de la méthode delete f l-repository dyalk
        $this->specialiteRepository->delete($id);

        $_SESSION['success_msg'] = "La spécialité a été supprimée avec succès.";
        header('Location: index.php?action=admin_dashboard');
        exit();
    }


      

    }



    




