-- Création de la base de données (si elle n'existe pas)
CREATE DATABASE IF NOT EXISTS projet_ter_m1_miage;

-- Utilisation de la base de données
USE projet_ter_m1_miage;

-- Table des utilisateurs
CREATE TABLE IF NOT EXISTS utilisateur (
    id_utilisateur INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50) NOT NULL,
    prenom VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    date_naissance DATE NOT NULL,
    date_inscription TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table des post-its
CREATE TABLE IF NOT EXISTS postit (
    id_postit INT AUTO_INCREMENT PRIMARY KEY,
    id_owner INT NOT NULL,
    titre VARCHAR(255) NOT NULL,
    contenu TEXT NOT NULL,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_owner) REFERENCES utilisateur(id_utilisateur) ON DELETE CASCADE
);

-- Table des partages
CREATE TABLE IF NOT EXISTS partage (
    id_partage INT AUTO_INCREMENT PRIMARY KEY,
    id_postit INT NOT NULL,
    id_user INT NOT NULL,
    FOREIGN KEY (id_postit) REFERENCES postit(id_postit) ON DELETE CASCADE,
    FOREIGN KEY (id_user) REFERENCES utilisateur(id_utilisateur) ON DELETE CASCADE
);
