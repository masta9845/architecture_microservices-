<?php ob_start(); ?>
<link rel="stylesheet" href="public/styles.css">
<div class="container-vis-post">
    <?php if ($postit_details['id_owner'] == $_SESSION['user_id']): ?>
        <div class="postit-vis yellow">
            <h2><?= htmlspecialchars($postit_details['titre']) ?></h2>
            <p><?= nl2br(htmlspecialchars($postit_details['contenu'])) ?></p>
            <p>Date de création : <?= date('d/m/Y', strtotime($postit_details['date_creation'])) ?></p>
            <div class="button-group-vis">
                <a href="index.php?action=modif-postit&id=<?= $postit_details['id_postit'] ?>" class="button-vis-mod">Modifier</a>
                <form action="index.php?action=supp-postit" method="post">
                    <input type="hidden" name="id_postit" value="<?= $postit_details['id_postit'] ?>">
                    <button type="submit" class="button-vis-sup" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce post-it ?')">Supprimer</button>
                </form>
            </div>
        </div>
    <?php else: ?>
        <div class="postit-vis green">
            <h2><?= htmlspecialchars($postit_details['titre']) ?></h2>
            <p><?= nl2br(htmlspecialchars($postit_details['contenu'])) ?></p>
            <p>Partagé par : <?= htmlspecialchars($postit_details['owner_prenom'] . ' ' . $postit_details['owner_nom']) ?></p>
            <p>Date de création : <?= date('d/m/Y', strtotime($postit_details['date_creation'])) ?></p>
        </div>
    <?php endif; ?>
</div>
<?php $content = ob_get_clean(); ?>
<?php require('template.php'); ?>
        