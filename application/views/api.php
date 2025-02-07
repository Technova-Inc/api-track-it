<?php
header('content-type:application/json');
// echo json_encode($pc);
// echo json_encode($software);
// echo json_encode($network);
// echo json_encode($windows);
// echo json_encode($unix);
// echo json_encode($android);

// Si le tableau $data est vide, on ne renvoie rien
if (empty($data)) {
    echo json_encode([]);  // Retourne un tableau vide en JSON
} else {
    // Encodez toutes les données en JSON et envoyez-les
    echo json_encode($data);
}
?>


?>