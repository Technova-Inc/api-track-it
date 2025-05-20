<?php
require_once '../Configuration/dbconnect.php';

// Fonction générique pour exécuter une requête et retourner une valeur unique
function fetch_single_value($query) {
    global $pdo;
    try {
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return array_values($result)[0]; // Récupère la première valeur
    } catch (Exception $e) {
        return null;
    }
}

function get_tickets_per_month() {
    global $pdo;

    try {
        // Requête pour obtenir le nombre de tickets par mois
        $stmt = $pdo->prepare("
            SELECT 
                MONTH(createDate) AS month, 
                COUNT(*) AS total_tickets
            FROM 
                tickets
            WHERE 
                YEAR(createDate) = YEAR(CURRENT_DATE) 
            GROUP BY 
                MONTH(createDate)
            ORDER BY 
                MONTH(createDate);
        ");
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Créer un tableau avec tous les mois de l'année, initialisés à 0
        $months = [
            'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin',
            'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'
        ];

        // Initialiser un tableau de résultats avec 0 pour chaque mois
        $tickets_per_month = array_fill(0, 12, 0);

        // Mettre à jour le tableau avec les résultats de la requête
        foreach ($result as $row) {
            $tickets_per_month[$row['month'] - 1] = $row['total_tickets'];
        }

        // Retourner un tableau associatif avec les mois et leur nombre de tickets
        return array_map(function($month, $tickets) {
            return ['month' => $month, 'total_tickets' => $tickets];
        }, $months, $tickets_per_month);
    } catch (Exception $e) {
        return [];
    }
}

// Fonctions spécifiques
function get_closure_rate_percent() {
    global $pdo;

    try {
        // Requête pour obtenir le taux de clôture, avec arrondi à l'entier le plus proche
        $stmt = $pdo->prepare("
            SELECT ROUND( 
                (SELECT COUNT(*) FROM tickets WHERE idstatus = 2) * 100.0 / COUNT(*), 0 
            ) AS closure_rate_percent 
            FROM tickets;
        ");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['closure_rate_percent'];
    } catch (Exception $e) {
        return 0;
    }
}

function get_avg_resolution_time_hours() {
    global $pdo;

    try {
        $stmt = $pdo->prepare("
            SELECT AVG(TIMESTAMPDIFF(HOUR, createDate, UpdateDate)) AS avg_resolution_time_hours
            FROM tickets WHERE idstatus = 2;
        ");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $hours = $result['avg_resolution_time_hours'];

        if ($hours === null) return 'N/A';

        // Si plus de 24 heures, convertir en jours avec 1 décimale
        if ($hours > 24) {
            $days = round($hours / 24, 1);
            return "{$days} j";
        } else {
            return round($hours, 1) . ' h';
        }
    } catch (Exception $e) {
        return 'N/A';
    }
}

function get_tickets_in_progress_count() {
    return fetch_single_value("
        SELECT COUNT(*) AS in_progress_count FROM tickets WHERE idstatus != 2;
    ");
}

function get_total_tickets() {
    return fetch_single_value("SELECT COUNT(*) AS total FROM tickets;");
}

function get_monthly_tickets() {
    return fetch_single_value("
        SELECT COUNT(*) FROM tickets
        WHERE MONTH(createDate) = MONTH(CURRENT_DATE())
        AND YEAR(createDate) = YEAR(CURRENT_DATE());
    ");
}

function get_resolved_tickets() {
    return fetch_single_value("
        SELECT COUNT(*) FROM tickets WHERE idstatus = 2;
    ");
}

function get_resolved_today() {
    return fetch_single_value("
        SELECT COUNT(*) FROM tickets
        WHERE idstatus = 2 AND DATE(UpdateDate) = CURRENT_DATE();
    ");
}

function get_stale_tickets() {
    return fetch_single_value("
        SELECT COUNT(*) FROM tickets
        WHERE idstatus != 2 AND TIMESTAMPDIFF(HOUR, UpdateDate, NOW()) > 48;
    ");
}

// En-têtes CORS et JSON
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Traitement de la requête GET
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $stats = [
            'closure_rate_percent'      => get_closure_rate_percent(),
            'avg_resolution_time_hours' => get_avg_resolution_time_hours(),
            'tickets_in_progress_count' => get_tickets_in_progress_count(),
            'total_tickets'             => get_total_tickets(),
            'monthly_tickets'           => get_monthly_tickets(),
            'resolved_tickets'          => get_resolved_tickets(),
            'resolved_today'            => get_resolved_today(),
            'stale_tickets'             => get_stale_tickets(),
            'tickets_per_month'         => get_tickets_per_month(),
        ];

        echo json_encode([
            'success' => true,
            'data'    => $stats
        ]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'error'   => 'Erreur serveur : ' . $e->getMessage()
        ]);
    }
} else {
    http_response_code(405);
    echo json_encode([
        'error' => 'Méthode HTTP non autorisée.'
    ]);
}
