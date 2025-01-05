<?php

// Démarrer la session uniquement si elle n'est pas déjà active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Fonction générique pour appeler un service
function fetchFromService($url, $data)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}

// Récupération des post-its personnels et partagés
function getOwnedPostIts($userId)
{
    $url = 'http://postit-service:8002/?action=owned_postits';
    return fetchFromService($url, ['user_id' => $userId]);
}

function getSharedPostIts($userId)
{
    $url = 'http://postit-service:8002/?action=shared_postits';
    return fetchFromService($url, ['user_id' => $userId]);
}

// Vérification de l'utilisateur connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php?action=connexion');
    exit();
}

$user_id = $_SESSION['user_id'];

// Appels aux services pour récupérer les post-its
$ownedPostIts = getOwnedPostIts($user_id);
$sharedPostIts = getSharedPostIts($user_id);

// Stocker les résultats dans les sessions
if (isset($ownedPostIts['success'], $sharedPostIts['success']) && $ownedPostIts['success'] && $sharedPostIts['success']) {
    $_SESSION['postitPos'] = $ownedPostIts['data'];
    $_SESSION['postitPart'] = $sharedPostIts['data'];
} else {
    $_SESSION['error_message'] = 'Erreur lors de la récupération des post-its.';
}

// Rediriger vers la page d'accueil
header("Location: index.php?action=accueil");
exit();
