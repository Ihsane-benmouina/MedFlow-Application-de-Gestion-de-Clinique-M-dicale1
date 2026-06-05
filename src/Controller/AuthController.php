<?php

namespace App\Controller;

use App\Repository\UtilisateurRepository;
use App\Repository\MedecinRepository;
use App\Middleware\AuthMiddleware;
use PDO;

class AuthController
{
    private UtilisateurRepository $utilisateurRepository;
    private MedecinRepository $medecinRepository;

    public function __construct(PDO $pdo)
    {
        $this->utilisateurRepository = new UtilisateurRepository($pdo);
        $this->medecinRepository = new MedecinRepository($pdo);
    }

 
    public function loginAction(): void
    {
      
        if (AuthMiddleware::isLoggedIn()) {
            $this->redirectByRole($_SESSION['user']['role']);
            return;
        }

      
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!validateCsrfToken()) {
                $_SESSION['error_msg'] = "Session expirée. Veuillez réessayer.";
                header('Location: index.php?action=login');
                exit();
            }

            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            
            $user = $this->utilisateurRepository->findByEmail($email);

       
            if ($user && password_verify($password, $user['password'])) {
                
                session_regenerate_id(true);

              
                $_SESSION['user'] = [
                    'id'     => $user['id'],
                    'nom'    => $user['nom'],
                    'prenom' => $user['prenom'],
                    'email'  => $user['email'],
                    'role'   => $user['role'],
                ];

               
                if ($user['role'] === 'medecin') {
                    $medecin = $this->medecinRepository->findByUserId($user['id']);
                    if ($medecin) {
                        $_SESSION['user']['id_medecin'] = $medecin['id_medecin'];
                    }
                }

               
                $this->redirectByRole($user['role']);
                return;
            } else {
            
                $_SESSION['error_msg'] = "Email ou mot de passe incorrect.";
                header('Location: index.php?action=login');
                exit();
            }
        }

        include __DIR__ . '/../../templates/auth/login.php';
    }

 
    public function registerAction(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!validateCsrfToken()) {
                $_SESSION['error_msg'] = "Session expirée. Veuillez réessayer.";
                header('Location: index.php?action=register');
                exit();
            }

            $nom = trim($_POST['nom'] ?? '');
            $prenom = trim($_POST['prenom'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            
            $existing = $this->utilisateurRepository->findByEmail($email);
            if ($existing) {
                $_SESSION['error_msg'] = "Cet email est déjà utilisé.";
                header('Location: index.php?action=register');
                exit();
            }

           
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

           
            $this->utilisateurRepository->create($nom, $prenom, $email, $hashedPassword, 'patient');

            $_SESSION['success_msg'] = "Compte créé avec succès ! Connectez-vous.";
            header('Location: index.php?action=login');
            exit();
        }

       
        include __DIR__ . '/../../templates/auth/login.php';
    }


    public function logoutAction(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_destroy();
        header('Location: index.php?action=login');
        exit();
    }

    private function redirectByRole(string $role): void
    {
        switch ($role) {
            case 'admin':
                header('Location: index.php?action=admin_dashboard');
                break;
            case 'medecin':
                header('Location: index.php?action=doctor_dashboard');
                break;
            case 'patient':
                header('Location: index.php?action=patient_dashboard');
                break;
            default:
                header('Location: index.php?action=login');
        }
        exit();
    }
}