<?php
require_once '../config.php';
require_once '../includes/db.php';
require_once '../includes/auth.php';

// 🔐 Protection
requireLogin();
requireRole('teacher');

$error = "";
$success = "";

$id = $_SESSION['user_id'];

// Fetch teacher data
$teacher = fetch("SELECT * FROM users WHERE id = ?", [$id]);

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
            $teacher = fetch("SELECT * FROM users WHERE id = ?", [$id]);
        }
    }
}

// Page title
$title = "My Profile";
require_once '../includes/header.php';
?>

<?php require_once '../includes/sidebar_teacher.php'; ?>

<h2 class="mb-4">My Profile</h2>

<div class="card shadow p-4">

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="POST">

        <div class="mb-3">
            <label class="form-label">Full Name</label>
            <input type="text" name="name" class="form-control"
                   value="<?= htmlspecialchars($teacher['name']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control"
                   value="<?= htmlspecialchars($teacher['email']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">New Password (optional)</label>
            <input type="password" name="password" class="form-control">
        </div>

        <button class="btn btn-primary">Update Profile</button>

    </form>

</div>

<?php
echo "</div></div>";
require_once '../includes/footer.php';
?>