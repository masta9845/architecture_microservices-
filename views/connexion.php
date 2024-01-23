<?php

// Vérifier si l'utilisateur est déjà connecté
if (isset($_SESSION['user_id'])) {
    header("Location: ./views/accueil.php");
    exit();
}

// // Vérifier si le formulaire de connexion a été soumis
// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     // Récupérer les données du formulaire
//     $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
//     $password = $_POST['password'];

//     // Valider les données
//     if (!$email || !$password) {
//         $_SESSION['error_message'] = "Veuillez saisir tous les champs.";
//         header("Location: index.php");
//         exit();
//     }

//     // Connexion à la base de données SQLite (assurez-vous que le fichier de base de données existe)
//     $db = new SQLite3('chemin/vers/votre/base/de/donnees.sqlite');

//     // Préparez la requête SQL
//     $stmt = $db->prepare("SELECT id, mot_de_passe FROM utilisateurs WHERE email = :email");
//     $stmt->bindParam(':email', $email, SQLITE3_TEXT);

//     // Exécutez la requête
//     $result = $stmt->execute();

//     // Vérifiez si l'utilisateur existe dans la base de données
//     $user = $result->fetchArray(SQLITE3_ASSOC);
//     if ($user && password_verify($password, $user['mot_de_passe'])) {
//         // Connexion réussie, enregistrez l'ID de l'utilisateur dans la session
//         $_SESSION['user_id'] = $user['id'];
//         header("Location: accueil.php");
//         exit();
//     } else {
//         $_SESSION['error_message'] = "Les informations d'identification sont incorrectes.";
//         header("Location: index.php");
//         exit();
//     }
// }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta name="description" content="Gestion d'emploi du temps"/>
    <meta charset="utf-8">
    <title>Connexion</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="RUELE Amaury">
    <meta name="author" content="CASSACA Kilian">
    <meta name="author" content="RABHI Sofiene">
    <meta name="author" content="Ridouane OUSMANE DOUDOU">
    <link rel="stylesheet" href="public/style.css">
</head>

<body>
<main>
    <div class="background-image"></div>
    <div class="connexion-form">
        <h1>Connexion</h1>
        <form action="index.php?action=login-check" method="POST" class="form-card">
            <?php
            if (isset($_SESSION['error-msg'])) {
                echo '<span class="error-msg">' . $_SESSION['error-msg'] . '</span>';
                unset($_SESSION['error-msg']);
            }
            ?>
                <div>
                    <label>Email:</label>
                    <input type="email" name="email" required autofocus>
                </div>
                <div>
                    <label>Mot de passe:</label>
                    <input type="password" name="password" required>
                </div>
                <input type="submit" name="submit" value="Login">
        </form>
        <p>Vous n'avez pas de compte ? <a href="index.php?action=inscription">Inscrivez-vous ici</a>.</p>
    </div>
</main>
</body>
</html>