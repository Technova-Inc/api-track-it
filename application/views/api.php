<?php
header('content-type:application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");



if (empty($api)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'No data was found'
    ]);  
} else {
    
    echo json_encode($api);
}



