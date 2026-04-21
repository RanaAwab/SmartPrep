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

// Handle upload
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject_id = (int) $_POST['subject_id'];
    $title      = trim($_POST['title']);

    if (empty($subject_id) || empty($title) || empty($_FILES['file']['name'])) {
        $error = "All fields are required!";
    } else {
        $file = $_FILES['file'];
        $allowed = ['pdf','doc','docx','mp4','ppt','pptx'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!in_array($ext, $allowed)) {
            $error = "Invalid file type!";
        } else {
            // Check if uploads dir exists, if not create it
            $uploads_dir = "../uploads/";
            if (!is_dir($uploads_dir)) {
                mkdir($uploads_dir, 0777, true);
            }

            $filename = time() . "_" . basename($file['name']);
            $path = $uploads_dir . $filename;

            if (move_uploaded_file($file['tmp_name'], $path)) {
                executeQuery(
                    "INSERT INTO lectures (subject_id, teacher_id, title, file)
                     VALUES (?, ?, ?, ?)",
                    [$subject_id, $teacher_id, $title, $filename]
                );
                $success = "Lecture material uploaded successfully!";
            } else {
                $error = "Upload failed! Check permissions.";
            }
        }
    }
}

// Page title
$title = "Upload Lecture";
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
input[type=file]::file-selector-button {
    background-color: #e2e8f0;
    border: none;
    padding: 8px 16px;
    border-radius: 8px;
    color: #334155;
    font-weight: 600;
    font-family: 'Outfit', sans-serif;
    margin-right: 15px;
    cursor: pointer;
    transition: all 0.2s;
}
input[type=file]::file-selector-button:hover {
    background-color: #cbd5e1;
}
</style>

<?php require_once '../includes/sidebar_teacher.php'; ?>

<div class="form-card mt-4">
    <h2 class="dash-title">Upload Course Lecture</h2>

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

    <form method="POST" enctype="multipart/form-data">
        <div class="mb-4">
            <label class="form-label-custom">Related Subject</label>
            <select name="subject_id" class="form-control-custom" required>
                <option value="" disabled selected>-- Select Subject --</option>
                <?php foreach ($subjects as $s): ?>
                    <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-4">
            <label class="form-label-custom">Lecture Topic / Title</label>
            <input type="text" name="title" class="form-control-custom" placeholder="e.g. Intro to Computer Science" required>
        </div>

        <div class="mb-4">
            <label class="form-label-custom">Material File</label>
            <input type="file" name="file" class="form-control-custom" accept=".pdf,.doc,.docx,.ppt,.pptx,.mp4" required>
            <div class="form-text mt-2 text-muted fw-medium small">Allowed: PDF, DOC, PPT, MP4. Do not exceed 20MB.</div>
        </div>

        <div class="text-center mt-2">
            <button class="btn-save w-100"><i class="bi bi-cloud-arrow-up-fill me-2"></i> Upload Lecture</button>
        </div>
    </form>
</div>

<?php
echo "</div></div>";
require_once '../includes/footer.php';
?>