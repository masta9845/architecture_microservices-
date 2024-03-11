<?php
session_start();
define('WEBROOT', str_replace('index.php', '', $_SERVER['SCRIPT_FILENAME']));   // chemin absolue depuis la racine du serveur
define('ROOT', str_replace('index.php', '', $_SERVER['SCRIPT_NAME']));  // chemin depuis la racine du site
try {
    if (!isset($_SESSION['user_id'])) { // si la personne n'est pas encore connectée
        if (isset($_GET['action']) && $_GET['action'] === 'connexion') {
            // si la personne essaye de se connecter
            require(WEBROOT . '/Controllers/loginController.php');
        } else if (isset($_GET['action']) && $_GET['action'] === 'inscription') {
            //renvoie vers la page d'inscription
            $title = 'Inscription';
            require(WEBROOT . '/views/inscription.php');
        } else if (isset($_GET['action']) && $_GET['action'] === 'insert-utilisateur') {
            //Sauvegarde des information d'inscription
            $title = 'Inscription';
            require(WEBROOT . '/Controllers/inscriptionController.php');
        } else {
            //par defaut renvoie a la page de connexion
            $title = 'Connexion';
            require(WEBROOT . '/views/Connexion.php');
        }
    } else {
        if (isset($_GET['action']) && $_GET['action'] !== '') {
            if ($_GET['action'] === 'deconnect') {
                //deconnection
                session_destroy();
                header('Location:' . ROOT);
                exit();
            } else if ($_GET['action'] === 'ajout-postits') {
                //Renvoie vers la page d'ajout de post'it
                $title = 'Ajout';
                require(WEBROOT . '/views/ajoutpostits.php');
            } else if ($_GET['action'] === 'insert-postit') {
                //Enregistrer le post'it crée 
                $title = 'Insertion';
                require(WEBROOT . '/Controllers/postitsController.php');
            } else if ($_GET['action'] === 'visualiser-postits') {
                //fait appel a la fonction qui permet d'afficher le post'it selection
                $title = 'Visualiser';
                require(WEBROOT . '/Controllers/postitsController.php');
            } elseif ($_GET['action'] === 'VisualiserPostit') {
                //Renvoie vers la page de visualisation du post'it selectionner 
                $title = 'Visualiser';
                require(WEBROOT . '/views/visualisationPostits.php');
            } else if ($_GET['action'] === 'modif-postit') {
                //Fait appel a la fonction qui permet de modifier le post'it 
                $title = 'Modifier';
                require(WEBROOT . '/Controllers/postitsController.php');
            } else if ($_GET['action'] === 'afficheInfo-postit-modif') {
                //Affiche la page de modification avec les champs pre-rempli avec les informations du post'it a modifier
                $title = 'Modifier';
                require(WEBROOT . '/views/modifierPostits.php');
            } else if ($_GET['action'] === 'mod-valid-postit') {
                //Insertion des valeurs a modifier dans le post'it
                $title = 'Insertion Modification';
                require(WEBROOT . '/Controllers/postitsController.php');
            } else if ($_GET['action'] === 'supp-postit') {
                //Suppression d'un post'it
                $title = 'Suppression';
                require(WEBROOT . '/Controllers/postitsController.php');
            } else if ($_GET['action'] === 'accueil') {
                //Renvoie vers la page d'accueil
                $title = 'Accueil';
                require(WEBROOT . '/views/accueil.php');
            } else {
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
