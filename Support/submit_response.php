<?php
require_once '../Configuration/dbconnect.php';
require_once '../email/sendemail.php'; // Assurez-vous d'avoir cette fonction disponible

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
        // Récupérer les informations nécessaires (email et titre du ticket) de l'utilisateur
        $stmt = $pdo->prepare("
            SELECT u.email, t.titreTicket 
            FROM tickets t
            JOIN users u ON t.user = u.idUtilisateur
            WHERE t.idTicket = :idTicket
        ");
        $stmt->execute(['idTicket' => $idTicket]);
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$userData) {
            http_response_code(404);
            echo json_encode(["error" => "Ticket introuvable ou utilisateur non associé."]);
            exit;
        }

        // Insertion de la réponse dans la base de données
        $stmt = $pdo->prepare("
            INSERT INTO ticketReponse (idTicket, idUser, commentaire)
            VALUES (:idTicket, :idUser, :commentaire)
        ");
        $stmt->execute([
            'idTicket' => $idTicket,
            'idUser' => $idUser,
            'commentaire' => $commentaire
        ]);

        // Envoi de l'email
        $emailResult = sendTicketNotificationEmail(
            $userData['email'],          // Email du destinataire
            $userData['titreTicket'],    // Titre du ticket
            $idTicket,                   // ID du ticket
            'response',                  // Type d'action
            $commentaire                 // Commentaire (réponse)
        );

        // Vérifier si l'email a été envoyé avec succès
        if (!$emailResult['emailSent']) {
            throw new Exception("Erreur lors de l'envoi de l'email: " . $emailResult['error']);
        }

        // Succès
        http_response_code(200);
        echo json_encode(["success" => true, "message" => "Réponse enregistrée et email envoyé avec succès."]);

    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(["error" => "Erreur lors de l'insertion de la réponse: " . $e->getMessage()]);
    }

} else {
    http_response_code(405);
    echo json_encode(["error" => "Méthode HTTP non autorisée."]);
}
?>
