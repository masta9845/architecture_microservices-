<?php
// Fonction pour traiter le formulaire de connexion de l'utilisateur
function fonctionConnexion()
{
    // Vérifier si le formulaire de connexion a été soumis
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Inclure le fichier de connexion
        include('config.php');

        // Récupérer les données du formulaire
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $password = $_POST['password'];

        // Valider les données
        if (!$email || !$password) {
            $_SESSION['error_message'] = "Veuillez saisir tous les champs";
            header("Location: index.php?action=connexion");
            exit();
        }

        // Connexion à la base de données MySQL en utilisant les informations du fichier de connexion
        $conn = new mysqli($dbConfig['host'], $dbConfig['username'], $dbConfig['password'], $dbConfig['dbname']);

        // Vérifier la connexion
        if ($conn->connect_error) {
            die('Erreur de connexion à la base de données : ' . $conn->connect_error);
        }

        // Vérifier les informations d'identification de l'utilisateur
        $stmt = $conn->prepare("SELECT id_utilisateur, nom, prenom, mot_de_passe FROM utilisateurs WHERE email = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->bind_result($user_id, $nom, $prenom, $hashed_password);
        $stmt->fetch();
        $stmt->close();

        if (!$user_id) {
            // L'email n'existe pas
            $_SESSION['error_message'] = "Adresse email incorrecte.";
            header("Location: index.php");
            exit();
        }
        // password_verify(htmlspecialchars($_POST['password']), $u['password'])
        if (!password_verify($password, $hashed_password)) {
            // Le mot de passe est incorrect
            $_SESSION['error_message'] = "Mot de passe incorrect.";
            // $_SESSION['error_message'] = "Mot de passe incorrect.";
            header("Location: index.php");
            exit();
        }

        // L'utilisateur est authentifié avec succès
        $_SESSION['user_id'] = $user_id;
        $_SESSION['nom'] = $nom;
        $_SESSION['prenom'] = $prenom;
        //$_SESSION['success_message'] = "Connexion réussie ! Bienvenue, $prenom $nom.";
        // header("Location: index.php?action=accueil");
        header('Location:' . ROOT);
        exit();
    }
}

// Vérifier si l'action est "connexion"
if (isset($_GET['action']) && $_GET['action'] === 'connexion') {
    fonctionConnexion();
} else {
    // Si l'action n'est pas spécifiée ou n'est pas "connexion", rediriger vers la page de connexion
    header("Location: index.php");
    exit();
}
