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

function get_returned_hardware_per_month() {
    global $pdo;

    try {
        // Requête pour obtenir le nombre de PC ayant un retour par mois
        $stmt = $pdo->prepare("
            SELECT 
                MONTH(LASTCOME) AS month,
                COUNT(*) AS total_returns
            FROM 
                hardware
            WHERE 
                LASTCOME IS NOT NULL
                AND YEAR(LASTCOME) = YEAR(CURRENT_DATE)
            GROUP BY 
                MONTH(LASTCOME)
            ORDER BY 
                MONTH(LASTCOME);
        ");
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Créer un tableau avec tous les mois de l'année, initialisés à 0
        $months = [
            'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin',
            'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'
        ];

        $returns_per_month = array_fill(0, 12, 0);

        foreach ($result as $row) {
            $returns_per_month[$row['month'] - 1] = $row['total_returns'];
        }

        return array_map(function($month, $returns) {
            return ['month' => $month, 'total_returns' => $returns];
        }, $months, $returns_per_month);

    } catch (Exception $e) {
        return [];
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
            'monthly_stats' => get_returned_hardware_per_month(),

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
