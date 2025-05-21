<?php
// Gestion CORS
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: http://localhost:3000"); // ou l'origine exacte de votre front-endheader('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Allow-Credentials: true');

// Inclure la configuration de la base de données
require_once '../Configuration/dbconnect.php';

// Vérifier si la méthode de la requête est GET
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405); // Méthode non autorisée
    echo json_encode(["message" => "Méthode non autorisée"]);
    exit;
}

try {
    // Connexion à la base de données et récupération des rôles
    $sql_roles = "SELECT idRole, libelleRole, nivPermissions FROM roles";
    $stmt_roles = $pdo->prepare($sql_roles);
    $stmt_roles->execute();
    $roles = $stmt_roles->fetchAll(PDO::FETCH_ASSOC);

    // Préparer la réponse JSON
    $response = [
        "message" => "Rôles récupérés avec succès",
        "roles" => $roles
    ];

    http_response_code(200); // Succès
    echo json_encode($response);
} catch (PDOException $e) {
    http_response_code(500); // Erreur interne du serveur
    echo json_encode([
        "message" => "Erreur interne du serveur",
        "error" => $e->getMessage() // Optionnel : uniquement pour débogage
    ]);
}
?>
