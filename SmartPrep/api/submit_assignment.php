<?php
require_once '../config.php';
require_once '../includes/db.php';
require_once '../includes/auth.php';

header("Content-Type: application/json");

// 🔐 Check login
if (!isLoggedIn() || $_SESSION['role'] !== 'student') {
    echo json_encode([
        "status" => "error",
        "message" => "Unauthorized access"
    ]);
    exit;
}

$student_id = $_SESSION['user_id'];

// Get JSON input
$data = json_decode(file_get_contents("php://input"), true);

$assignment_id = (int) ($data['assignment_id'] ?? 0);
$content       = trim($data['content'] ?? '');

// Validation
if (empty($assignment_id) || empty($content)) {
    echo json_encode([
        "status" => "error",
        "message" => "All fields are required"
    ]);
    exit;
}

// Check duplicate
$exists = fetch(
    "SELECT id FROM submissions WHERE assignment_id = ? AND student_id = ?",
    [$assignment_id, $student_id]
);

if ($exists) {
    echo json_encode([
        "status" => "error",
        "message" => "You already submitted this assignment"
    ]);
    exit;
}

// Insert submission
executeQuery(
    "INSERT INTO submissions (assignment_id, student_id, content)
     VALUES (?, ?, ?)",
    [$assignment_id, $student_id, $content]
);

echo json_encode([
    "status" => "success",
    "message" => "Assignment submitted successfully"
]);