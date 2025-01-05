<?php
session_start();

// Définir WEBROOT pour utiliser un chemin absolu basé sur le répertoire courant
define('WEBROOT', __DIR__); // Utilise le chemin absolu du dossier actuel
define('ROOT', str_replace('index.php', '', $_SERVER['SCRIPT_NAME'])); // Chemin depuis la racine du site

try {
    if (!isset($_SESSION['user_id'])) { // Si l'utilisateur n'est pas connecté
        if (isset($_GET['action']) && $_GET['action'] === 'connexion') {
            // L'utilisateur essaie de se connecter
            require(WEBROOT . '/Controllers/loginController.php');
        } elseif (isset($_GET['action']) && $_GET['action'] === 'inscription') {
            // Redirige vers la page d'inscription
            require(WEBROOT . '/views/inscription.php');
        } elseif (isset($_GET['action']) && $_GET['action'] === 'insert-utilisateur') {
            // Appelle le contrôleur pour gérer l'inscription
            require(WEBROOT . '/Controllers/inscriptionController.php');
        } else {
            // Par défaut, redirige vers la page de connexion
            require(WEBROOT . '/views/Connexion.php');
        }
    } else {
        if (isset($_GET['action']) && $_GET['action'] !== '') {
            switch ($_GET['action']) {
                case 'deconnect':
                    // Déconnexion
                    session_destroy();
                    header('Location:' . ROOT);
                    exit();
                case 'accueilControllers': // Ajout de cette gestion
                    require(WEBROOT . '/Controllers/accueilControllers.php');
                    break;
                /*case 'ajout-postits':
                    require(WEBROOT . '/views/ajoutpostits.php');
                    break;*/
                case 'ajout-postits':
                    require(WEBROOT . '/Controllers/postitsController.php');
                    break;

                case 'insert-postit':
                    // Appelle le contrôleur pour gérer l'ajout d'un post-it
                    require(WEBROOT . '/Controllers/postitsController.php');
                    break;

                case 'supp-postit':
                    // Suppression d'un post-it
                    require(WEBROOT . '/Controllers/postitsController.php');
                    break;

                case 'visualiser-postits':
                    // Redirige vers la vue de visualisation des post-its
                    require(WEBROOT . '/views/visualisationPostits.php');
                    break;

                case 'modif-postit':
                    // Gestion de la modification d'un post-it
                    require(WEBROOT . '/views/modifierPostits.php');
                    break;

                case 'accueil':
                    // Redirige vers la page d'accueil
                    require(WEBROOT . '/views/accueil.php');
                    break;

                default:
                    throw new Exception("Erreur 404 : Page non trouvée");
            }
        } else {
            // Par défaut : redirige vers la page d'accueil
            require(WEBROOT . '/Controllers/accueilControllers.php');
        }
    }
} catch (Exception $e) {
    // Affiche une page d'erreur en cas d'exception
    require(WEBROOT . '/views/error.php');
}
