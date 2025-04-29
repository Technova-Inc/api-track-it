<?php
require_once '../Configuration/dbconnect.php';

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
    if (!isset($input['idTicket']) || !isset($input['status']) || !isset($input['priority']) ) {
        http_response_code(400);
        echo json_encode(["error" => "Tous les champs sont requis."]);
        exit;
    }

    // Extraire les données
    $idTicket = $input['idTicket'];  // ID du ticket à mettre à jour
    $status = $input['status'];
    $priority = $input['priority'];
    

  

    // Préparer la mise à jour du ticket
    try {
        $stmt = $pdo->prepare(
            "UPDATE tickets 
             SET idstatus = :idStatus, Priorite = :Priorite
             WHERE idTicket = :idTicket"
        );

        // Exécuter la requête de mise à jour
        $stmt->execute([
            'idStatus' => $status,
            'Priorite' => $priority,
            'idTicket' => $idTicket
        ]);

        // Vérifier si la mise à jour a eu lieu
        if ($stmt->rowCount() > 0) {
            // Si la mise à jour réussit
            http_response_code(200); // Réponse 200 OK
            echo json_encode(["success" => true, "message" => "Ticket mis à jour avec succès"]);
        } else {
            // Si le ticket n'existe pas ou aucune modification n'a eu lieu
            http_response_code(404);  // Ticket non trouvé
            echo json_encode(["error" => "Ticket non trouvé ou aucune modification effectuée."]);
        }

    } catch (Exception $e) {
        // En cas d'erreur d'exécution
        http_response_code(500);  // Erreur interne serveur
        echo json_encode(["error" => "Erreur lors de la mise à jour du ticket: " . $e->getMessage()]);
    }

} else {
    http_response_code(405); // Méthode non autorisée
    echo json_encode(["error" => "Méthode HTTP non autorisée."]);
}
?>
