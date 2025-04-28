<?php
require_once '../Configuration/dbconnect.php';

// Gestion CORS
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS'); 
header('Access-Control-Allow-Headers: Content-Type, Authorization'); 
header('Access-Control-Allow-Credentials: true'); 

// Pré-vol CORS
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Méthode
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
    // Lire JSON
    $input = json_decode(file_get_contents('php://input'), true);

    // Vérifications
    if (!isset($input['idTicket']) || !isset($input['idUser']) || !isset($input['commentaire'])) {
        http_response_code(400);
        echo json_encode(["error" => "Tous les champs sont requis."]);
        exit;
    }

    // Extraction sécurisée
    $idTicket = intval($input['idTicket']);
    $idUser = intval($input['idUser']);
    $commentaire = trim($input['commentaire']);

    try {
        // Préparer l'insertion
        $stmt = $pdo->prepare("
            INSERT INTO ticketReponse (idTicket, idUser, commentaire)
            VALUES (:idTicket, :idUser, :commentaire)
        ");

        $stmt->execute([
            'idTicket' => $idTicket,
            'idUser' => $idUser,
            'commentaire' => $commentaire
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
