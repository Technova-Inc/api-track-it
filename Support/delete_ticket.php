<?php
require_once '../Configuration/dbconnect.php';  // Connexion à la base de données

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
    // Récupérer les données envoyées
    $input = json_decode(file_get_contents('php://input'), true);

    if (!isset($input['idTicket'])) {
        http_response_code(400);
        echo json_encode(["error" => "L'ID du ticket est requis."]);
        exit;
    }

    $idTicket = $input['idTicket'];

    try {
        // Début d'une transaction
        $pdo->beginTransaction();

        // Supprimer les réponses associées au ticket
        $stmt = $pdo->prepare("DELETE FROM ticketReponse WHERE idTicket = :idTicket");
        $stmt->execute(['idTicket' => $idTicket]);

        // Supprimer le ticket lui-même
        $stmt = $pdo->prepare("DELETE FROM tickets WHERE idTicket = :idTicket");
        $stmt->execute(['idTicket' => $idTicket]);

        // Valider la transaction
        $pdo->commit();

        http_response_code(200);
        echo json_encode(["success" => true, "message" => "Ticket supprimé avec succès."]);

    } catch (Exception $e) {
        // En cas d'erreur, annuler la transaction
        $pdo->rollBack();
        http_response_code(500);
        echo json_encode(["error" => "Erreur lors de la suppression du ticket: " . $e->getMessage()]);
    }

} else {
    http_response_code(405);
    echo json_encode(["error" => "Méthode HTTP non autorisée."]);
}
?>
