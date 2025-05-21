<?php
// Lire les données JSON
require_once '../Configuration/dbconnect.php';

// Vérifier le Content-Type
$contentType = $_SERVER['CONTENT_TYPE'] ?? '';
if (stripos($contentType, 'application/json') === false) {
    http_response_code(415);
    echo json_encode(["error" => "Type de contenu non pris en charge, attendu application/json."]);
    exit;
}

$rawInput = file_get_contents('php://input');

// Pour debug, décommente la ligne suivante pour voir ce qui est reçu
// file_put_contents('debug_input.txt', $rawInput);

$input = json_decode($rawInput, true);

// Vérifier que les données JSON sont valides
if ($input === null) {
    http_response_code(400);
    echo json_encode(["error" => "JSON invalide."]);
    exit;
}


// Vérifier les sous-champs attendus
if (
    !isset($rapport['Risques']) ||
    !isset($rapport['ScoreSécurité']) ||
    !isset($rapport['InfosSysteme'])
) {
    http_response_code(400);
    echo json_encode(["error" => "Champs manquants dans 'rapport'."]);
    exit;
}

$infos = $rapport['InfosSysteme'];

$nomMachine = $infos['NomMachine'] ?? null;
$utilisateur = $infos['Utilisateur'] ?? null;
$dateRapport = $infos['Date'] ?? null;
$architecture = $infos['Architecture'] ?? null;
$build = $infos['Build'] ?? null;
$uptime = $infos['Uptime_H'] ?? null;

// Nettoyage du score : extraire uniquement la partie numérique
preg_match('/\d+/', $rapport['ScoreSécurité'], $matches);
$score = isset($matches[0]) ? intval($matches[0]) : null;

// Conversion des risques en chaîne JSON ou texte (selon DB)
$risques = json_encode($rapport['Risques'], JSON_UNESCAPED_UNICODE);

try {
    // Insertion dans la table Rapport
    $stmt = $pdo->prepare("
        INSERT INTO Rapport (
            NomMachine, Utilisateur, DateRapport, Architecture,
            Build, UptimeHeures, ScoreSecurite, Risques
        ) VALUES (
            :NomMachine, :Utilisateur, :DateRapport, :Architecture,
            :Build, :UptimeHeures, :ScoreSecurite, :Risques
        )
    ");

    $stmt->execute([
        'NomMachine'     => $nomMachine,
        'Utilisateur'    => $utilisateur,
        'DateRapport'    => $dateRapport,
        'Architecture'   => $architecture,
        'Build'          => $build,
        'UptimeHeures'   => $uptime,
        'ScoreSecurite'  => $score,
        'Risques'        => $risques
    ]);

    http_response_code(200);
    echo json_encode(["success" => true, "message" => "Rapport inséré avec succès."]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => "Erreur lors de l'insertion du rapport: " . $e->getMessage()]);
}
?>
