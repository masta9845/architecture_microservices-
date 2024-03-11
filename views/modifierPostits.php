<?php ob_start(); ?>
<?php
// Vérifier si l'utilisateur est connecté
if (isset($_SESSION['user_id'])) {
    $id_postit = $_GET['id'];
    // Récupérer les détails du post-it à modifier
    $postit_details = json_decode($_GET['postit_details'], true);

    // Récupérer les utilisateurs avec lesquels le post-it est partagé
    $shared_users = json_decode($_GET['shared_users'], true);
    //var_dump($shared_users);
    $result_users = json_decode($_GET['result_users'], true);
} else {
    // Rediriger si l'utilisateur n'est pas connecté
    header("Location: index.php");
    exit();
}
?>
<link rel="stylesheet" href="public/styles.css">
<marquee class="marque">Bienvenu.e <?= $_SESSION['prenom'] . ' ' . $_SESSION['nom'] ?></marquee>
<div class="container">
    <h1>Modifier le Post-it</h1>
    <form id="form-postit" action="index.php?action=mod-valid-postit" method="POST">
        <div class="form-group">
            <label for="titre">Titre :</label><br>
            <input type="text" id="titre" name="titre" value="<?= $postit_details['titre'] ?>" required><br>
        </div>
        <div class="form-group">
            <label for="contenu">Contenu :</label><br>
            <textarea id="contenu" name="contenu" required><?= $postit_details['contenu'] ?></textarea><br>
        </div>
        <div class="form-group">
            <label for="searchUser">Partager avec :</label><br>
            <input type="text" id="searchUser" placeholder="Rechercher un utilisateur">
            <ul id="listutilisateurs" class="list-utilisateurs">
                <?php

                if (!empty($result_users)) {
                    foreach ($result_users as $user) {
                        $user_id = $user['id_utilisateur'];
                        $user_name = $user['prenom'] . " " . $user['nom'];
                        $selected = in_array($user_id, $shared_users) ? "checked" : "";
                        echo "<li><input type='checkbox' name='utilisateurs[]' value='" . $user_id . "' $selected>" . $user_name . "</li>";
                    }
                } else {
                    echo "<li>Aucun utilisateur trouvé.</li>";
                }
                ?>
            </ul>
        </div>
        <input type="hidden" name="id_postit" value="<?= $id_postit ?>">
        <div class="form-group">
            <input type="submit" value="Modifier">
            <a href="#" onclick="goBack()" class="button-vis-ret">Retour</a>
        </div>
    </form>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="public/script-recherche.js"></script>
<?php $content = ob_get_clean(); ?>
<?php $title = "Visualiser Post-its"; ?>
<?php require('template.php'); ?>