<?php
session_start();

header("Access-Control-Allow-Origin: *"); 
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if (isset($_SESSION['user'])) {
    http_response_code(200);
    // Ajoutez un var_dump pour déboguer la session si nécessaire
    // var_dump($_SESSION);
    echo json_encode([
        "message" => "Utilisateur connecté",
        "user" => $_SESSION['user']
    ]);
} else {
    http_response_code(401);
    echo json_encode(["message" => "Aucun utilisateur connecté"]);
}
?>