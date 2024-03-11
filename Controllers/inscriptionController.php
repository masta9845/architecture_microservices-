<?php
// Fonction pour traiter le formulaire d'inscription de l'utilisateur
function fonctionInscription()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        include('config.php'); // Inclure le fichier de connexion a la base 
        // Récupérer les données du formulaire
        $nom = strtoupper($_POST['nom']);
        $prenom = ucfirst($_POST['prenom']);
        $date_naissance = $_POST['date_naissance'];
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

        // Valider les données
        if (!$email || !$password || !$nom || !$prenom || !$date_naissance) {
            $_SESSION['error_message'] = "Veuillez saisir tous les champs. ";
            header("Location: index.php?action=inscription");
            exit();
        }

        $conn = new mysqli($dbConfig['host'], $dbConfig['username'], $dbConfig['password'], $dbConfig['dbname']);

        // Vérifier la connexion
        if ($conn->connect_error) {
            die('Erreur de connexion à la base de données : ' . $conn->connect_error);
        }

        // Vérifier si l'adresse email est déjà utilisée
        $stmt = $conn->prepare("SELECT COUNT(*) FROM utilisateurs WHERE email = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        if ($count > 0) {
            $_SESSION['error_message'] = "Cette adresse email est déjà utilisée.";
            header("Location: index.php?action=inscription");
            exit();
        }

        // Insérer l'utilisateur dans la base de données
        $stmt = $conn->prepare("INSERT INTO utilisateurs (nom, prenom, email, date_de_naissance, mot_de_passe) 
                             VALUES ( ?, ?, ?, ?, ?)");
        echo $password;
        $stmt->bind_param('sssss', $nom, $prenom, $email, $date_naissance, $password);
        $stmt->execute();
        $stmt->close();

        $_SESSION['success_message'] = "Inscription réussie ! Vous pouvez maintenant vous connecter.";
        header("Location: index.php");
        exit();
    }
}

// Vérifier si l'action est "insert-utilisateur"
if (isset($_GET['action']) && $_GET['action'] === 'insert-utilisateur') {
    fonctionInscription();
} else {
    // Si l'action n'est pas spécifiée ou n'est pas "insert-utilisateur", rediriger vers la page d'inscription
    header("Location: index.php?action=inscription");
    exit();
}
