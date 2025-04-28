<?php
require_once 'notes_functions.php';

// Gestion CORS
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS'); 
header('Access-Control-Allow-Headers: Content-Type, Authorization'); 
header('Access-Control-Allow-Credentials: true'); 

// Si la méthode est OPTIONS (preflight CORS), on répond immédiatement
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Connaitre la méthode utilisée
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    // Récupérer la note d'un PC
    if (!isset($_GET['pc']) || empty($_GET['pc'])) {
        http_response_code(400);
        echo json_encode(["error" => "Le paramètre 'pc' est requis."]);
        exit;
    }

    $pc_name = $_GET['pc'];
    $notes = get_notes_by_pc($pc_name);

    if (!empty($notes)) {
        echo json_encode(["notes" => $notes]);
    } else {
        echo json_encode(["notes" => []]);
    }

} elseif ($method === 'POST') {
    // Ajouter ou mettre à jour une note
    $input = json_decode(file_get_contents('php://input'), true);

    if (!isset($input['pc']) || !isset($input['note'])) {
        http_response_code(400);
        echo json_encode(["error" => "Les paramètres 'pc' et 'note' sont requis."]);
        exit;
    }

    $pc_name = $input['pc'];
    $note = $input['note'];

    insert_note_for_pc($pc_name, $note);

    echo json_encode(["success" => "Note mise à jour avec succès pour $pc_name."]);
} else {
    http_response_code(405); // Méthode non autorisée
    echo json_encode(["error" => "Méthode HTTP non autorisée."]);
}
?>
