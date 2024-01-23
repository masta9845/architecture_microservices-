<?php
// Vérifier si l'utilisateur est déjà connecté
if (isset($_SESSION['user_id'])) {
    header("Location: accueil.php");
    exit();
}

// Vérifier si le formulaire d'inscription a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $date_naissance = $_POST['date_naissance'];

    // Valider les données
    if (!$email || !$password || !$nom || !$prenom || !$date_naissance) {
        $_SESSION['error_message'] = "Veuillez saisir tous les champs.";
        header("Location: inscription.php");
        exit();
    }

    // Connexion à la base de données SQLite
    $db = new SQLite3('chemin/vers/votre/base/de/donnees.sqlite');

    // Vérifier si l'adresse email est déjà utilisée
    $stmt = $db->prepare("SELECT COUNT(*) FROM utilisateurs WHERE email = :email");
    $stmt->bindParam(':email', $email, SQLITE3_TEXT);
    $result = $stmt->execute();
    $count = $result->fetchArray(SQLITE3_NUM)[0];

    if ($count > 0) {
        $_SESSION['error_message'] = "Cette adresse email est déjà utilisée.";
        header("Location: inscription.php");
        exit();
    }

    // Insérer l'utilisateur dans la base de données
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $db->prepare("INSERT INTO utilisateurs (email, mot_de_passe, nom, prenom, date_naissance) 
                         VALUES (:email, :mot_de_passe, :nom, :prenom, :date_naissance)");
    $stmt->bindParam(':email', $email, SQLITE3_TEXT);
    $stmt->bindParam(':mot_de_passe', $hashed_password, SQLITE3_TEXT);
    $stmt->bindParam(':nom', $nom, SQLITE3_TEXT);
    $stmt->bindParam(':prenom', $prenom, SQLITE3_TEXT);
    $stmt->bindParam(':date_naissance', $date_naissance, SQLITE3_TEXT);
    $stmt->execute();

    $_SESSION['success_message'] = "Inscription réussie ! Vous pouvez maintenant vous connecter.";
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Gestionnaire de Post-its</title>
    <link rel="stylesheet" href="public/style.css">
</head>
<body>
<main>
    <div class="background-image"></div>
    <div class="inscription-form">
        <h1>Inscription</h1>

        <?php if (isset($_SESSION['error_message'])) : ?>
            <p class="error"><?php echo $_SESSION['error_message']; ?></p>
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>

        <form action="" method="post" id="inscription-form">
                <label for="nom">Nom :</label>
                <input type="text" id="nom" name="nom">

                <label for="prenom">Prénom :</label>
                <input type="text" id="prenom" name="prenom">

                <label for="email">Email :</label>
                <input type="text" id="email" name="email">

                <label for="date_naissance">Date de naissance :</label>
                <input type="date" id="date_naissance" name="date_naissance">

                <label for="password">Mot de passe :</label>
                <input type="password" id="password" name="password">

                <label for="password-fonfirm">Confirmation du Mot de passe :</label>
                <input type="password" id="password-confirm" name="password-confirm">

                <input type="submit" value="S'inscrire">
        </form>

        <p>Vous avez déjà un compte ? <a href="index.php?action=connexion">Connectez-vous ici</a>.</p>
    </div>

</main>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="public/validation-jquery.js"></script>
</body>
</html>

