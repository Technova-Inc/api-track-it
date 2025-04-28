<?php
require_once '../dbconnect.php';

function get_notes_by_pc($pc_name) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT NOTE FROM hardware WHERE NAME = ?");
    $stmt->execute([$pc_name]);
    return $stmt->fetchAll();
}

function insert_note_for_pc($pc_name, $note) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE hardware SET NOTE = ? WHERE NAME = ?");
    $stmt->execute([$note, $pc_name]);
}
?>
