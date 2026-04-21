<?php
require_once '../config.php';
require_once '../includes/db.php';
require_once '../includes/auth.php';

// 🔐 Protection
requireLogin();
requireRole('admin');

$error = "";
$success = "";

// Fetch teachers & courses
$teachers = fetchAll("SELECT id, name FROM users WHERE role = 'teacher'");
$courses  = fetchAll("SELECT id, name FROM courses");

// Handle assignment
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $teacher_id = (int) $_POST['teacher_id'];
    $course_id  = (int) $_POST['course_id'];

    if (empty($teacher_id) || empty($course_id)) {
        $error = "All fields are required!";
    } else {
        // Prevent duplicate assignment
        $existing = fetch(
            "SELECT id FROM teacher_course WHERE teacher_id = ? AND course_id = ?",
            [$teacher_id, $course_id]
        );

        if ($existing) {
            $error = "This assignment already exists!";
        } else {
            $insert = executeQuery(
                "INSERT INTO teacher_course (teacher_id, course_id) VALUES (?, ?)",
                [$teacher_id, $course_id]
            );

            if ($insert) {
                $success = "Teacher assigned successfully!";
            } else {
                $error = "Something went wrong!";
            }
        }
    }
}

// Fetch assignments
$assignments = fetchAll("
    SELECT tc.id, u.name AS teacher_name, c.name AS course_name
    FROM teacher_course tc
    JOIN users u ON tc.teacher_id = u.id
    JOIN courses c ON tc.course_id = c.id
    ORDER BY tc.id DESC
");

// Page title
$title = "Assign Teacher";
require_once '../includes/header.php';
?>

<style>
.dash-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
    padding-bottom: 15px;
    border-bottom: 1px solid #e2e8f0;
}
.dash-title {
    font-weight: 800;
    color: #0f172a;
    font-size: 2rem;
    margin: 0;
}
.assignment-card {
    border-radius: 16px;
    border: 1px solid rgba(0,0,0,0.03);
    box-shadow: 0 10px 30px rgba(0,0,0,0.02);
    padding: 30px;
    background: #ffffff;
    margin-bottom: 30px;
}
.form-label-custom {
    font-weight: 600;
    color: #475569;
    margin-bottom: 8px;
    font-size: 0.95rem;
}
select.form-control-custom {
    display: block;
    width: 100%;
    padding: 12px 16px;
    font-size: 1rem;
    color: #334155;
    background-color: #f8fafc;
    border: 1.5px solid #e2e8f0;
    border-radius: 12px;
    transition: all 0.3s ease;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23334155' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right 1rem center;
    background-size: 16px 12px;
    padding-right: 2.5rem;
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
}
select.form-control-custom:focus {
    background-color: #fff;
    border-color: #3b82f6;
    outline: 0;
    box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15);
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
    height: 100%;
}
.btn-save:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 18px rgba(59, 130, 246, 0.35);
    color: white;
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
.table-card {
    border-radius: 16px;
    border: 1px solid rgba(0,0,0,0.03);
    box-shadow: 0 10px 30px rgba(0,0,0,0.02);
    padding: 25px;
    background: #ffffff;
}
</style>

<?php require_once '../includes/sidebar_admin.php'; ?>

<div class="dash-header">
    <h2 class="dash-title">Assign Teacher to Course</h2>
</div>

<div class="assignment-card">
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

    <form method="POST" class="row g-4 align-items-end">
        <div class="col-md-5">
            <label class="form-label-custom">Select Teacher</label>
            <select name="teacher_id" class="form-control-custom" required>
                <option value="" disabled selected>-- Choose a Teacher --</option>
                <?php foreach ($teachers as $t): ?>
                    <option value="<?= $t['id'] ?>"><?= htmlspecialchars($t['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-5">
            <label class="form-label-custom">Select Course</label>
            <select name="course_id" class="form-control-custom" required>
                <option value="" disabled selected>-- Choose a Course --</option>
                <?php foreach ($courses as $c): ?>
                    <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-2">
            <button class="btn-save w-100">Assign</button>
        </div>
    </form>
</div>

<!-- Assigned List -->
<div class="table-card">
    <h5 class="fw-bold mb-4 text-slate-800"><i class="bi bi-link-45deg me-2 text-primary"></i> Current Assignments</h5>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th width="10%" class="text-center">Ref ID</th>
                    <th>Teacher Name</th>
                    <th>Assigned Course</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($assignments): ?>
                    <?php foreach ($assignments as $a): ?>
                        <tr>
                            <td class="text-center text-muted fw-medium">#<?= $a['id'] ?></td>
                            <td class="fw-semibold text-dark">
                                <i class="bi bi-person-fill text-secondary me-2"></i><?= htmlspecialchars($a['teacher_name']) ?>
                            </td>
                            <td class="text-secondary">
                                <i class="bi bi-journal-check text-primary me-2"></i><?= htmlspecialchars($a['course_name']) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" class="text-center text-muted py-4">No teachers have been assigned to courses yet.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
echo "</div></div>";
require_once '../includes/footer.php';
?>