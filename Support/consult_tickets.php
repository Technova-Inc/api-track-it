<?php
require_once 'dbconnect.php';

// Fonction pour récupérer tous les tickets ou les tickets d'un PC spécifique
function get_tickets($pc_name = null) {
    global $pdo;

    try {
        // Si le paramètre pc_name est passé, récupérer les tickets associés à ce PC
        if ($pc_name) {
            $stmt = $pdo->prepare(
                "SELECT t.id, t.pc_name, t.ticket_subject, t.ticket_status, t.created_at, c.libelleCategorie
                FROM tickets t
                LEFT JOIN categorietickets c ON t.idCategorie = c.idCategorie
                WHERE t.pc_name = ?"
            );
            $stmt->execute([$pc_name]);
        } else {
            // Sinon, récupérer tous les tickets
            $stmt = $pdo->query(
                "SELECT t.idTicket, t.titreTicket, t.descriptionTicket, t.user, t.Priorite, c.libelleCategorie 
                FROM tickets t 
                LEFT JOIN categorieTickets c ON t.idCategorie = c.idCategorie;
"
            );
        }

        // Vérifier si la requête a échoué
        if ($stmt === false) {
            throw new Exception("Erreur lors de l'exécution de la requête SQL.");
        }

        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    } catch (Exception $e) {
        // Si une erreur se produit, l'afficher
        echo json_encode(["error" => "Une erreur est survenue : " . $e->getMessage()]);
        return false;
    }
}

// Définir les en-têtes CORS
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Connaitre la méthode utilisée
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    // Vérifier si le paramètre 'pc' est fourni
    if (isset($_GET['pc']) && !empty($_GET['pc'])) {
        // Si le paramètre 'pc' est passé, récupérer les tickets pour ce PC
        $pc_name = $_GET['pc'];
        $tickets = get_tickets($pc_name);
    } else {
        // Sinon, récupérer tous les tickets
        $tickets = get_tickets();
    }

    if ($tickets !== false && !empty($tickets)) {
        echo json_encode(["success" => true, "tickets" => $tickets]);
    } else {
        echo json_encode(["success" => false, "tickets" => []]);
    }

} else {
    http_response_code(405); // Méthode non autorisée
    echo json_encode(["error" => "Méthode HTTP non autorisée."]);
}
?>
