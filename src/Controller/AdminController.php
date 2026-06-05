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




}    