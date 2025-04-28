<?php
require_once 'pc_functions.php'; 

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Allow-Credentials: true');

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    try {
        $pcs = get_lst_pc(); 

        echo json_encode([
            "success" => true,
            "pcs" => $pcs
        ]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            "success" => false,
            "error" => "Erreur lors de la récupération des PC : " . $e->getMessage()
        ]);
    }
} else {
    http_response_code(405);
    echo json_encode(["error" => "Méthode HTTP non autorisée."]);
}
?>
