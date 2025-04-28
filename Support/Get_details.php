<?php
// Gestion CORS
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Methods: GET');
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
    // Connexion à la base de données et récupération des catégories
    $sql_categories = "SELECT idCategorie, libelleCategorie FROM categorieTickets";
    $stmt_categories = $pdo->prepare($sql_categories);
    $stmt_categories->execute();
    $categories = $stmt_categories->fetchAll(PDO::FETCH_ASSOC);

    // Récupération des statuts
    $sql_statuses = "SELECT idstatus, libellestatus FROM status";
    $stmt_statuses = $pdo->prepare($sql_statuses);
    $stmt_statuses->execute();
    $statuses = $stmt_statuses->fetchAll(PDO::FETCH_ASSOC);

    // Préparer la réponse JSON
    $response = [
        "message" => "Catégories et statuts récupérés avec succès",
        "categories" => $categories,
        "statuses" => $statuses
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