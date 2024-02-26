<?php ob_start(); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajout de Post-it</title>
    <meta name="author" content="RUELE Amaury">
    <meta name="author" content="CASSACA Kilian">
    <meta name="author" content="RABHI Sofiene">
    <meta name="author" content="Ridouane OUSMANE DOUDOU">
    <link rel="stylesheet" href="public/styles.css">
</head>
<body>
    <div class="container">
        <h1>Ajouter un Post-it</h1>
        <form id="form-postit" action="index.php?action=insert-postit" method="POST">
            <div class="form-group">
                <label for="titre">Titre :</label><br>
                <input type="text" id="titre" name="titre" required><br>
            </div>
            <div class="form-group">
                <label for="contenu">Contenu :</label><br>
                <textarea id="contenu" name="contenu" required></textarea><br>
            </div>
            <div class="form-group">
                <label for="utilisateurs">Partager avec :</label><br>
                <select id="utilisateurs" name="utilisateurs[]" multiple >
                    <?php
                    // Inclure le fichier de connexion
                    include('config.php');

                    // Connexion à la base de données MySQL en utilisant les informations du fichier de connexion
                    $conn = new mysqli($dbConfig['host'], $dbConfig['username'], $dbConfig['password'], $dbConfig['dbname']);

                    // Vérifier la connexion
                    if ($conn->connect_error) {
                        die('Erreur de connexion à la base de données : ' . $conn->connect_error);
                    }

                    // Requête SQL pour récupérer les utilisateurs
                    $sql = "SELECT id_utilisateur, nom, prenom FROM utilisateurs WHERE id_utilisateur != ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param('i', $_SESSION['user_id']);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    // Affichage des utilisateurs dans la liste déroulante
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            print_r($row);
                            echo "<option value='" . $row['id_utilisateur'] . "'>" . $row['prenom'] . " " . $row['nom'] . "</option>";
                        }
                    } else {
                        echo "Aucun utilisateur trouvé.";
                    }

                    // Fermeture de la connexion à la base de données
                    $conn->close();
                    ?>
                </select>
            </div>
            <div class="form-group">
                <input type="submit" value="Ajouter">
            </div>
        </form>
    </div>

    <!-- Inclure jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>
</html>

<?php $content = ob_get_clean(); ?>
<?php require('template.php') ?>
