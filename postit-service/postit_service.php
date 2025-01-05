<?php
header("Content-Type: application/json");
include_once 'config.php';

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? null;
// Fonction pour vérifier si l'utilisateur est le propriétaire
function isOwner($conn, $userId, $postitId) {
    $isOwner = 0;
    $stmt = $conn->prepare("SELECT COUNT(*) FROM postit WHERE id_owner = ? AND id_postit = ?");
    $stmt->bind_param('ii', $userId, $postitId);
    $stmt->execute();
    $stmt->bind_result($isOwner);
    $stmt->fetch();
    $stmt->close();
    return $isOwner > 0;
}

// Gestion des requêtes
if ($method === 'POST' && $action === 'add_postit') {
    // Ajouter un post-it
    $data = json_decode(file_get_contents('php://input'), true);
    if (isset($data['id_owner'], $data['titre'], $data['contenu'])) {
        $stmt = $conn->prepare("INSERT INTO postit (id_owner, titre, contenu, date_creation) VALUES (?, ?, ?, NOW())");
        $stmt->bind_param('iss', $data['id_owner'], $data['titre'], $data['contenu']);
        if ($stmt->execute()) {
            $postitId = $stmt->insert_id;

            // Ajouter les partages
            if (isset($data['shared_users']) && is_array($data['shared_users'])) {
                $stmtShare = $conn->prepare("INSERT INTO partage (id_postit, id_user) VALUES (?, ?)");
                foreach ($data['shared_users'] as $userId) {
                    $stmtShare->bind_param('ii', $postitId, $userId);
                    $stmtShare->execute();
                }
                $stmtShare->close();
            }
            echo json_encode(['success' => true, 'message' => 'Post-it ajouté avec succès.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'ajout du post-it.']);
        }
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Données incomplètes.']);
    }
} elseif ($method === 'PUT' && $action === 'update_postit') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['id_postit'], $data['id_owner'], $data['titre'], $data['contenu'])) {
        // Vérifier que l'utilisateur est le propriétaire
        if (!isOwner($conn, $data['id_owner'], $data['id_postit'])) {
            echo json_encode(['success' => false, 'message' => 'Vous n\'êtes pas autorisé à modifier ce post-it.']);
            exit();
        }

        // Mettre à jour le titre et le contenu du post-it
        $stmt = $conn->prepare("UPDATE postit SET titre = ?, contenu = ?, date_creation = NOW() WHERE id_postit = ?");
        $stmt->bind_param('ssi', $data['titre'], $data['contenu'], $data['id_postit']);
        if ($stmt->execute()) {
            // Gérer les partages si des utilisateurs sont spécifiés
            if (isset($data['shared_users']) && is_array($data['shared_users'])) {
                // Supprimer les anciens partages
                $stmtDelete = $conn->prepare("DELETE FROM partage WHERE id_postit = ?");
                $stmtDelete->bind_param('i', $data['id_postit']);
                $stmtDelete->execute();
                $stmtDelete->close();

                // Ajouter les nouveaux partages
                $stmtShare = $conn->prepare("INSERT INTO partage (id_postit, id_user) VALUES (?, ?)");
                foreach ($data['shared_users'] as $userId) {
                    $stmtShare->bind_param('ii', $data['id_postit'], $userId);
                    $stmtShare->execute();
                }
                $stmtShare->close();
            }

            echo json_encode(['success' => true, 'message' => 'Post-it modifié avec succès.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erreur lors de la modification du post-it.']);
        }
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Données incomplètes.']);
    }
} elseif ($method === 'POST' && $action === 'delete_postit') {
    // Supprimer un post-it
    $data = json_decode(file_get_contents('php://input'), true);
    if (isset($data['id_postit'], $data['id_owner'])) {
        if (!isOwner($conn, $data['id_owner'], $data['id_postit'])) {
            echo json_encode(['success' => false, 'message' => 'Vous n\'êtes pas autorisé à supprimer ce post-it.']);
            exit();
        }

        $stmtShare = $conn->prepare("DELETE FROM partage WHERE id_postit = ?");
        $stmtShare->bind_param('i', $data['id_postit']);
        $stmtShare->execute();
        $stmtShare->close();

        $stmt = $conn->prepare("DELETE FROM postit WHERE id_postit = ?");
        $stmt->bind_param('i', $data['id_postit']);
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Post-it supprimé avec succès.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erreur lors de la suppression du post-it.']);
        }
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Données incomplètes.']);
    }
} elseif ($method === 'POST' && $action === 'owned_postits') {
    // Récupérer les post-its possédés
    $data = json_decode(file_get_contents('php://input'), true);
    if (isset($data['user_id'])) {
        $stmt = $conn->prepare("SELECT id_postit, titre, contenu, date_creation FROM postit WHERE id_owner = ? ORDER BY date_creation DESC");
        $stmt->bind_param('i', $data['user_id']);
        $stmt->execute();
        $result = $stmt->get_result();
        $postits = $result->fetch_all(MYSQLI_ASSOC);
        echo json_encode(['success' => true, 'data' => $postits]);
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Données incomplètes.']);
    }
} elseif ($method === 'POST' && $action === 'get_all_users') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['user_id'])) {
        $stmt = $conn->prepare("SELECT id_utilisateur, nom, prenom FROM utilisateur WHERE id_utilisateur != ?");
        $stmt->bind_param('i', $data['user_id']);
        $stmt->execute();
        $result = $stmt->get_result();
        $users = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        echo json_encode(['success' => true, 'data' => $users]);
    } else {
        echo json_encode(['success' => false, 'message' => 'User ID manquant.']);
    }
    exit();
}elseif ($method === 'POST' && $action === 'shared_postits') {
    // Récupérer les post-its partagés
    $data = json_decode(file_get_contents('php://input'), true);
    if (isset($data['user_id'])) {
        $stmt = $conn->prepare("
            SELECT P.id_postit, P.titre, P.contenu, P.date_creation, P.id_owner, U.nom AS owner_nom, U.prenom AS owner_prenom
            FROM postit AS P
            INNER JOIN partage AS PR ON P.id_postit = PR.id_postit
            INNER JOIN utilisateur AS U ON P.id_owner = U.id_utilisateur
            WHERE PR.id_user = ? ORDER BY P.date_creation DESC
        ");
        $stmt->bind_param('i', $data['user_id']);
        $stmt->execute();
        $result = $stmt->get_result();
        $postits = $result->fetch_all(MYSQLI_ASSOC);
        echo json_encode(['success' => true, 'data' => $postits]);
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Données incomplètes.']);
    }
} elseif ($method === 'POST' && $action === 'get_postit') {
    // Visualiser un post-it
    $data = json_decode(file_get_contents('php://input'), true);
    if (isset($data['id_postit'], $data['id_user'])) {
        $stmt = $conn->prepare("
            SELECT P.id_postit, P.titre, P.contenu, P.date_creation, U.nom AS owner_nom, U.prenom AS owner_prenom
            FROM postit AS P
            LEFT JOIN partage AS PR ON P.id_postit = PR.id_postit
            LEFT JOIN utilisateur AS U ON P.id_owner = U.id_utilisateur
            WHERE (P.id_owner = ? OR PR.id_user = ?) AND P.id_postit = ?
        ");
        $stmt->bind_param('iii', $data['id_user'], $data['id_user'], $data['id_postit']);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $postit = $result->fetch_assoc();
            echo json_encode(['success' => true, 'data' => $postit]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Aucun post-it trouvé ou vous n\'êtes pas autorisé à le visualiser.']);
        }
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Données incomplètes.']);
    }
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée ou action invalide.']);
}
?>
