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
        <?php
        if (isset($_SESSION['success_message'])) {
            echo '<p class="success">' . $_SESSION['success_message'] . '</p>';
            unset($_SESSION['success_message']);
            }
        if (isset($_SESSION['error_message'])) {
            echo '<p class="error">' . $_SESSION['error_message'] . '</p>';
            unset($_SESSION['error_message']);
            }
        ?>
        <form action="index.php?action=connexion" method="POST" id="connexions-form">
            <?php
            if (isset($_SESSION['error-msg'])) {
                echo '<span class="error-msg">' . $_SESSION['error-msg'] . '</span>';
                unset($_SESSION['error-msg']);
            }
            ?>
                <div>
                    <label>Email:</label>
                    <input type="text" name="email" autofocus>
                </div>
                <div>
                    <label>Mot de passe:</label>
                    <input type="password" name="password">
                </div>
                <input type="submit" name="submit" value="Login">
        </form>
        <p>Vous n'avez pas de compte ? <a href="index.php?action=inscription">Inscrivez-vous ici</a>.</p>
    </div>
</main>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="public/validation-jquery.js"></script>
</body>
</html>