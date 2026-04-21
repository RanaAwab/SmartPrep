<?php
require_once '../config.php';
require_once '../includes/db.php';
require_once '../includes/auth.php';

// 🔐 Protection
requireLogin();
requireRole('admin');

$error = "";
$success = "";

// Handle form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);

    if (empty($name)) {
        $error = "Department name is required!";
    } else {
        // Check duplicate
        $existing = fetch("SELECT id FROM departments WHERE name = ?", [$name]);

        if ($existing) {
            $error = "Department already exists!";
        } else {
            $insert = executeQuery(
                "INSERT INTO departments (name) VALUES (?)",
                [$name]
            );

            if ($insert) {
                $success = "Department added successfully!";
            } else {
                $error = "Something went wrong!";
            }
        }
    }
}

// Page title
$title = "Add Department";
require_once '../includes/header.php';
?>

<style>
.form-card {
    max-width: 600px;
    margin: 0 auto;
    border-radius: 16px;
    border: 1px solid rgba(0,0,0,0.03);
    box-shadow: 0 10px 30px rgba(0,0,0,0.02);
    padding: 35px 40px;
    background: #ffffff;
}
.form-label-custom {
    font-weight: 600;
    color: #475569;
    margin-bottom: 8px;
    font-size: 0.95rem;
}
.form-control-custom {
    display: block;
    width: 100%;
    padding: 12px 16px;
    font-size: 1rem;
    color: #334155;
    background-color: #f8fafc;
    border: 1.5px solid #e2e8f0;
    border-radius: 12px;
    transition: all 0.3s ease;
}
.form-control-custom:focus {
    background-color: #fff;
    border-color: #3b82f6;
    outline: 0;
    box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15);
}
.btn-save {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
    font-weight: 600;
    padding: 12px 28px;
    border-radius: 12px;
    border: none;
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.25);
    transition: all 0.2s ease;
}
.btn-save:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 18px rgba(16, 185, 129, 0.35);
    color: white;
}
.page-top-link {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: #64748b;
    text-decoration: none;
    font-weight: 600;
    margin-bottom: 25px;
    transition: all 0.2s;
}
.page-top-link:hover {
    color: #0f172a;
    transform: translateX(-3px);
}
.dash-title {
    font-weight: 800;
    color: #0f172a;
    font-size: 1.8rem;
    margin-bottom: 30px;
    text-align: center;
}
.alert-custom {
    border-radius: 12px;
    padding: 14px 16px;
    font-size: 0.95rem;
    border: none;
    margin-bottom: 25px;
}
.alert-custom-error { background-color: #fef2f2; color: #991b1b; border-left: 4px solid #ef4444; }
.alert-custom-success { background-color: #f0fdf4; color: #166534; border-left: 4px solid #22c55e; }
</style>

<?php require_once '../includes/sidebar_admin.php'; ?>

<a href="manage_departments.php" class="page-top-link">
    <i class="bi bi-arrow-left"></i> Back to Departments
</a>

<div class="form-card">
    <h2 class="dash-title">Add Department</h2>

    <?php if ($error): ?>
        <div class="alert alert-danger alert-custom alert-custom-error d-flex align-items-center">
            <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
            <div><?= htmlspecialchars($error) ?></div>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="alert alert-success alert-custom alert-custom-success d-flex align-items-center">
            <i class="bi bi-check-circle-fill me-2 fs-5"></i>
            <div><?= htmlspecialchars($success) ?></div>
        </div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-4">
            <label class="form-label-custom">Department Name</label>
            <input type="text" name="name" class="form-control-custom" placeholder="e.g. Computer Science" required>
        </div>

        <div class="text-center">
            <button class="btn-save w-100">Create Department</button>
        </div>
    </form>
</div>

<?php
echo "</div></div>";
require_once '../includes/footer.php';
?>