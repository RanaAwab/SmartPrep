<?php
require_once '../config.php';
require_once '../includes/db.php';
require_once '../includes/auth.php';

header("Content-Type: application/json");

// Get JSON input
$data = json_decode(file_get_contents("php://input"), true);

$email = trim($data['email'] ?? '');
$password = trim($data['password'] ?? '');

if (empty($email) || empty($password)) {
    echo json_encode([
        "status" => "error",
        "message" => "All fields are required"
    ]);
    exit;
}

// Fetch user
$user = fetch("SELECT * FROM users WHERE email = ?", [$email]);

if (!$user || !password_verify($password, $user['password'])) {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid email or password"
    ]);
    exit;
}

// Check approval
if ($user['status'] !== 'approved') {
    echo json_encode([
        "status" => "error",
        "message" => "Account not approved yet"
    ]);
    exit;
}

// Login user
loginUser($user);

// Redirect by role
$redirect = '';

if ($user['role'] === 'admin') {
    $redirect = base_url('admin/dashboard.php');
} elseif ($user['role'] === 'teacher') {
    $redirect = base_url('teacher/dashboard.php');
} else {
    $redirect = base_url('student/dashboard.php');
}

echo json_encode([
    "status" => "success",
    "message" => "Login successful",
    "redirect" => $redirect
]);