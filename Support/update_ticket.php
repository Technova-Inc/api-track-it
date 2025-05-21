<?php
require_once '../Configuration/dbconnect.php';
require_once '../email/sendemail.php';

// CORS headers
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS'); 
header('Access-Control-Allow-Headers: Content-Type, Authorization'); 
header('Access-Control-Allow-Credentials: true'); 

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["error" => "Méthode HTTP non autorisée."]);
    exit;
}

// Lire les données JSON
$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['idTicket']) || !isset($input['status']) || !isset($input['priority'])) {
    http_response_code(400);
    echo json_encode(["error" => "Tous les champs sont requis."]);
    exit;
}

$idTicket = $input['idTicket'];
$statusId = $input['status'];
$priority = $input['priority'];

// Récupération de l'email, du titre du ticket et du libellé du nouveau statut
$stmt = $pdo->prepare("
    SELECT u.email, t.titreTicket, s.libelleStatus
    FROM tickets t
    JOIN users u ON t.user = u.idUtilisateur
    JOIN status s ON s.idStatus = :statusId
    WHERE t.idTicket = :idTicket
");
$stmt->execute([
    'statusId' => $statusId,
    'idTicket' => $idTicket
]);

$recipientData = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$recipientData) {
    http_response_code(400);
    echo json_encode(["error" => "Impossible de récupérer les informations pour le ticket."]);
    exit;
}

$email = $recipientData['email'];
$titreTicket = $recipientData['titreTicket'];
$libelleStatus = $recipientData['libelleStatus'];

try {
    // Mise à jour du ticket
    $stmt = $pdo->prepare("
        UPDATE tickets 
        SET idStatus = :idStatus, Priorite = :Priorite
        WHERE idTicket = :idTicket
    ");
    $stmt->execute([
        'idStatus' => $statusId,
        'Priorite' => $priority,
        'idTicket' => $idTicket
    ]);

    if ($stmt->rowCount() > 0) {
        // Envoi de l'email de mise à jour
        $emailResult = sendTicketNotificationEmail($email, $titreTicket, $idTicket, 'update', $libelleStatus, $priority);

        if (!$emailResult['emailSent']) {
            http_response_code(500);
            echo json_encode([
                "success" => true,
                "message" => "Ticket mis à jour mais l'envoi de l'email a échoué.",
                "emailSent" => false,
                "error" => $emailResult['error']
            ]);
            exit;
        }

        http_response_code(200);
        echo json_encode(["success" => true, "message" => "Ticket mis à jour avec succès.", "emailSent" => true]);
    } else {
        http_response_code(404);
        echo json_encode(["error" => "Ticket non trouvé ou aucune modification effectuée."]);
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => "Erreur lors de la mise à jour du ticket: " . $e->getMessage()]);
}
?>
