<?php
require_once '../config.php';
require_once '../includes/db.php';

header("Content-Type: application/json");

try {

    $assignments = fetchAll("
        SELECT a.id, a.title, a.description, a.deadline, s.name AS subject
        FROM assignments a
        JOIN subjects s ON a.subject_id = s.id
        ORDER BY a.deadline ASC
    ");

    echo json_encode([
        "status" => "success",
        "data" => $assignments
    ]);

} catch (Exception $e) {

    echo json_encode([
        "status" => "error",
        "message" => "Failed to fetch assignments"
    ]);
}