<?php
require_once '../config.php';
require_once '../includes/db.php';

header("Content-Type: application/json");

// Get JSON data
$data = json_decode(file_get_contents("php://input"), true);

$name     = trim($data['name'] ?? '');
$email    = trim($data['email'] ?? '');
$password = trim($data['password'] ?? '');
$role     = $data['role'] ?? '';

// Validation
if (empty($name) || empty($email) || empty($password) || empty($role)) {
    echo json_encode([
        "status" => "error",
        "message" => "All fields are required"
    ]);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid email"
    ]);
    exit;
}

if (strlen($password) < 6) {
    echo json_encode([
        "status" => "error",
        "message" => "Password must be at least 6 characters"
    ]);
    exit;
}

if (!in_array($role, ['student', 'teacher'])) {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid role"
    ]);
    exit;
}

// Check existing
$existing = fetch("SELECT id FROM users WHERE email = ?", [$email]);

if ($existing) {
    echo json_encode([
        "status" => "error",
        "message" => "Email already exists"
    ]);
    exit;
}

// Insert user (pending approval)
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

executeQuery(
    "INSERT INTO users (name, email, password, role, status)
     VALUES (?, ?, ?, ?, 'pending')",
    [$name, $email, $hashedPassword, $role]
);

echo json_encode([
    "status" => "success",
    "message" => "Registered successfully! Wait for admin approval."
]);