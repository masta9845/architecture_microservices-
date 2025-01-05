<?php

$dbConfig = array(
    'host' => 'host.docker.internal', 
    'username' => 'root',            
    'password' => '',                
    'dbname' => 'projet_ter_m1_miage' 
);

// Création de la connexion
$conn = new mysqli(
    $dbConfig['host'],
    $dbConfig['username'],
    $dbConfig['password'],
    $dbConfig['dbname']
);

// Vérification de la connexion
if ($conn->connect_error) {
    die('Erreur de connexion à la base de données : ' . $conn->connect_error);
}
?>
