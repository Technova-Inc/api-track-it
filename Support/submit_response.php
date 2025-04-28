<?php
require_once '../dbconnect.php';  // Connexion BDD

// Gestion CORS
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS'); 
header('Access-Control-Allow-Headers: Content-Type, Authorization'); 
header('Access-Control-Allow-Credentials: true'); 

// Gestion pré-vol CORS
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Récupération de la méthode
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
    // Récupérer le corps JSON
    $input = json_decode(file_get_contents('php://input'), true);

    // Vérifier que les champs nécessaires sont présents
    if (!isset($input['idTicket']) || !isset($input['idUser']) || !isset($input['message'])) {
        http_response_code(400);
        echo json_encode(["error" => "Tous les champs sont requis."]);
        exit;
    }

    // Extraire les données
    $idTicket = intval($input['idTicket']);
    $idUser = intval($input['idUser']);
    $message = trim($input['message']);

    try {
        // Préparer la requête d'insertion
        $stmt = $pdo->prepare("INSERT INTO reponses_tickets (id_ticket, id_user, message, date_reponse)
                               VALUES (:id_ticket, :id_user, :message, NOW())");

        // Exécuter
        $stmt->execute([
            'id_ticket' => $idTicket,
            'id_user' => $idUser,
            'message' => $message
        ]);

        // Succès
        http_response_code(200);
        echo json_encode(["success" => true, "message" => "Réponse enregistrée avec succès."]);

    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(["error" => "Erreur lors de l'insertion de la réponse: " . $e->getMessage()]);
    }

} else {
    http_response_code(405);
    echo json_encode(["error" => "Méthode HTTP non autorisée."]);
}
?>
