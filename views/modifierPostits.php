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

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modification de Post-it</title>
    <link rel="stylesheet" href="public/styles.css">
</head>

<body>
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
                <label for="utilisateurs">Partager avec :</label><br>
                <select id="utilisateurs" name="utilisateurs[]" multiple>
                    <?php

                    if (!empty($result_users)) {
                        foreach ($result_users as $user) {
                            $user_id = $user['id_utilisateur'];
                            $user_name = $user['prenom'] . " " . $user['nom'];
                            $selected = in_array($user_id, $shared_users) ? "selected" : "";
                            echo "<option value='" . $user_id . "' $selected>" . $user_name . "</option>";
                        }
                    } else {
                        echo "Aucun utilisateur trouvé.";
                    }
                    ?>
                </select>
            </div>
            <input type="hidden" name="id_postit" value="<?= $id_postit ?>">
            <div class="form-group">
                <input type="submit" value="Modifier">
                <a href="#" onclick="goBack()" class="button-vis-ret">Retour</a>
            </div>
        </form>
    </div>

    <script>
        function goBack() {
            window.history.back();
        }
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>

</html>
<?php $content = ob_get_clean(); ?>
<?php require('template.php') ?>