# MedFlow - Application de Gestion de Clinique Médicale

Projet éducatif PHP OOP pour la gestion d'une clinique médicale.

## Stack Technique

- **PHP 8** (Programmation Orientée Objet)
- **MySQL** (Base de données)
- **PDO** (Connexion sécurisée)
- **Tailwind CSS** (Interface utilisateur via CDN)
- **PHP Sessions** (Authentification)

## Structure du Projet

```
medflow/
├── config/
│   ├── database.php          # Connexion PDO à MySQL
│   └── security.php          # Fonctions de sécurité
├── public/
│   ├── css/                  # Styles CSS
│   ├── js/                   # Scripts JavaScript
│   └── index.php             # Point d'entrée (Routing)
├── src/
│   ├── Controller/
│   │   ├── AdminController.php
│   │   ├── AuthController.php
│   │   ├── DoctorController.php
│   │   └── PatientController.php
│   ├── Entity/
│   │   ├── Utilisateur.php
│   │   ├── Administrateur.php
│   │   ├── Patient.php
│   │   ├── Medecin.php
│   │   ├── Specialite.php
│   │   ├── Creneau.php
│   │   ├── RendezVous.php
│   │   └── Ordonnance.php
│   ├── Enum/
│   │   ├── RoleEnum.php
│   │   └── StatutRendezVousEnum.php
│   ├── Middleware/
│   │   └── AuthMiddleware.php
│   └── Repository/
│       ├── UtilisateurRepository.php
│       ├── MedecinRepository.php
│       ├── SpecialiteRepository.php
│       ├── CreneauRepository.php
│       ├── RendezVousRepository.php
│       └── OrdonnanceRepository.php
├── templates/
│   ├── admin/
│   │   └── dashboard.php
│   ├── doctor/
│   │   └── dashboard.php
│   ├── patient/
│   │   ├── dashboard.php
│   │   └── recherche.php
│   ├── auth/
│   │   └── login.php
│   └── layout/
│       ├── header.php
│       └── footer.php
├── database.sql              # Schéma SQL complet
├── .env                      # Variables d'environnement
└── README.md
```
## Diagramm de classe
![
    
](<docs/class diagram clinique.png>)
## Diagramm de cas de utilisation
![alt text](<docs/usecase clinique.png>)
## ER diagramme
![alt text](<docs/er diagramme.png>)



## Installation

### 1. Prérequis

- PHP 8.0 ou supérieur
- MySQL 5.7 ou supérieur
- Serveur web (Apache/Nginx) ou PHP built-in server

### 2. Base de données

```bash
# Créer la base de données et les tables
mysql -u root -p < database.sql
```

### 3. Configuration

Modifiez le fichier `.env` à la racine du projet :

```env
DB_HOST=localhost
DB_NAME=medflow_db
DB_USER=root
DB_PASS=
```

### 4. Lancer le serveur

```bash
# Depuis le dossier du projet
cd public
php -S localhost:8000
```

Accédez à l'application : `http://localhost:8000`

## Comptes de démonstration

| Rôle    | Email                          | Mot de passe |
|---------|--------------------------------|--------------|
| Admin   | admin@medflow.com              | admin123     |
| Médecin | ahmed.alami@medflow.com        | medecin123   |
| Médecin | fatima.benjelloun@medflow.com   | medecin123   |
| Patient | khalid.nassiri@medflow.com     | patient123   |

> **Note** : Les mots de passe dans la base de données sont hachés avec bcrypt. Utilisez le script d'initialisation pour créer les comptes de démonstration.

## Architecture

### Diagramme de Classes

- **Utilisateur** : Classe de base (id, nom, prenom, email, password)
  - **Administrateur** extends Utilisateur : creerMedecin(), modifierMedecin(), desactiverMedecin()
  - **Patient** extends Utilisateur : rechercherMedecin(), reserverRendezVous(), telechargerOrdonnance()
  - **Medecin** extends Utilisateur : visualiserPlanning(), validerRendezVous(), terminerConsultation()
- **Specialite** : id, nom, description
- **Creneau** : id, heureDebut, heureFin, disponible
- **RendezVous** : id, statut (En attente / Confirmé / Annulé / Terminé)
- **Ordonnance** : id, contenu, dateCreation

### Relations

- Administrateur gère les Médecins
- Médecin appartient à une Spécialité
- Spécialité contient plusieurs Médecins
- Médecin possède plusieurs Créneaux
- Patient réserve plusieurs RendezVous
- RendezVous utilise un Créneau
- RendezVous génère zéro ou une Ordonnance

### Règles SQL

- Toutes les requêtes SQL sont dans les classes **Repository**
- Les Controllers ne contiennent **jamais** de SQL
- Les Entities ne contiennent **jamais** de SQL

## Fonctionnalités

### Patient
- Rechercher des médecins par nom ou spécialité
- Consulter les créneaux disponibles
- Réserver un rendez-vous
- Voir l'historique des rendez-vous
- Télécharger les ordonnances

### Médecin
- Visualiser le planning
- Confirmer / Annuler un rendez-vous
- Terminer une consultation
- Rédiger une ordonnance
- Gérer ses créneaux horaires

### Administrateur
- Créer un médecin
- Modifier un médecin
- Désactiver / Activer un médecin
- Consulter les statistiques de la clinique

## Sécurité

- Mots de passe hachés avec **bcrypt** (`password_hash` / `password_verify`)
- Protection XSS avec `htmlspecialchars()`
- Requêtes préparées PDO (protection injection SQL)
- Middleware de vérification de rôle
- Sessions PHP sécurisées
