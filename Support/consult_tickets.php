<?php
require_once '../Configuration/dbconnect.php';

// Fonction pour récupérer un ticket spécifique par ID
function get_ticket_by_id($ticket_id) {
    global $pdo;

    try {
        $stmt = $pdo->prepare(
            "SELECT t.idTicket, t.titreTicket, t.descriptionTicket, t.user, t.Priorite, c.libelleCategorie, t.createDate, t.UpdateDate, t.idstatus 
            FROM tickets t
            LEFT JOIN categorieTickets c ON t.idCategorie = c.idCategorie
            WHERE t.idTicket = ?"
        );
        $stmt->execute([$ticket_id]);

        // Vérifier si le ticket existe
        if ($stmt->rowCount() > 0) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            throw new Exception("Ticket non trouvé.");
        }

    } catch (Exception $e) {
        // Si une erreur se produit, l'afficher
        echo json_encode(["error" => "Une erreur est survenue : " . $e->getMessage()]);
        return false;
    }
}

// Fonction pour récupérer tous les tickets
function get_all_tickets() {
    global $pdo;

    try {
        $stmt = $pdo->query(
            "SELECT t.idTicket, t.titreTicket, t.descriptionTicket, t.user, t.Priorite, c.libelleCategorie 
            FROM tickets t
            LEFT JOIN categorieTickets c ON t.idCategorie = c.idCategorie"
        );

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
    // Vérifier si le paramètre 'id' est fourni
    if (isset($_GET['id']) && !empty($_GET['id'])) {
        // Si l'ID est passé, récupérer ce ticket par son ID
        $ticket_id = $_GET['id'];
        $ticket = get_ticket_by_id($ticket_id);

        if ($ticket !== false) {
            echo json_encode(["success" => true, "ticket" => $ticket]);
        } else {
            echo json_encode(["success" => false, "message" => "Ticket non trouvé"]);
        }
    } else {
        // Si aucun ID n'est passé, récupérer tous les tickets
        $tickets = get_all_tickets();

        if ($tickets !== false && !empty($tickets)) {
            echo json_encode(["success" => true, "tickets" => $tickets]);
        } else {
            echo json_encode(["success" => false, "message" => "Aucun ticket trouvé"]);
        }
    }

} else {
    http_response_code(405); // Méthode non autorisée
    echo json_encode(["error" => "Méthode HTTP non autorisée."]);
}
?>
