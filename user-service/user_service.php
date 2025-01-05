<?php
header("Content-Type: application/json");
include_once 'config.php';

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? null;

if ($method === 'POST' && $action === 'register') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['nom'], $data['prenom'], $data['email'], $data['password'], $data['date_naissance'])) {
        // Vérifier si l'email existe déjà
        $stmt = $conn->prepare("SELECT COUNT(*) FROM utilisateur WHERE email = ?");
        if (!$stmt) {
            echo json_encode(['success' => false, 'message' => 'Erreur de préparation SQL : ' . $conn->error]);
            exit();
        }
        $stmt->bind_param('s', $data['email']);
        $stmt->execute();
        $stmt->bind_result($emailExists);
        $stmt->fetch();
        $stmt->close();

        if ($emailExists > 0) {
            echo json_encode(['success' => false, 'message' => 'Cet email est déjà utilisé.']);
            exit();
        }

        // Insérer l'utilisateur
        $stmt = $conn->prepare("INSERT INTO utilisateur (nom, prenom, email, password, date_naissance) VALUES (?, ?, ?, ?, ?)");
        if (!$stmt) {
            echo json_encode(['success' => false, 'message' => 'Erreur de préparation SQL : ' . $conn->error]);
            exit();
        }
        $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);
        $stmt->bind_param('sssss', $data['nom'], $data['prenom'], $data['email'], $hashedPassword, $data['date_naissance']);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Utilisateur inscrit avec succès.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'inscription : ' . $stmt->error]);
        }
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Données incomplètes.']);
    }
} elseif ($action === 'login' && $method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    // Debug : Log des données reçues
    file_put_contents('php://stderr', "Données reçues pour connexion : " . json_encode($data) . "\n");

    if (isset($data['email'], $data['password'])) {
        $stmt = $conn->prepare("SELECT * FROM utilisateur WHERE email = ?");
        $stmt->bind_param("s", $data['email']);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            if (password_verify($data['password'], $row['password'])) {
                echo json_encode([
                    'success' => true,
                    'data' => [
                        'id' => $row['id_utilisateur'],
                        'nom' => $row['nom'],
                        'prenom' => $row['prenom'],
                        'email' => $row['email']
                    ]
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Mot de passe incorrect.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Utilisateur non trouvé.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Données invalides.']);
    }
    exit();
}
 else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée ou action invalide.']);
}
?>
