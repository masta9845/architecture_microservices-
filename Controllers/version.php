<?php
function ajoutPostit() {

// Inclure le fichier de configuration de la base de données
include('config.php');

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $titre = $_POST['titre'];
    $contenu = $_POST['contenu'];
    $utilisateurs = $_POST['utilisateurs'];

    // Connexion à la base de données
    $conn = new mysqli($dbConfig['host'], $dbConfig['username'], $dbConfig['password'], $dbConfig['dbname']);

    // Vérifier la connexion
    if ($conn->connect_error) {
        die('Erreur de connexion à la base de données : ' . $conn->connect_error);
    }

    // Requête d'insertion pour le post-it
    $sql_insert_postit = "INSERT INTO postit (id_owner, titre, contenu) VALUES (?, ?, ?)";
    $stmt_insert_postit = $conn->prepare($sql_insert_postit);
    $stmt_insert_postit->bind_param('iss', $_SESSION['user_id'], $titre, $contenu);

    // Exécuter la requête d'insertion pour le post-it
    if ($stmt_insert_postit->execute()) {
        // Récupérer l'ID du post-it nouvellement inséré
        $id_postit = $stmt_insert_postit->insert_id;
        if (!empty($utilisateurs)) {
            // Requête d'insertion pour les partages
            $sql_insert_partage = "INSERT INTO partage (id_postit, id_user) VALUES (?, ?)";
            $stmt_insert_partage = $conn->prepare($sql_insert_partage);

            // Boucle pour insérer les relations de partage
            foreach ($utilisateurs as $id_user) {
                $stmt_insert_partage->bind_param('ii', $id_postit, $id_user);
                $stmt_insert_partage->execute();
            }
            $_SESSION['success_message'] = "Postit Crée avec succes";
            header('Location:' . ROOT);
            exit();
    } 
        $_SESSION['success_message'] = "Postit Crée avec succes";
        header('Location:' . ROOT);
        exit();
    } else {
        echo "Erreur lors de l'insertion du post-it : " . $conn->error;
    }

    // Fermer la connexion à la base de données
    $conn->close();
}
}

// Vérifier si l'action est "connexion"
if (isset($_GET['action']) && $_GET['action'] === 'insert-postit') {
    ajoutPostit();
} else {
    // Si l'action n'est pas spécifiée ou n'est pas "connexion", rediriger vers la page de connexion
    header("Location: index.php");
    exit();
}
?>
