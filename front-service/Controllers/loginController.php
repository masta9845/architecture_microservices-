<?php
function fonctionConnexion()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = [
            'email' => $_POST['email'] ?? '',
            'password' => $_POST['password'] ?? ''
        ];

        // Debug : Log des données envoyées
        file_put_contents('php://stderr', "Données du formulaire : " . json_encode($data) . "\n");

        $ch = curl_init('http://user-service:8001/?action=login');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        $response = curl_exec($ch);
        $curl_error = curl_error($ch);
        curl_close($ch);

        // Debug : Log de la réponse
        file_put_contents('php://stderr', "Réponse user-service : $response\n");

        if ($curl_error) {
            $_SESSION['error_message'] = "Erreur de communication avec le service de connexion : $curl_error";
            header("Location: index.php?action=connexion");
            exit();
        }

        $result = json_decode($response, true);

        if ($result['success']) {
            
            $_SESSION['user_id'] = $result['data']['id'];
            $_SESSION['nom'] = $result['data']['nom'];
            $_SESSION['prenom'] = $result['data']['prenom'];
            $_SESSION['email'] = $result['data']['email'];
    
            // Rediriger vers le contrôleur d'accueil
            header('Location: index.php?action=accueilControllers');
            exit();
        }  else {
            $_SESSION['error_message'] = $result['message'] ?? "Une erreur inconnue est survenue.";
            header("Location: index.php?action=connexion");
            exit();
        }
    }
}

fonctionConnexion();
