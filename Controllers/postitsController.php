<?php
function ajoutPostit()
{

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
/**
 * affichage recuperation des information du postit a Modifier 
 *
 * @return void
 */
// Fonction pour récupérer les détails du post-it à modifier
function getPostItDetails($id_postit, $conn)
{
    $sql = "SELECT titre, contenu FROM Postit WHERE id_postit = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id_postit);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// Fonction pour récupérer les utilisateurs avec lesquels le post-it est partagé
function getSharedUsers($id_postit, $conn)
{
    $shared_users = array();
    $sql_shared = "SELECT id_user FROM Partage WHERE id_postit = ?";
    $stmt_shared = $conn->prepare($sql_shared);
    $stmt_shared->bind_param('i', $id_postit);
    $stmt_shared->execute();
    $result_shared = $stmt_shared->get_result();
    while ($row_shared = $result_shared->fetch_assoc()) {
        $shared_users[] = $row_shared['id_user'];
    }
    return $shared_users;
}

//FIn 


/**
 * Validation des modification sur le postit selectionner 
 *
 * @return void
 */
function sauvegarderModificationPostit()
{

    // Vérifier si la méthode de requête est POST
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $id_postit = $_POST['id_postit'];
        // Récupérer les données du formulaire
        $titre = $_POST['titre'];
        $contenu = $_POST['contenu'];

        // Inclure le fichier de configuration de la base de données
        include('config.php');

        // Connexion à la base de données
        $conn = new mysqli($dbConfig['host'], $dbConfig['username'], $dbConfig['password'], $dbConfig['dbname']);

        // Vérifier la connexion
        if ($conn->connect_error) {
            die('Erreur de connexion à la base de données : ' . $conn->connect_error);
        }

        // Mettre à jour le post-it
        $sql_update_postit = "UPDATE postit SET titre=?, contenu=?, date_creation =NOW() WHERE id_postit=?";
        $stmt_update_postit = $conn->prepare($sql_update_postit);
        $stmt_update_postit->bind_param('ssi', $titre, $contenu, $id_postit);

        // Exécuter la requête de mise à jour du post-it
        if (isset($_POST['utilisateurs'])) {
            //execution de la requete de modification de postit
            $stmt_update_postit->execute();
            $utilisateurs = $_POST['utilisateurs'];
            // Supprimer les anciennes entrées de partage pour ce post-it
            $sql_delete_partage = "DELETE FROM Partage WHERE id_postit=?";
            $stmt_delete_partage = $conn->prepare($sql_delete_partage);
            $stmt_delete_partage->bind_param('i', $id_postit);
            $stmt_delete_partage->execute();

            // Insérer les nouvelles entrées de partage
            foreach ($utilisateurs as $id_user) {
                $sql_insert_partage = "INSERT INTO Partage (id_postit, id_user) VALUES (?, ?)";
                $stmt_insert_partage = $conn->prepare($sql_insert_partage);
                $stmt_insert_partage->bind_param('ii', $id_postit, $id_user);
                $stmt_insert_partage->execute();
            }

            // Rediriger vers la page d'accueil avec un message de succès
            $_SESSION['success_message'] = "Post-it modifié avec succès.";
            header('Location: index.php');
            exit();
        } else if ($stmt_update_postit->execute()) {
            // Supprimer les anciennes entrées de partage pour ce post-it
            $sql_delete_partage = "DELETE FROM Partage WHERE id_postit=?";
            $stmt_delete_partage = $conn->prepare($sql_delete_partage);
            $stmt_delete_partage->bind_param('i', $id_postit);
            $stmt_delete_partage->execute();

            // Rediriger vers la page d'accueil avec un message de succès
            $_SESSION['success_message'] = "Post-it modifié avec succès.";
            header('Location: index.php');
            exit();
        } else {
            // Rediriger vers la page d'accueil avec un message d'erreur
            $_SESSION['error_message'] = "Erreur lors de la modification du post-it.";
            header('Location: index.php');
            exit();
        }

        // Fermer la connexion à la base de données
        $conn->close();
    } else {
        // Rediriger vers la page d'accueil si la méthode de requête n'est pas POST
        header('Location: index.php');
        exit();
    }
}

/**
 * Fonction pour visualiser un postit selectionner
 * on recupere d'abord les informations du post-its dont on a besoin d'afficher puis on le retourne a la vue d'afficher
 */
function affichePostits($id_postit)
{

    // Inclure les fichiers nécessaires
    include('config.php');
    $conn = new mysqli($dbConfig['host'], $dbConfig['username'], $dbConfig['password'], $dbConfig['dbname']);
    // Fonction pour récupérer les post-its possédés par l'utilisateur
    $sql = "SELECT P.id_owner,P.titre, P.contenu, P.date_creation, U.prenom AS owner_prenom 
    FROM Postit AS P 
    INNER JOIN utilisateur AS U ON P.id_owner = U.id_utilisateur WHERE id_postit = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id_postit);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

/**
 * Fonction pour supprimer un postit.
 * on supprime les partages liées au post-its dans la table partages, puis a la fin on supprime le post-its dans la tables postits
 */
function suppPostit()
{
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $id_user = $_SESSION['user_id'];
        $id_postit = $_POST['id_postit'];

        // Inclure le fichier de configuration de la base de données
        include('config.php');

        // Connexion à la base de données
        $conn = new mysqli($dbConfig['host'], $dbConfig['username'], $dbConfig['password'], $dbConfig['dbname']);

        // Vérifier la connexion
        if ($conn->connect_error) {
            die('Erreur de connexion à la base de données : ' . $conn->connect_error);
        }

        // Vérifier si l'utilisateur est le propriétaire du post-it

        // Supprimer les partages liés au post-it
        $stmt_delete_partage = $conn->prepare("DELETE FROM Partage WHERE id_postit = ?");
        $stmt_delete_partage->bind_param('i', $id_postit);
        $stmt_delete_partage->execute();
        $stmt_delete_partage->close();

        // Supprimer le post-it
        $stmt_delete_postit = $conn->prepare("DELETE FROM postit WHERE id_postit = ?");
        $stmt_delete_postit->bind_param('i', $id_postit);
        $stmt_delete_postit->execute();
        $stmt_delete_postit->close();

        $_SESSION['success_message'] = "Post-it supprimé avec succès.";

        // Rediriger vers la page d'accueil
        header('Location: index.php');
        exit();
    }
    // Fermer la connexion à la base de données
    $conn->close();
}

