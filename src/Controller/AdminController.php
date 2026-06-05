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


    
}    