<?php
require_once '../config.php';
require_once '../includes/db.php';
require_once '../includes/auth.php';

// 🔐 Protection
requireLogin();
requireRole('teacher');

$teacher_id = $_SESSION['user_id'];
$error = "";
$success = "";

// Fetch subjects assigned to teacher
$subjects = fetchAll("
    SELECT s.id, s.name 
    FROM subjects s
    JOIN courses c ON s.course_id = c.id
    JOIN teacher_course tc ON tc.course_id = c.id
    WHERE tc.teacher_id = ?
", [$teacher_id]);

// Handle form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject_id = (int) $_POST['subject_id'];
    $title      = trim($_POST['title']);
    $description= trim($_POST['description']);
    $deadline   = $_POST['deadline'];

    if (empty($subject_id) || empty($title) || empty($deadline)) {
        $error = "All required fields must be filled!";
    } else {
        $insert = executeQuery(
            "INSERT INTO assignments (subject_id, teacher_id, title, description, deadline)
             VALUES (?, ?, ?, ?, ?)",
            [$subject_id, $teacher_id, $title, $description, $deadline]
        );

        if ($insert) {
            $success = "Assignment uploaded successfully!";
        } else {
            $error = "Something went wrong!";
        }
    }
}

// Page title
$title = "Upload Assignment";
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
select.form-control-custom {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23334155' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right 1rem center;
    background-size: 16px 12px;
    padding-right: 2.5rem;
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
}
.btn-save {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    color: white;
    font-weight: 600;
    padding: 12px 28px;
    border-radius: 12px;
    border: none;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.25);
    transition: all 0.2s ease;
}
.btn-save:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 18px rgba(59, 130, 246, 0.35);
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
textarea.form-control-custom {
    min-height: 120px;
    resize: vertical;
}
</style>

<?php require_once '../includes/sidebar_teacher.php'; ?>

<a href="manage_assignment.php" class="page-top-link">
    <i class="bi bi-arrow-left"></i> View All Assignments
</a>

<div class="form-card">
    <h2 class="dash-title">Draft Assignment</h2>

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
            <label class="form-label-custom">Select Subject</label>
            <select name="subject_id" class="form-control-custom" required>
                <option value="" disabled selected>-- Choose Subject --</option>
                <?php foreach ($subjects as $s): ?>
                    <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-4">
            <label class="form-label-custom">Assignment Title</label>
            <input type="text" name="title" class="form-control-custom" placeholder="e.g. Chapter 4 Integration Practice" required>
        </div>

        <div class="mb-4">
            <label class="form-label-custom">Description / Instructions</label>
            <textarea name="description" class="form-control-custom" placeholder="Provide detailed instructions..."></textarea>
        </div>

        <div class="mb-4">
            <label class="form-label-custom">Due Deadline</label>
            <input type="date" name="deadline" class="form-control-custom" required>
        </div>

        <div class="text-center mt-2">
            <button class="btn-save w-100">Publish Assignment</button>
        </div>
    </form>
</div>

<?php
echo "</div></div>";
require_once '../includes/footer.php';
?>