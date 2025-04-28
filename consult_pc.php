<?php
require_once 'pc_functions.php';

if (!isset($_GET['pc']) || empty($_GET['pc'])) {
    http_response_code(400);
    echo json_encode(["error" => "Le paramètre 'pc' est requis."]);
    exit;
}

$nomPc = $_GET['pc'];

$pc = get_Infos_cons_main($nomPc);

if (!empty($pc)) {
    $response = [
        'api' => [
            'pc' => $pc
        ]
    ];
    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    echo json_encode(["error" => "Aucun PC trouvé avec ce nom."]);
}
?>
