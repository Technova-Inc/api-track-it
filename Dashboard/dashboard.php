<?php
require_once '../Configuration/dbconnect.php';

// Fonction générique pour compter les OS contenant un mot-clé donné
function get_os_count($keyword) {
    global $pdo;

    try {
        $stmt = $pdo->prepare("SELECT COUNT(*) AS total FROM hardware WHERE OSNAME LIKE ?");
        $stmt->execute(["%$keyword%"]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)$result['total'];
    } catch (Exception $e) {
        return 0;
    }
}

// En-têtes CORS et JSON
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Méthode HTTP autorisée : GET uniquement
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $stats = [
            'windows' => get_os_count('Windows'),
            'unix'    => get_os_count('unix'),
            'android' => get_os_count('android'),
            'macos'   => get_os_count('macos'),
        ];

        echo json_encode([
            'success' => true,
            'data' => $stats
        ]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'error' => 'Erreur serveur : ' . $e->getMessage()
        ]);
    }
} else {
    http_response_code(405);
    echo json_encode([
        'error' => 'Méthode HTTP non autorisée.'
    ]);
}
?>
