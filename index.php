<?php
session_start();
define('WEBROOT', str_replace('index.php', '', $_SERVER['SCRIPT_FILENAME']));   // chemin absolue depuis la racine du serveur
define('ROOT', str_replace('index.php', '', $_SERVER['SCRIPT_NAME']));  // chemin depuis la racine du site
try {
    if (!isset($_SESSION['user_id'])) { // si la personne n'est pas encore connectée
        if (isset($_GET['action']) && $_GET['action'] === 'connexion') {
            // si la personne essaye de se connecter
            require(WEBROOT . '/Controllers/loginController.php');
        }else if(isset($_GET['action']) && $_GET['action'] === 'inscription'){
            $title = 'Inscription';
            require(WEBROOT . '/views/inscription.php');
        } else if(isset($_GET['action']) && $_GET['action'] === 'insert-utilisateur') {
            $title = 'Inscription';
            require(WEBROOT . '/Controllers/inscriptionController.php');
        }else {
            $title = 'Connexion';
            require(WEBROOT . '/views/Connexion.php');
        }
    } else {
        if (isset($_GET['action']) && $_GET['action'] !== '') {
            if ($_GET['action'] === 'deconnect') {
                session_destroy();
                header('Location:' . ROOT);
                exit();
            }else if($_GET['action'] === 'ajout-postits') {
                $title = 'Ajout';
                require(WEBROOT . '/views/ajoutpostits.php');
            }else if($_GET['action'] === 'insert-postit') {
                $title = 'Insertion';
                require(WEBROOT . '/Controllers/postitsController.php');
            }else if($_GET['action'] === 'visualiser-postits') {
                $title = 'Visualiser';
                require(WEBROOT . '/Controllers/postitsController.php');
            }elseif ($_GET['action'] === 'VisualiserPostit') {
                $title = 'Visualiser';
                require(WEBROOT . '/views/visualisationPostits.php');
            }else if($_GET['action'] === 'modif-postit') {
                $title = 'Modifier';
                require(WEBROOT . '/Controllers/postitsController.php');
            }else if($_GET['action'] === 'afficheInfo-postit-modif') {
                $title = 'Modifier';
                require(WEBROOT . '/views/modifierPostits.php');
            }else if($_GET['action'] === 'mod-valid-postit') {
                $title = 'Insertion Modification';
                require(WEBROOT . '/Controllers/postitsController.php');
            }else if($_GET['action'] === 'supp-postit') {
                $title = 'Suppression';
                require(WEBROOT . '/Controllers/postitsController.php');
            }else if($_GET['action'] === 'accueil') {
                $title = 'Accueil';
                require(WEBROOT . '/views/accueil.php');
            }else {
                throw new Exception("Error 404 : Page not found");
            }
        } else {
            // Par defaut: page d'accueuil
            $title = 'Accueil';
            require(WEBROOT . '/Controllers/accueilControllers.php');
        }
    }
} catch (Exception $e) {
    require(WEBROOT . '/views/error.php');
}
