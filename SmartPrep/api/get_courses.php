<?php
require_once '../config.php';
require_once '../includes/db.php';

header("Content-Type: application/json");

try {

    $courses = fetchAll("
        SELECT id, name, code 
        FROM courses 
        ORDER BY name ASC
    ");

    echo json_encode([
        "status" => "success",
        "data" => $courses
    ]);

} catch (Exception $e) {

    echo json_encode([
        "status" => "error",
        "message" => "Failed to fetch courses"
    ]);
}