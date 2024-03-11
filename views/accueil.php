<?php ob_start(); ?>
<?php
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    $ownedPostIts = json_decode($_GET['postitPos'], true);
    $sharedPostIts = json_decode($_GET['postitPart'], true);
?>

    <marquee class="marque">Bienvenu.e <?= $_SESSION['prenom'] . ' ' . $_SESSION['nom'] ?></marquee>
    <main>
        <div id="acceuil">
            <p>Bienvenue.e</p>
        </div>
        <div>
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
        <link rel="stylesheet" href="public/style.css">
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
                            <p><?= date('d/m/Y', strtotime($postit['date_creation'])); ?></p>
                            <div class="button-group-vis">
                                <a href="index.php?action=modif-postit&id=<?= $postit['id_postit'] ?>" class="button-vis-mod" id="acc-mod">Modifier</a>
                                <form action="index.php?action=supp-postit" method="post" style="display: inline;">
                                    <input type="hidden" name="id_postit" value="<?= $postit['id_postit'] ?>">
                                    <button type="submit" class="button-vis-sup" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce post-it ?')">Supprimer</button>
                                </form>
                            </div>
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
                        <p><?= date('d/m/Y', strtotime($postit['date_creation'])); ?></p>
                        <p><?= $postit['owner_prenom']; ?></p>
                    <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </main>

<?php
} else {
    // Rediriger vers l'écran de connexion si l'utilisateur n'est pas connecté
    header("Location: index.php");
    exit();
}
?>
<?php $content = ob_get_clean(); ?>
<?php $title = "Accueil - Vos Post-its"; ?>
<?php require('template.php'); ?>
