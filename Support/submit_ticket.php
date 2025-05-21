<?php
require_once '../Configuration/dbconnect.php';
require_once '../email/sendemail.php'; // Contient la nouvelle fonction sendTicketNotificationEmail

// Gestion CORS
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

if (
    !isset($input['category']) ||
    !isset($input['priority']) ||
    !isset($input['userid']) ||
    !isset($input['title']) ||
    !isset($input['description'])
) {
    http_response_code(400);
    echo json_encode(["error" => "Tous les champs sont requis."]);
    exit;
}

// Extraction des données
$category = $input['category'];
$priority = $input['priority'];
$title = $input['title'];
$description = $input['description'];
$userId = $input['userid'];

// Récupération de l'email de l'utilisateur
$stmt = $pdo->prepare("SELECT email FROM users WHERE idUtilisateur = ?");
$stmt->execute([$userId]);
$recipientEmail = $stmt->fetchColumn();

if (!$recipientEmail) {
    http_response_code(400);
    echo json_encode(["error" => "Email introuvable pour l'utilisateur."]);
    exit;
}

// Récupération de l'ID de la catégorie
$stmt = $pdo->prepare("SELECT idCategorie FROM categorieTickets WHERE libelleCategorie = ?");
$stmt->execute([$category]);
$idCategorie = $stmt->fetchColumn();

if (!$idCategorie) {
    http_response_code(400);
    echo json_encode(["error" => "Catégorie invalide."]);
    exit;
}

try {
    // Insertion du ticket
    $stmt = $pdo->prepare("INSERT INTO tickets (titreTicket, descriptionTicket, idCategorie, user, Priorite)
                           VALUES (:titreTicket, :descriptionTicket, :idCategorie, :user, :Priorite)");
    $stmt->execute([
        'titreTicket' => $title,
        'descriptionTicket' => $description,
        'idCategorie' => $idCategorie,
        'user' => $userId,
        'Priorite' => $priority
    ]);

    $ticketId = $pdo->lastInsertId();

    // Envoi de l'email
    $emailResult = sendTicketNotificationEmail($recipientEmail, $title, $ticketId, 'creation', $description);

    if (!$emailResult['emailSent']) {
        http_response_code(500);
        echo json_encode([
            "success" => true,
            "message" => "Ticket créé avec succès, mais l'envoi de l'email a échoué.",
            "emailSent" => false,
            "error" => $emailResult['error']
        ]);
        exit;
    }

    http_response_code(200);
    echo json_encode([
        "success" => true,
        "message" => "Ticket créé avec succès.",
        "emailSent" => true
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => "Erreur lors de l'insertion du ticket: " . $e->getMessage()]);
}
?>
