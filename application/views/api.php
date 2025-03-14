<?php
header('content-type:application/json');


if (empty($api)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'No data was found'
    ]);  
} else {
    
    echo json_encode($api);
}



