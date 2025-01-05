<?php
function fonctionInscription()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = [
            'nom' => strtoupper(trim($_POST['nom'] ?? '')), // Gestion des valeurs manquantes
            'prenom' => ucfirst(trim($_POST['prenom'] ?? '')),
            'email' => trim($_POST['email'] ?? ''),
            'password' => trim($_POST['password'] ?? ''),
            'date_naissance' => trim($_POST['date_naissance'] ?? '') // Ajout de la date de naissance
        ];

        // Debug : Vérifions les données envoyées
        file_put_contents('php://stderr', "Données du formulaire : " . json_encode($data) . "\n");

        // Vérifions que toutes les données nécessaires sont présentes
        if (empty($data['nom']) || empty($data['prenom']) || empty($data['email']) || empty($data['password']) || empty($data['date_naissance'])) {
            $_SESSION['error_message'] = "Tous les champs sont requis.";
            header("Location: index.php?action=inscription");
            exit();
        }

        $ch = curl_init('http://user-service:8001/?action=register'); 
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        $response = curl_exec($ch);

        // Vérifions si curl a rencontré une erreur
        if (curl_errno($ch)) {
            $_SESSION['error_message'] = "Erreur de communication avec le service d'inscription : " . curl_error($ch);
            curl_close($ch);
            header("Location: index.php?action=inscription");
            exit();
        }

        curl_close($ch);

        // Debug : Vérifions la réponse
        file_put_contents('php://stderr', "Réponse user-service : " . $response . "\n");

        $result = json_decode($response, true);

        // Vérifions si la réponse est valide et contient les clés attendues
        if (is_array($result) && isset($result['success'])) {
            if ($result['success']) {
                $_SESSION['success_message'] = $result['message'];
                header("Location: index.php");
                exit();
            } else {
                $_SESSION['error_message'] = $result['message'];
                header("Location: index.php?action=inscription");
                exit();
            }
        } else {
            $_SESSION['error_message'] = "Réponse inattendue du service d'inscription.";
            header("Location: index.php?action=inscription");
            exit();
        }
    }
}

fonctionInscription();
