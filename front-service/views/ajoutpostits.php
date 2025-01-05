<?php ob_start(); ?>
<link rel="stylesheet" href="public/styles.css">
<div class="container">
    <h1>Ajouter un Post-it</h1>
    <form id="form-postit" action="index.php?action=insert-postit" method="POST">
        <div class="form-group">
            <label for="titre">Titre :</label><br>
            <input type="text" id="titre" name="titre" maxlength="150"><br>
        </div>
        <div class="form-group">
            <label for="contenu">Contenu :</label><br>
            <textarea id="contenu" name="contenu" ></textarea><br>
        </div>
        <div class="form-group">
            <label for="searchUser">Partager avec :</label><br>
            <input type="text" id="searchUser" placeholder="Rechercher un utilisateur">
            <ul id="listutilisateurs" class="list-utilisateurs">
                <?php
                if (isset($_SESSION['all_users']) && !empty($_SESSION['all_users'])) {
                    foreach ($_SESSION['all_users'] as $user) {
                        echo "<li><input type='checkbox' name='utilisateurs[]' value='" . $user['id_utilisateur'] . "'>" . $user['prenom'] . " " . $user['nom'] . "</li>";
                    }
                } else {
                    echo "<li>Aucun utilisateur trouvé.</li>";
                }
                ?>
            </ul>
        </div>

        <div class="form-group">
            <input type="submit" value="Ajouter">
        </div>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="public/script-recherche.js"></script>

<?php $content = ob_get_clean(); ?>
<?php require('template.php') ?>