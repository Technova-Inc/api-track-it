<?php
require_once '../Configuration/dbconnect.php';  // Assure-toi d'importer la connexion à ta base de données

// Gestion CORS
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS'); 
header('Access-Control-Allow-Headers: Content-Type, Authorization'); 
header('Access-Control-Allow-Credentials: true'); 

// Vérifier si la requête est une demande OPTIONS (pré-vol CORS)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Récupérer la méthode de la requête
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
    // Récupérer les données envoyées dans le corps de la requête
    $input = json_decode(file_get_contents('php://input'), true);

    // Vérifier que tous les champs nécessaires sont présents
    if (!isset($input['category']) || !isset($input['priority']) || !isset($input['title']) || !isset($input['description'])) {
        http_response_code(400);
        echo json_encode(["error" => "Tous les champs sont requis."]);
        exit;
    }

    // Extraire les données
    $category = $input['category'];
    $priority = $input['priority'];
    $title = $input['title'];
    $description = $input['description'];

    // Récupérer l'ID de la catégorie à partir du libellé
    $stmt = $pdo->prepare("SELECT idCategorie FROM categorieTickets WHERE libelleCategorie = ?");
    $stmt->execute([$category]);
    $idCategorie = $stmt->fetchColumn(); // Récupérer l'ID de la catégorie

    if (!$idCategorie) {
        http_response_code(400);  // Catégorie invalide
        echo json_encode(["error" => "Catégorie invalide."]);
        exit;
    }

    // Récupérer le nom de l'utilisateur (à remplacer par la méthode d'identification de l'utilisateur)
    $user = $_SESSION['user']['id']; // Exemple d'utilisateur statique, à remplacer par un mécanisme d'identification réel

    try {
        // Préparer l'insertion dans la table tickets
        $stmt = $pdo->prepare("INSERT INTO tickets (titreTicket, descriptionTicket, idCategorie, user, Priorite) 
                               VALUES (:titreTicket, :descriptionTicket, :idCategorie, :user, :Priorite)");

        // Exécuter la requête d'insertion
        $stmt->execute([
            'titreTicket' => $title,
            'descriptionTicket' => $description,
            'idCategorie' => $idCategorie,
            'user' => $user,
            'Priorite' => $priority,
        ]);

        // Si l'insertion réussit
        http_response_code(200); // Réponse 200 OK
        echo json_encode(["success" => true, "message" => "Ticket créé avec succès"]);

    } catch (Exception $e) {
        // En cas d'erreur d'exécution
        http_response_code(500);  // Erreur interne serveur
        echo json_encode(["error" => "Erreur lors de l'insertion du ticket: " . $e->getMessage()]);
    }

} else {
    http_response_code(405); // Méthode non autorisée
    echo json_encode(["error" => "Méthode HTTP non autorisée."]);
}
?>
