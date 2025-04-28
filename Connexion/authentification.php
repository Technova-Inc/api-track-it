<?php
session_start(); // Démarrer la session
// Gestion CORS
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS'); 
header('Access-Control-Allow-Headers: Content-Type, Authorization'); 
header('Access-Control-Allow-Credentials: true'); 
// Si la méthode est OPTIONS (preflight CORS), on répond immédiatement
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}
// Inclure la configuration de la base de données
require_once '../Configuration/dbconnect.php';

// Vérifier si la méthode de la requête est POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Méthode non autorisée
    echo json_encode(["message" => "Méthode non autorisée"]);
    exit;
}

// Lire les données envoyées depuis le frontend
$data = json_decode(file_get_contents("php://input"));

if (!isset($data->username) || !isset($data->password)) {
    http_response_code(400); // Mauvaise requête
    echo json_encode(["message" => "Nom d'utilisateur et mot de passe requis"]);
    exit;
}

$username = htmlspecialchars(strip_tags($data->username));
$password = htmlspecialchars(strip_tags($data->password));

// Connexion à la base de données
try {
    // Requête pour vérifier l'utilisateur
    $sql = "SELECT id, username, role, password_hash FROM utilisateurs WHERE username = :username";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Vérifier si l'utilisateur existe et si le mot de passe est correct
    if ($user && password_verify($password, $user['password_hash'])) {
        // Vérifier si l'utilisateur a un rôle autorisé
        $rolesAutorises = ["ROLE_ADMIN", "ROLE_USER", "ROLE_VISITEUR"];
        if (in_array($user['role'], $rolesAutorises)) {
            // Stocker les informations de l'utilisateur dans la session
            $_SESSION['user'] = [
                'id' => $user['id'],
                'username' => $user['username'],
                'role' => $user['role']
            ];

            http_response_code(200); // Succès
            echo json_encode(["message" => "Connexion réussie"]);
        } else {
            http_response_code(403); // Accès refusé
            echo json_encode(["message" => "Rôle non autorisé"]);
        }
    } else {
        http_response_code(401); // Non autorisé
        echo json_encode(["message" => "Nom d'utilisateur ou mot de passe incorrect"]);
    }
} catch (PDOException $e) {
    http_response_code(500); // Erreur interne du serveur
    echo json_encode(["message" => "Erreur de connexion à la base de données", "error" => $e->getMessage()]);
}