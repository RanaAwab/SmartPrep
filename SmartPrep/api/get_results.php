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

try {

    // 🎓 Academic Results
    $academic = fetchAll("
        SELECT s.name AS subject, r.marks
        FROM results r
        JOIN subjects s ON r.subject_id = s.id
        WHERE r.student_id = ?
    ", [$student_id]);

    // 🧠 Quiz Results
    $quiz = fetchAll("
        SELECT q.title, qr.score
        FROM quiz_results qr
        JOIN quizzes q ON qr.quiz_id = q.id
        WHERE qr.student_id = ?
    ", [$student_id]);

    echo json_encode([
        "status" => "success",
        "academic" => $academic,
        "quiz" => $quiz
    ]);

} catch (Exception $e) {

    echo json_encode([
        "status" => "error",
        "message" => "Failed to fetch results"
    ]);
}