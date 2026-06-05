-- =============================================
-- MedFlow - Base de données de la clinique
-- =============================================

CREATE DATABASE IF NOT EXISTS medflow_db;
USE medflow_db;

-- ===================================
-- TABLE: users (Utilisateurs)
-- ===================================
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'medecin', 'patient') NOT NULL
);

-- ===================================
-- TABLE: specialites
-- ===================================
CREATE TABLE specialites (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    description VARCHAR(255)
);

-- ===================================
-- TABLE: medecins
-- ===================================
CREATE TABLE medecins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL UNIQUE,
    id_specialite INT NOT NULL,
    actif BOOLEAN DEFAULT TRUE,

    CONSTRAINT fk_medecin_user
        FOREIGN KEY (id_user) REFERENCES users(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_medecin_specialite
        FOREIGN KEY (id_specialite) REFERENCES specialites(id)
        ON DELETE RESTRICT
);

-- ===================================
-- TABLE: creneaux
-- ===================================
CREATE TABLE creneaux (
    id INT AUTO_INCREMENT PRIMARY KEY,
    heure_debut DATETIME NOT NULL,
    heure_fin DATETIME NOT NULL,
    disponible BOOLEAN DEFAULT TRUE,
    id_medecin INT NOT NULL,

    CONSTRAINT fk_creneau_medecin
        FOREIGN KEY (id_medecin) REFERENCES medecins(id)
        ON DELETE CASCADE
);

-- ===================================
-- TABLE: rendez_vous
-- ===================================
CREATE TABLE rendez_vous (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_patient INT NOT NULL,
    id_medecin INT NOT NULL,
    id_creneau INT NOT NULL,
    statut ENUM('En attente', 'Confirmé', 'Annulé', 'Terminé') DEFAULT 'En attente',

    CONSTRAINT fk_rendezvous_patient
        FOREIGN KEY (id_patient) REFERENCES users(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_rendezvous_medecin
        FOREIGN KEY (id_medecin) REFERENCES medecins(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_rendezvous_creneau
        FOREIGN KEY (id_creneau) REFERENCES creneaux(id)
        ON DELETE CASCADE
);

-- ===================================
-- TABLE: ordonnances
-- ===================================
CREATE TABLE ordonnances (
    id INT AUTO_INCREMENT PRIMARY KEY,
    description TEXT NOT NULL,
    id_rendez_vous INT NOT NULL UNIQUE,

    CONSTRAINT fk_ordonnance_rendezvous
        FOREIGN KEY (id_rendez_vous) REFERENCES rendez_vous(id)
        ON DELETE CASCADE
);

-- ===================================
-- DONNÉES DE DÉMONSTRATION
-- ===================================

-- Admin par défaut (mot de passe: admin123)
INSERT INTO users (nom, prenom, email, password, role) VALUES
('Admin', 'Super', 'admin@medflow.com', '$2y$10$WJ/Lv8YPuNTKoL0mgPDj9uJ31hanDM/eybNrzJyhPJWnagDRNPTGG', 'admin');

-- Spécialités
INSERT INTO specialites (nom, description) VALUES
('Cardiologie', 'Spécialité du coeur et des vaisseaux sanguins'),
('Dermatologie', 'Spécialité de la peau'),
('Médecine Générale', 'Consultations générales et suivi médical'),
('Pédiatrie', 'Médecine des enfants et adolescents'),
('Ophtalmologie', 'Spécialité des yeux et de la vision');

-- Médecins (mot de passe: medecin123)
INSERT INTO users (nom, prenom, email, password, role) VALUES
('Alami', 'Ahmed', 'ahmed.alami@medflow.com', '$2y$10$m84Rt8Hcw6FILOmuF3t5Luu2rRIXINkihoE2uiruqDYqKEgIFJSfa', 'medecin'),
('Benjelloun', 'Fatima', 'fatima.benjelloun@medflow.com', '$2y$10$m84Rt8Hcw6FILOmuF3t5Luu2rRIXINkihoE2uiruqDYqKEgIFJSfa', 'medecin'),
('Tazi', 'Youssef', 'youssef.tazi@medflow.com', '$2y$10$m84Rt8Hcw6FILOmuF3t5Luu2rRIXINkihoE2uiruqDYqKEgIFJSfa', 'medecin');

INSERT INTO medecins (id_user, id_specialite, actif) VALUES
(2, 1, TRUE),
(3, 2, TRUE),
(4, 3, TRUE);

-- Patient (mot de passe: patient123)
INSERT INTO users (nom, prenom, email, password, role) VALUES
('Nassiri', 'Khalid', 'khalid.nassiri@medflow.com', '$2y$10$37LhNShpAFODgbXtflva8.cqrRePkMRE1Ky82rOFJF3p/4x4AxBTG', 'patient');

-- Créneaux pour les médecins
INSERT INTO creneaux (heure_debut, heure_fin, disponible, id_medecin) VALUES
('2026-06-05 09:00:00', '2026-06-05 09:30:00', TRUE, 1),
('2026-06-05 10:00:00', '2026-06-05 10:30:00', TRUE, 1),
('2026-06-05 11:00:00', '2026-06-05 11:30:00', TRUE, 1),
('2026-06-05 09:00:00', '2026-06-05 09:30:00', TRUE, 2),
('2026-06-05 14:00:00', '2026-06-05 14:30:00', TRUE, 2),
('2026-06-06 09:00:00', '2026-06-06 09:30:00', TRUE, 3),
('2026-06-06 10:00:00', '2026-06-06 10:30:00', TRUE, 3);
