<?php
session_start();
define('WEBROOT', str_replace('index.php', '', $_SERVER['SCRIPT_FILENAME']));   // chemin absolue depuis la racine du serveur
define('ROOT', str_replace('index.php', '', $_SERVER['SCRIPT_NAME']));  // chemin depuis la racine du site
try {
    if (!isset($_SESSION['user-id'])) { // si la personne n'est pas encore connectée
        if (isset($_GET['action']) && $_GET['action'] === 'connexion-check') {
            // si la personne vient de se connecter
            require(WEBROOT . '/controllers/ConnexionController.php');
        }else if(isset($_GET['action']) && $_GET['action'] === 'inscription'){
            $title = 'Inscription';
            require(WEBROOT . '/views/inscription.php');
        }else if(isset($_GET['action']) && $_GET['action'] === 'connexion'){
            $title = 'Inscription';
            require(WEBROOT . '/views/connexion.php');
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
            } else if ($_GET['action'] === 'inscription') {
                $title = 'Inscription';
                require(WEBROOT . '/views/inscription.php');
            } else if ($_GET['action'] === 'admin') {
                // si le role n'est pas Responsable, on jette une exception not authorized
                if (strtolower($_SESSION['role']) !== 'responsable') {
                    throw new Exception("Error 403 : Not authorized");
                }
                $title = 'Administration';
                require(WEBROOT . '/controllers/AdministrationController.php');
            } else if ($_GET['action'] === 'ajax' && isset($_GET['search'])) {
                require(WEBROOT . '/controllers/AjaxRequestController.php');
            } else if (preg_match('/^edt-(delete|add|edit)$/', $_GET['action'])) {
                // si le role n'est pas Responsable ou coordinateur, on jette une exception not authorized
                if (strtolower($_SESSION['role']) !== 'responsable' && strtolower($_SESSION['role']) !== 'coordinateur') {
                    throw new Exception("Error 403 : Not authorized");
                }
                require(WEBROOT . '/controllers/EdtController.php');
            } else {
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
