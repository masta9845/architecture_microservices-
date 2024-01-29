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
            }else {
                throw new Exception("Error 404 : Page not found");
            }
        } else {
            // Par defaut: page d'accueuil
            $title = 'Accueil';
            require(WEBROOT . '/views/accueil.php');
        }
    }
} catch (Exception $e) {
    require(WEBROOT . '/views/error.php');
}
