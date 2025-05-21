<?php
require_once 'pc_functions.php';

if (!isset($_GET['pc']) || empty($_GET['pc'])) {
    http_response_code(400);
    echo json_encode(["error" => "Le paramètre 'pc' est requis."]);
    exit;
}

$nomPc = $_GET['pc'];

$pc = get_Infos_cons_main($nomPc);
$rapport = get_rapport_by_machine($nomPc);

if (!empty($pc)) {
    $response = [
        'pc' => $pc,
        'rapport' => $rapport,
        'success' => true
    ];
    header('Content-Type: application/json');
    header('Content-Type: application/json');
    header("Access-Control-Allow-Origin: *");
    header('Access-Control-Allow-Methods: GET, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');
    header('Access-Control-Allow-Credentials: true');
    echo json_encode($response);
} else {
    echo json_encode(["error" => "Aucun PC trouvé avec ce nom."]);
}
?>
