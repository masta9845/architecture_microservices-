<?php ob_start(); ?>
<?php
// Vérifier si l'utilisateur est connecté
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];

    // Récupérer les post-its possédés
    $postit_aff = json_decode($_GET['postit_aff'], true);
    $id_postit = $_GET['id'];
?>
    <!DOCTYPE html>
    <html lang="fr">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Affichage du Post-it</title>
        <meta name="author" content="RUELE Amaury">
        <meta name="author" content="CASSACA Kilian">
        <meta name="author" content="RABHI Sofiene">
        <meta name="author" content="Ridouane OUSMANE DOUDOU">
        <link rel="stylesheet" href="styles.css">
    </head>

    <body>
        <div class="container-vis-post">
            <div class="postit-vis">
                <h2><?= $postit_aff[0]['titre'] ?></h2>
                <?= $postit_aff[0]['contenu'] ?></p>
                <p>Date de création : <?= $postit_aff[0]['date_creation'] ?></p>
                <div class="button-group-vis">
                    <a href="index.php?action=modif-postit&id=<?= $id_postit ?>" class="button-vis-mod">Modifier</a>
                    <form action="index.php?action=supp-postit" method="post">
                        <input type="hidden" name="id_postit" value="<?= $id_postit ?>">
                        <button type="submit" class="button-vis-sup" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce post-it ?')">Supprimer</button>
                    </form>
                </div>
            </div>
        </div>
    </body>

    </html>

<?php
} else {
    // Rediriger vers l'écran de connexion si l'utilisateur n'est pas connecté
    header("Location: index.php");
    exit();
} ?>
<?php $content = ob_get_clean(); ?>
<?php require('template.php') ?>