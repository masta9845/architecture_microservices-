<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Fonction pour appeler un service avec des données
function fetchFromService($url, $data)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        error_log('Erreur CURL : ' . curl_error($ch));
    }

    curl_close($ch);
    return json_decode($response, true);
}

// Vérification des actions
if (isset($_GET['action'])) {
    switch ($_GET['action']) {

        // Cas d'ajout d'un post-it
        case 'insert-postit':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (!isset($_POST['titre'], $_POST['contenu'], $_SESSION['user_id'])) {
                    $_SESSION['error_message'] = 'Données manquantes pour créer un post-it.';
                    header('Location: index.php?action=accueilControllers');
                    exit();
                }

                $data = [
                    'id_owner' => $_SESSION['user_id'],
                    'titre' => trim($_POST['titre']),
                    'contenu' => trim($_POST['contenu']),
                    'shared_users' => isset($_POST['utilisateurs']) ? $_POST['utilisateurs'] : []
                ];

                $url = 'http://postit-service:8002/?action=add_postit';
                $response = fetchFromService($url, $data);

                if (isset($response['success']) && $response['success']) {
                    $_SESSION['success_message'] = 'Post-it ajouté avec succès.';
                } else {
                    $_SESSION['error_message'] = $response['message'] ?? 'Erreur lors de l\'ajout du post-it.';
                }

                header('Location: index.php?action=accueilControllers');
                exit();
            }
            break;

        // Cas de modification d'un post-it
        case 'modif-postit':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (!isset($_POST['id_postit'], $_POST['titre'], $_POST['contenu'], $_SESSION['user_id'])) {
                    $_SESSION['error_message'] = 'Données manquantes pour modifier le post-it.';
                    header('Location: index.php?action=accueilControllers');
                    exit();
                }

                $data = [
                    'id_postit' => $_POST['id_postit'],
                    'id_owner' => $_SESSION['user_id'],
                    'titre' => trim($_POST['titre']),
                    'contenu' => trim($_POST['contenu']),
                    'shared_users' => isset($_POST['utilisateurs']) ? $_POST['utilisateurs'] : []
                ];

                $url = 'http://postit-service:8002/?action=update_postit';
                $response = fetchFromService($url, $data);

                if (isset($response['success']) && $response['success']) {
                    $_SESSION['success_message'] = 'Post-it modifié avec succès.';
                } else {
                    $_SESSION['error_message'] = $response['message'] ?? 'Erreur lors de la modification du post-it.';
                }

                header('Location: index.php?action=accueilControllers');
                exit();
            }
            break;

        // Cas de récupération des utilisateurs pour partage
        case 'ajout-postits':
            $url = 'http://postit-service:8002/?action=get_all_users';
            $data = ['user_id' => $_SESSION['user_id']];
            $response = fetchFromService($url, $data);

            if (isset($response['success']) && $response['success']) {
                $_SESSION['all_users'] = $response['data'];
            } else {
                $_SESSION['error_message'] = $response['message'] ?? 'Erreur lors de la récupération des utilisateurs.';
                $_SESSION['all_users'] = [];
            }

            require(WEBROOT . '/views/ajoutpostits.php');
            exit();

        // Cas de suppression d'un post-it
        case 'supp-postit':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (!isset($_POST['id_postit'], $_SESSION['user_id'])) {
                    $_SESSION['error_message'] = 'Données manquantes pour supprimer le post-it.';
                    header('Location: index.php?action=accueilControllers');
                    exit();
                }

                $data = [
                    'id_postit' => $_POST['id_postit'],
                    'id_owner' => $_SESSION['user_id']
                ];

                $url = 'http://postit-service:8002/?action=delete_postit';
                $response = fetchFromService($url, $data);

                if (isset($response['success']) && $response['success']) {
                    $_SESSION['success_message'] = 'Post-it supprimé avec succès.';
                } else {
                    $_SESSION['error_message'] = $response['message'] ?? 'Erreur lors de la suppression du post-it.';
                }

                header('Location: index.php?action=accueilControllers');
                exit();
            }
            break;

        // Cas de visualisation d'un post-it
        case 'visualiser-postits':
            if (isset($_GET['id'])) {
                $data = [
                    'id_postit' => $_GET['id'],
                    'user_id' => $_SESSION['user_id']
                ];

                $url = 'http://postit-service:8002/?action=get_postit';
                $response = fetchFromService($url, $data);

                if (isset($response['success']) && $response['success']) {
                    $_SESSION['postit_details'] = $response['data'];
                    header('Location: index.php?action=visualisation');
                } else {
                    $_SESSION['error_message'] = $response['message'] ?? 'Erreur lors de la récupération du post-it.';
                    header('Location: index.php?action=accueilControllers');
                }
                exit();
            }
            break;

        default:
            $_SESSION['error_message'] = 'Action non reconnue.';
            header('Location: index.php?action=accueilControllers');
            exit();
    }
} else {
    header('Location: index.php?action=accueilControllers');
    exit();
}
