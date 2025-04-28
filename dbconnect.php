<?php
$server = "mysql-jouin-n.alwaysdata.net";
$nomBD = "jouin-n_ocs-trackit";
$login = "jouin-n_ocs";
$psw = "Nicolas.277";

try {
    $pdo = new PDO("mysql:host=$server;dbname=$nomBD", $login, $psw);
} catch (Exception $e) {
    echo 'Erreur de connexion : ' . $e->getMessage();
    exit();
}
?>