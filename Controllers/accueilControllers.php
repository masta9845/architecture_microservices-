<?php

// Inclure le fichier de connexion
include('config.php');
// Connexion à la base de données MySQL en utilisant les informations du fichier de connexion
$conn = new mysqli($dbConfig['host'], $dbConfig['username'], $dbConfig['password'], $dbConfig['dbname']);

// Vérifier la connexion
if ($conn->connect_error) {
    die('Erreur de connexion à la base de données : ' . $conn->connect_error);
}

function getOwnedPostIts($userId, $conn)
{
    $sql = "SELECT id_postit, titre, contenu, date_creation FROM Postit WHERE id_owner = ? ORDER BY date_creation DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Fonction pour récupérer les post-its partagés avec l'utilisateur
function getSharedPostIts($userId, $conn)
{
    $sql = "SELECT P.id_postit, P.titre,P.id_owner, P.contenu,date_creation, U.nom AS owner_nom, U.prenom AS owner_prenom
            FROM Postit AS P
            INNER JOIN Partage AS PR ON P.id_postit = PR.id_postit
            INNER JOIN utilisateur AS U ON P.id_owner = U.id_utilisateur
            WHERE PR.id_user = ? ORDER BY date_creation DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}
$user_id = $_SESSION['user_id'];
$postitPo = json_encode(getOwnedPostIts($user_id, $conn));
$postitPa = json_encode(getSharedPostIts($user_id, $conn));

header("Location: index.php?action=accueil&postitPos=" . urlencode($postitPo) . "&postitPart=" . urlencode($postitPa));
exit();
