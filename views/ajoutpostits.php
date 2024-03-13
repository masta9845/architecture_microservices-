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
                // Inclure le fichier de connexion
                include('config.php');

                // Connexion à la base de données MySQL en utilisant les informations du fichier de connexion
                $conn = new mysqli($dbConfig['host'], $dbConfig['username'], $dbConfig['password'], $dbConfig['dbname']);

                // Vérifier la connexion
                if ($conn->connect_error) {
                    die('Erreur de connexion à la base de données : ' . $conn->connect_error);
                }

                // Requête SQL pour récupérer les utilisateurs
                $sql = "SELECT id_utilisateur, nom, prenom FROM utilisateur WHERE id_utilisateur != ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('i', $_SESSION['user_id']);
                $stmt->execute();
                $result = $stmt->get_result();
                // Affichage des utilisateurs dans la liste déroulante
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<li><input type='checkbox' name='utilisateurs[]' value='" . $row['id_utilisateur'] . "'>" . $row['prenom'] . " " . $row['nom'] . "</li>";
                    }
                } else {
                    echo "<tr>Aucun utilisateur trouvé.</tr>";
                }

                // Fermeture de la connexion à la base de données
                $conn->close();
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