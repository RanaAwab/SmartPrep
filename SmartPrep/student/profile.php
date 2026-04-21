<?php
require_once '../config.php';
require_once '../includes/db.php';
require_once '../includes/auth.php';

// 🔐 Protection
requireLogin();
requireRole('student');

$error = "";
$success = "";

$id = $_SESSION['user_id'];

// Fetch student data
$student = fetch("SELECT * FROM users WHERE id = ?", [$id]);

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($name) || empty($email)) {
        $error = "Name and email are required!";
    }
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email!";
    }
    else {

        // Check duplicate email
        $existing = fetch(
            "SELECT id FROM users WHERE email = ? AND id != ?",
            [$email, $id]
        );

        if ($existing) {
            $error = "Email already in use!";
        } else {

            if (!empty($password)) {

                if (strlen($password) < 6) {
                    $error = "Password must be at least 6 characters!";
                } else {

                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                    executeQuery(
                        "UPDATE users SET name = ?, email = ?, password = ? WHERE id = ?",
                        [$name, $email, $hashedPassword, $id]
                    );

                    $success = "Profile updated!";
                }

            } else {

                executeQuery(
                    "UPDATE users SET name = ?, email = ? WHERE id = ?",
                    [$name, $email, $id]
                );

                $success = "Profile updated!";
            }

            // Refresh data
            $student = fetch("SELECT * FROM users WHERE id = ?", [$id]);
        }
    }
}

// Page title
$title = "My Profile";
require_once '../includes/header.php';
?>

<?php require_once '../includes/sidebar_student.php'; ?>

<div class="dashboard-wrapper">
    <div class="dash-header">
        <div>
            <h1 class="dash-title">My Profile</h1>
            <div class="dash-subtitle">Manage your personal information and credentials.</div>
        </div>
    </div>

    <div class="content-card" style="max-width: 700px; margin: auto;">
        
        <div class="text-center mb-5 mt-3">
            <div class="bg-primary bg-opacity-10 d-inline-flex rounded-circle p-4 mb-3 text-primary">
                <i class="bi bi-person-circle fs-1"></i>
            </div>
            <h4 class="fw-bold text-dark"><?= htmlspecialchars($student['name']) ?></h4>
            <p class="text-muted"><?= htmlspecialchars($student['email']) ?></p>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-danger border-0 border-start border-4 border-danger fw-medium d-flex align-items-center gap-2">
                <i class="bi bi-exclamation-octagon-fill"></i> <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success border-0 border-start border-4 border-success fw-medium d-flex align-items-center gap-2">
                <i class="bi bi-check-circle-fill"></i> <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-4">
                <label class="form-label fw-bold text-dark">Full Name</label>
                <div class="input-group drop-shadow-sm">
                    <span class="input-group-text bg-light border-light text-muted"><i class="bi bi-person"></i></span>
                    <input type="text" name="name" class="form-control border-light shadow-none bg-light"
                           value="<?= htmlspecialchars($student['name']) ?>" required>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-bold text-dark">Email Address</label>
                <div class="input-group drop-shadow-sm">
                    <span class="input-group-text bg-light border-light text-muted"><i class="bi bi-envelope"></i></span>
                    <input type="email" name="email" class="form-control border-light shadow-none bg-light"
                           value="<?= htmlspecialchars($student['email']) ?>" required>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-bold text-dark">New Password <span class="text-muted fw-normal fs-6">(Optional)</span></label>
                <div class="input-group drop-shadow-sm">
                    <span class="input-group-text bg-light border-light text-muted"><i class="bi bi-lock"></i></span>
                    <input type="password" name="password" class="form-control border-light shadow-none bg-light" placeholder="Leave blank to keep current password">
                </div>
            </div>

            <div class="mt-5">
                <button class="btn-action btn-action-success w-100 justify-content-center text-decoration-none border-0 shadow-sm">
                    <i class="bi bi-save-fill"></i> Update Profile
                </button>
            </div>
        </form>
    </div>
</div>

<style>
.drop-shadow-sm { box-shadow: 0 2px 10px rgba(0,0,0,0.02); }
</style>

<?php
echo "</div></div>";
require_once '../includes/footer.php';
?>