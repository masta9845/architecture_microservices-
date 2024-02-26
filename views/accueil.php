<?php ob_start(); ?>
<?php

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    $ownedPostIts = json_decode($_GET['postitPos'], true);
    $sharedPostIts = json_decode($_GET['postitPart'], true);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="RUELE Amaury">
    <meta name="author" content="CASSACA Kilian">
    <meta name="author" content="RABHI Sofiene">
    <meta name="author" content="Ridouane OUSMANE DOUDOU">
    <title>Accueil - Vos Post-its</title>
    <link rel="stylesheet" href="public/style.css"> 
</head>
<body>
<marquee class="marque">Bienvenu.e <?= $_SESSION['prenom'] . ' ' . $_SESSION['nom'] ?></marquee>
<main>
    <div id="acceuil">
            <p>Bienvenue.e</p>
    </div>
    <div cl>
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
    </div>
    <div>
        <a href="index.php?action=ajout-postits" class="add-button">
                Ajout de Postits
                <i class="fa-solid fa-plus"></i>
        </a>
    </div>
    <div class="postits-container">
        <div class="postits-section">
            <h2>Vos Post-its Possédés</h2>
            <div class="postit-list">
                <?php foreach ($ownedPostIts as $postit) : ?>
                    <div class="postit">
                            <a href="index.php?action=visualiser-postits&id=<?= $postit['id_postit'] ?>" class="postit-title">
                                    <?= $postit['titre'] ?>
                            </a>
                            <!-- <p> = substr($postit['contenu'], 0, 10) . '...' ?></p> -->
                            <p style="display: none;" class="postit-content-full">
                                <?= $postit['contenu']; ?>
                            </p>
                            <p>Ajouté le <?= $postit['date_creation']; ?></p>
                            <!-- Ajoutez ici des liens pour éditer ou supprimer le post-it -->
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="postits-section">
            <h2>Post-its Partagés avec Vous</h2>
            <div class="postit-list">
                <?php foreach ($sharedPostIts as $postit) : ?>
                    <?php if ($postit['id_owner'] == $_SESSION['user_id']) : ?>
                        <div class="postit">
                        <a href="index.php?action=visualiser-postits&id=<?= $postit['id_postit'] ?>" class="postit-title">
                                <?= $postit['titre'] ?>
                        </a>
                    <?php else : ?>
                        <div class="postit-pasProprio">
                        <a href="index.php?action=visualiser-postits&id=<?= $postit['id_postit'] ?>" class="postit-title">
                                <?= $postit['titre'] ?>
                        </a>
                    <?php endif; ?>
                        
                        <!-- <p> = substr($postit['contenu'], 0, 10) . '...' ?></p> -->
                        <p style="display: none;" class="postit-content-full">
                            <?= $postit['contenu']; ?>
                        </p>
                        <p>Ajouté par <?= $postit['owner_prenom'] . ' ' . $postit['owner_nom']; ?></p>
                        <p>Ajouté le <?= $postit['date_creation']; ?></p>
                        <!-- Ajoutez ici un lien pour visualiser le post-it -->
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</main>
</body>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="public/validation.js"></script>
</html>

<?php
} else {
    // Rediriger vers l'écran de connexion si l'utilisateur n'est pas connecté
    header("Location: index.php");
    exit();
}
?>
<?php $content = ob_get_clean(); ?>
<?php require('template.php') ?>