// Vérifier si l'action est une "insertion" ou une "visualisation" ou "affichage du formulaire de modification post-it" ou encore "la validation des modification effectuer sur le post-it" ou encore verifier si c'est une "suppression"
if (isset($_GET['action']) && $_GET['action'] === 'insert-postit') {
    ajoutPostit();
} elseif ($_GET['action'] && $_GET['action'] === 'visualiser-postits') {
    // recuperation de l'identifiant du postits a affché 
    $id_postit = $_GET['id'];
    // Recuperation des informations du postit à afficher 
    $postit_aff = json_encode(affichePostits($id_postit));
    //require(WEBROOT . '/views/visualisationPostits.php?postit_aff=' . urlencode($postit_aff));
    header("Location: index.php?action=VisualiserPostit&id=$id_postit&postit_aff=" . urlencode($postit_aff));
    exit();
} else if ($_GET['action'] && $_GET['action'] === 'mod-valid-postit') {
    sauvegarderModificationPostit();
} elseif ($_GET['action'] && $_GET['action'] === 'modif-postit') {
    include('config.php');
    // Connexion à la base de données
    $conn = new mysqli($dbConfig['host'], $dbConfig['username'], $dbConfig['password'], $dbConfig['dbname']);

    // Vérifier la connexion
    if ($conn->connect_error) {
        die('Erreur de connexion à la base de données : ' . $conn->connect_error);
    }

    $id_postit = $_GET['id'];
    // Récupérer les détails du post-it à modifier
    $postit_details = json_encode(getPostItDetails($id_postit, $conn));

    // Récupérer les utilisateurs avec lesquels le post-it est partagé
    $shared_users = json_encode(getSharedUsers($id_postit, $conn));

    // Requête SQL pour récupérer tous les utilisateurs
    $sql = "SELECT id_utilisateur, nom, prenom FROM utilisateur WHERE id_utilisateur != ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $_SESSION['user_id']);
    $stmt->execute();
    $result_users = $stmt->get_result();

    // Récupérer les données sous forme de tableau PHP
    $users_array = [];
    while ($row = $result_users->fetch_assoc()) {
        $users_array[] = $row;
    }
    $json_users = json_encode($users_array);

    header("Location: index.php?action=afficheInfo-postit-modif&postit_details=" . urlencode($postit_details) . "&shared_users=" . urlencode($shared_users) . "&result_users=" . urlencode($json_users) . "&id=" . urlencode($id_postit));
    exit();
} else if ($_GET['action'] && $_GET['action'] === 'supp-postit') {
    suppPostit();
} else {
    // Si l'action n'est pas spécifiée rediriger vers la page de connexion
    header("Location: index.php");
    exit();
}
