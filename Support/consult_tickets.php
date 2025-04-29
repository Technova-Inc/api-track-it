<?php
require_once '../Configuration/dbconnect.php';

// Fonction pour récupérer un ticket spécifique par ID avec ses réponses
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

        if ($stmt->rowCount() > 0) {
            $ticket = $stmt->fetch(PDO::FETCH_ASSOC);

            // Récupérer les réponses du ticket (si applicable)
            $stmt_reponses = $pdo->prepare(
                "SELECT tr.idCommentaire, tr.commentaire, tr.idUser, tr.feedbackComm, tr.DatePublication, u.Login AS userLogin
                 FROM ticketReponse tr
                 LEFT JOIN users u ON tr.idUser = u.idUtilisateur
                 WHERE tr.idTicket = ?"
            );
            $stmt_reponses->execute([$ticket_id]);
            $reponses = $stmt_reponses->fetchAll(PDO::FETCH_ASSOC);

            // Ajouter les réponses au ticket
            $ticket['reponses'] = $reponses;

            return $ticket;
        } else {
            throw new Exception("Aucun ticket trouvé avec l'ID spécifié.");
        }
    } catch (Exception $e) {
        echo json_encode(["error" => "Une erreur est survenue : " . $e->getMessage()]);
        return false;
    }
}

// Fonction pour récupérer tous les tickets, avec ou sans filtre utilisateur
function get_all_tickets($idUser = null) {
    global $pdo;

    try {
        if (!empty($idUser)) {
            // Récupérer les tickets pour un utilisateur spécifique
            $stmt = $pdo->prepare(
               "SELECT t.idTicket, t.titreTicket, t.descriptionTicket, t.user, t.Priorite, 
            c.libelleCategorie, t.createDate, t.UpdateDate, t.idstatus, s.libelleStatus
            FROM tickets t
            LEFT JOIN categorieTickets c ON t.idCategorie = c.idCategorie
            LEFT JOIN status s ON t.idstatus = s.idstatus
            WHERE t.user = ?"
            );
            $stmt->execute([$idUser]);
        } else {
            // Récupérer tous les tickets
            $stmt = $pdo->query(
                "SELECT t.idTicket, t.titreTicket, t.descriptionTicket, t.user, t.Priorite, 
            c.libelleCategorie, t.createDate, t.UpdateDate, t.idstatus, s.libelleStatus
            FROM tickets t
            LEFT JOIN categorieTickets c ON t.idCategorie = c.idCategorie
            LEFT JOIN status s ON t.idstatus = s.idstatus"
            );
        }

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        echo json_encode(["error" => "Une erreur est survenue : " . $e->getMessage()]);
        return false;
    }
}

// Définir les en-têtes CORS
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Identifier la méthode HTTP utilisée
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $ticket_id = isset($_GET['id']) && !empty($_GET['id']) ? $_GET['id'] : null;
    $idUser = isset($_GET['idUser']) && !empty(trim($_GET['idUser'])) ? trim($_GET['idUser']) : null;

    try {
        if ($ticket_id) {
            // Récupération d'un ticket spécifique par son ID
            $ticket = get_ticket_by_id($ticket_id);

            if ($ticket !== false) {
                echo json_encode(["success" => true, "ticket" => $ticket]);
            } else {
                echo json_encode(["success" => false, "message" => "Ticket non trouvé"]);
            }
        } else {
            // Récupération de tous les tickets (avec ou sans filtre utilisateur)
            $tickets = get_all_tickets($idUser);

            if ($tickets !== false && !empty($tickets)) {
                echo json_encode(["success" => true, "tickets" => $tickets]);
            } else {
                echo json_encode(["success" => false, "message" => "Aucun ticket trouvé"]);
            }
        }
    } catch (Exception $e) {
        echo json_encode(["error" => "Une erreur inattendue est survenue : " . $e->getMessage()]);
    }
} else {
    http_response_code(405); // Méthode non autorisée
    echo json_encode(["error" => "Méthode HTTP non autorisée."]);
}
?>