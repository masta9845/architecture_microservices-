<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Gestionnaire de Post-its</title>
    <link rel="stylesheet" href="public/style.css">
    <meta name="author" content="RUELE Amaury">
    <meta name="author" content="CASSACA Kilian">
    <meta name="author" content="RABHI Sofiene">
    <meta name="author" content="Ridouane OUSMANE DOUDOU">
</head>

<body>
    <main>
        <div class="background-image"></div>
        <div class="inscription-form">
            <h1>Inscription</h1>

            <?php if (isset($_SESSION['error_message'])) {
                echo '<p class="error">' . $_SESSION['error_message'] . '</p>';
                unset($_SESSION['error_message']);
            }
            ?>

            <form action="index.php?action=insert-utilisateur" method="post" id="inscription-form">
                <label for="nom">Nom :</label>
                <input type="text" id="nom" name="nom">

                <label for="prenom">Prénom :</label>
                <input type="text" id="prenom" name="prenom">

                <label for="email">Email :</label>
                <input type="text" id="email" name="email">

                <label for="date_naissance">Date de naissance :</label>
                <input type="text" id="date_naissance" name="date_naissance" placeholder="AAAA/MM/JJ">

                <label for="password">Mot de passe :</label>
                <input type="password" id="password" name="password">

                <label for="password-confirm">Confirmation du Mot de passe :</label>
                <input type="password" id="password-confirm" name="password-confirm">

                <input type="submit" value="S'inscrire">
            </form>

            <p>Vous avez déjà un compte ? <a href="index.php">Connectez-vous ici</a>.</p>
        </div>

    </main>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="public/validation-jquery.js"></script>
</body>

</html>
