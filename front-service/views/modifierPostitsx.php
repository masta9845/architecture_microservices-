<?php ob_start(); ?>
<link rel="stylesheet" href="public/styles.css">
<div class="container">
    <h1>Modifier le Post-it</h1>
    <form id="form-postit" action="index.php?action=modif-postit" method="POST">
        <div class="form-group">
            <label for="titre">Titre :</label><br>
            <input type="text" id="titre" name="titre" value="<?= htmlspecialchars($postit_details['titre']) ?>" maxlength="150" required><br>
        </div>
        <div class="form-group">
            <label for="contenu">Contenu :</label><br>
            <textarea id="contenu" name="contenu" required><?= htmlspecialchars($postit_details['contenu']) ?></textarea><br>
        </div>
        <div class="form-group">
            <label for="searchUser">Partager avec :</label><br>
            <input type="text" id="searchUser" placeholder="Rechercher un utilisateur">
            <ul id="listutilisateurs" class="list-utilisateurs">
                <?php if (!empty($_SESSION['all_users'])): ?>
                    <?php foreach ($_SESSION['all_users'] as $user): ?>
                        <li>
                            <input type="checkbox" name="utilisateurs[]" value="<?= $user['id_utilisateur'] ?>" 
                                <?= in_array($user['id_utilisateur'], $shared_users) ? 'checked' : '' ?>>
                            <?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']) ?>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li>Aucun utilisateur trouvé.</li>
                <?php endif; ?>
            </ul>
        </div>
        <input type="hidden" name="id_postit" value="<?= $postit_details['id_postit'] ?>">
        <div class="form-group">
            <input type="submit" value="Modifier">
        </div>
    </form>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="public/script-recherche.js"></script>
<?php $content = ob_get_clean(); ?>
<?php require('template.php'); ?>
