<?php
require_once '../config.php';
require_once '../includes/db.php';
require_once '../includes/auth.php';

// 🔐 Protection
requireLogin();
requireRole('admin');

$error = "";
$success = "";

// Get ID
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// Fetch course
$course = fetch("SELECT * FROM courses WHERE id = ?", [$id]);

if (!$course) {
    die("Course not found!");
}

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $code = strtoupper(trim($_POST['code']));

    if (empty($name) || empty($code)) {
        $error = "All fields are required!";
    } elseif (strlen($code) < 2) {
        $error = "Course code is too short!";
    } else {
        // Check duplicate (exclude current)
        $existing = fetch(
            "SELECT id FROM courses WHERE code = ? AND id != ?",
            [$code, $id]
        );

        if ($existing) {
            $error = "Course code already exists!";
        } else {
            executeQuery(
                "UPDATE courses SET name = ?, code = ? WHERE id = ?",
                [$name, $code, $id]
            );

            $success = "Course updated successfully!";
            $course = fetch("SELECT * FROM courses WHERE id = ?", [$id]);
        }
    }
}

// Page title
$title = "Edit Course";
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
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: white;
    font-weight: 600;
    padding: 12px 28px;
    border-radius: 12px;
    border: none;
    box-shadow: 0 4px 12px rgba(245, 158, 11, 0.25);
    transition: all 0.2s ease;
}
.btn-save:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 18px rgba(245, 158, 11, 0.35);
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

<a href="manage_courses.php" class="page-top-link">
    <i class="bi bi-arrow-left"></i> Back to Courses
</a>

<div class="form-card">
    <h2 class="dash-title">Edit Course</h2>

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
            <label class="form-label-custom">Course Name</label>
            <input type="text" name="name" class="form-control-custom"
                   value="<?= htmlspecialchars($course['name']) ?>" required>
        </div>
        
        <div class="mb-4">
            <label class="form-label-custom">Course Code</label>
            <input type="text" name="code" class="form-control-custom"
                   value="<?= htmlspecialchars($course['code']) ?>" required>
        </div>

        <div class="text-center">
            <button class="btn-save w-100">Update Course</button>
        </div>
    </form>
</div>

<?php
echo "</div></div>";
require_once '../includes/footer.php';
?>