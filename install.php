<?php
$servername = 'localhost';
$username = 'root';
$password = '';

//  On établit la connexion
$conn = new mysqli($servername, $username, $password);

//   On vérifie la connexion
if ($conn->connect_error) {
    die('Erreur : ' . $conn->connect_error);
}
echo 'Connexion réussie';

// Création de la base de données
$sqlCreateDatabase = "CREATE DATABASE IF NOT EXISTS projet_ter_m1_miage";
if ($conn->query($sqlCreateDatabase) === TRUE) {
    echo 'Base de données créée avec succès';
} else {
    die('Erreur lors de la création de la base de données : ' . $conn->error);
}

// Sélection de la base de données
$conn->select_db("projet_ter_m1_miage");

// Création des tables
$sqlCreateTableUtilisateur = "
CREATE TABLE IF NOT EXISTS utilisateur(
    id_utilisateur INT NOT NULL AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    date_de_naissance DATE NOT NULL,
    email VARCHAR(100) NOT NULL,
    mot_de_passe VARCHAR(255) NOT NULL,
    PRIMARY KEY(id_utilisateur),
    CONSTRAINT uc_utilisateur_email UNIQUE(email)
)";
$conn->query($sqlCreateTableUtilisateur);

$sqlCreateTablePostit = "
CREATE TABLE IF NOT EXISTS Postit(
    id_postit INT NOT NULL AUTO_INCREMENT,
    id_owner INT NOT NULL,
    titre VARCHAR(100) NOT NULL,
    contenu VARCHAR(255) NOT NULL,
    date_creation DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, 
    PRIMARY KEY(id_postit),
    CONSTRAINT fk_Postit_id_owner FOREIGN KEY(id_owner) REFERENCES utilisateur(id_utilisateur)
)";
$conn->query($sqlCreateTablePostit);

$sqlCreateTablePartage = "
CREATE TABLE IF NOT EXISTS Partage(
    id_postit INT NOT NULL,
    id_user INT NOT NULL,
    PRIMARY KEY(id_postit, id_user),
    FOREIGN KEY(id_postit) REFERENCES Postit(id_postit),
    FOREIGN KEY(id_user) REFERENCES utilisateur(id_utilisateur)
)";
$conn->query($sqlCreateTablePartage);
echo 'Tables créées avec succès';

// Fermer la connexion
$conn->close();

?>

