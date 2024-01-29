<?php ob_start(); ?>
<?php
// Inclure les fichiers nécessaires
include('config.php');
$conn = new mysqli($dbConfig['host'], $dbConfig['username'], $dbConfig['password'], $dbConfig['dbname']);
// Fonction pour récupérer les post-its possédés par l'utilisateur
function getOwnedPostIts($userId, $conn) {
    $sql = "SELECT id_postit, titre, contenu FROM Postit WHERE id_owner = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Fonction pour récupérer les post-its partagés avec l'utilisateur
function getSharedPostIts($userId, $conn) {
    $sql = "SELECT P.id_postit, P.titre, P.contenu, U.nom AS owner_nom, U.prenom AS owner_prenom
            FROM Postit AS P
            INNER JOIN Partage AS PR ON P.id_postit = PR.id_postit
            INNER JOIN utilisateurs AS U ON P.id_owner = U.id_utilisateur
            WHERE PR.id_user = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Vérifier si l'utilisateur est connecté
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];

    // Récupérer les post-its possédés
    $ownedPostIts = getOwnedPostIts($userId, $conn);

    // Récupérer les post-its partagés
    $sharedPostIts = getSharedPostIts($userId, $conn);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - Vos Post-its</title>
    <link rel="stylesheet" href="public/style.css"> 
</head>
<body>
<marquee class="marque">Bienvenu.e <?= $_SESSION['prenom'] . ' ' . $_SESSION['nom'] ?></marquee>
<main>
    <div id="acceuil">
            <p>Bienvenue.e</p>
    </div>
    <div class="postits-container">
        <div class="postits-section">
            <h2>Vos Post-its Possédés</h2>
            <div class="postit-list">
                <?php foreach ($ownedPostIts as $postit) : ?>
                    <div class="postit">
                            <a href="visualiser_postit.php?id=<?= $postit['id_postit'] ?>" class="postit-title">
                                    <?= $postit['titre'] ?>
                            </a>
                            <!-- <p> = substr($postit['contenu'], 0, 10) . '...' ?></p> -->
                            <p style="display: none;" class="postit-content-full">
                                <?= $postit['contenu']; ?>
                            </p>
                            <!-- <p>Ajouté le  $postit['date_ajout']; ?></p> -->
                            <!-- Ajoutez ici des liens pour éditer ou supprimer le post-it -->
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="postits-section">
            <h2>Post-its Partagés avec Vous</h2>
            <div class="postit-list">
                <?php foreach ($sharedPostIts as $postit) : ?>
                    <div class="postit">
                        <a href="visualiser_postit.php?id=<?= $postit['id_postit'] ?>" class="postit-title">
                                <?= $postit['titre'] ?>
                        </a>
                        <!-- <p> = substr($postit['contenu'], 0, 10) . '...' ?></p> -->
                        <p style="display: none;" class="postit-content-full">
                            <?= $postit['contenu']; ?>
                        </p>
                        <p>Ajouté par <?= $postit['owner_prenom'] . ' ' . $postit['owner_nom']; ?></p>
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
