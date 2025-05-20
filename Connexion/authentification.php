<?php
// Définir les paramètres du cookie de session avant de démarrer la session
session_set_cookie_params([
    'lifetime' => 3600,  // Durée du cookie (1 heure)
    'secure' => false,     // Transmettre seulement via HTTPS
    'httponly' => true,   // Empêcher l'accès via JavaScript
    'samesite' => 'Strict' // Empêcher les requêtes inter-domaines
]);

session_start(); // Démarrer la session

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

// Inclure la configuration de la base de données
require_once '../Configuration/dbconnect.php';

// Vérifier si la méthode de la requête est POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Méthode non autorisée
    echo json_encode(["message" => "Méthode non autorisée"]);
    exit;
}

// Lire et valider les données envoyées depuis le frontend
$data = json_decode(file_get_contents("php://input"));

if (empty($data->username) || empty($data->password)) {
    http_response_code(400); // Mauvaise requête
    echo json_encode(["message" => "Nom d'utilisateur et mot de passe requis"]);
    exit;
}

$username = htmlspecialchars($data->username, ENT_QUOTES, 'UTF-8');
$password = htmlspecialchars($data->password, ENT_QUOTES, 'UTF-8');

// Connexion à la base de données
try {
    // Requête pour vérifier l'utilisateur
    $sql = "SELECT idUtilisateur, Login, idRole, email, password FROM users WHERE Login = :username";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Vérification de l'utilisateur et du mot de passe en clair
    if ($user && $password === $user['password']) {  // Comparer les mots de passe en clair
        // Charger les rôles autorisés depuis une configuration centralisée
        $rolesAutorises = [1, 2, 3]; // ID des rôles autorisés
        if (in_array($user['idRole'], $rolesAutorises)) {
            // Stocker les informations de l'utilisateur dans la session
            $_SESSION['user'] = [
                'id' => $user['idUtilisateur'],
                'username' => $user['Login'],
                'role' => $user['idRole'],
                'email' => $user['email']
            ];

            // Sécuriser le cookie de session
            session_regenerate_id(true);  // Empêcher les attaques de fixation de session

            // Réponse JSON avec les données utilisateur utiles
            http_response_code(200); // Succès
            echo json_encode([
                "message" => "Connexion réussie",
                "user" => [
                    "id" => $user['idUtilisateur'],
                    "username" => $user['Login'],
                    "role" => $user['idRole'],
                    "email" => $user['email']

                ]
            ]);
        } else {
            http_response_code(403); // Accès refusé
            echo json_encode(["message" => "Rôle non autorisé"]);
        }
    } else {
        http_response_code(401); // Non autorisé
        echo json_encode(["message" => "Identifiants incorrects"]);
    }
} catch (PDOException $e) {
    http_response_code(500); // Erreur interne du serveur
    echo json_encode(["message" => "Erreur interne du serveur"]);
    // Optionnel : Vous pouvez logger $e->getMessage() dans un fichier de log pour le débogage
}



?>
