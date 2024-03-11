<?php ob_start(); ?>
<?php
// Vérifier si l'utilisateur est connecté
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];

    // Récupérer les post-its possédés
    $postit_aff = json_decode($_GET['postit_aff'], true);
    $id_postit = $_GET['id'];
?>
<link rel="stylesheet" href="public/styles.css">
<marquee class="marque">Bienvenu.e <?= $_SESSION['prenom'] . ' ' . $_SESSION['nom'] ?></marquee>
<div class="container-vis-post">
    <?php if ($postit_aff[0]['id_owner'] == $userId) : ?>
        <div class="postit-vis yellow">
            <i class="pin"></i>
            <h2><?= $postit_aff[0]['titre'] ?></h2>
            <p><?= $postit_aff[0]['contenu'] ?></p>
            <p>Date de création : <?= date('d/m/Y', strtotime($postit_aff[0]['date_creation'])); ?></p>
            <div class="button-group-vis">
                <a href="index.php?action=modif-postit&id=<?= $id_postit ?>" class="button-vis-mod">Modifier</a>
                <form action="index.php?action=supp-postit" method="post">
                    <input type="hidden" name="id_postit" value="<?= $id_postit ?>">
                    <button type="submit" class="button-vis-sup" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce post-it ?')">Supprimer</button>
                </form>
            </div>
        </div>
    <?php else : ?>
        <div class="postit-vis green">
            <i class="pin"></i>
            <h2><?= $postit_aff[0]['titre'] ?></h2>
            <p><?= $postit_aff[0]['contenu'] ?></p>
            <p>Date de création : <?= date('d/m/Y', strtotime($postit_aff[0]['date_creation'])); ?></p>
            <p><?= $postit_aff[0]['owner_prenom'] ?></p>
        </div>
    <?php endif; ?>
</div>

<?php
} else {
    // Rediriger vers l'écran de connexion si l'utilisateur n'est pas connecté
    header("Location: index.php");
    exit();
}
?>
<?php $content = ob_get_clean(); ?>
<?php $title = "Visualiser Post-its"; ?>
<?php require('template.php'); ?>