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

// Fetch students
$students = fetchAll("SELECT id, name FROM users WHERE role = 'student'");

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
    $marks = $_POST['marks'] ?? [];

    if (empty($subject_id)) {
        $error = "Subject is required!";
    } else {
        foreach ($marks as $student_id => $score) {
            if ($score === '') continue;

            // Check existing result
            $exists = fetch(
                "SELECT id FROM results WHERE student_id = ? AND subject_id = ?",
                [$student_id, $subject_id]
            );

            if ($exists) {
                // UPDATE
                executeQuery(
                    "UPDATE results 
                     SET marks = ?, teacher_id = ? 
                     WHERE student_id = ? AND subject_id = ?",
                    [$score, $teacher_id, $student_id, $subject_id]
                );
            } else {
                // INSERT
                executeQuery(
                    "INSERT INTO results (student_id, subject_id, teacher_id, marks)
                     VALUES (?, ?, ?, ?)",
                    [$student_id, $subject_id, $teacher_id, $score]
                );
            }
        }
        $success = "Results saved successfully!";
    }
}

// Fetch previous results for selected subject
$existingResults = [];
if (isset($_GET['subject_id'])) {
    $sub_id = (int) $_GET['subject_id'];
    $records = fetchAll("SELECT student_id, marks FROM results WHERE subject_id = ?", [$sub_id]);
    $existingResults = array_column($records, 'marks', 'student_id');
}

// Page title
$title = "Upload Results";
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
.form-card {
    border-radius: 16px;
    border: 1px solid rgba(0,0,0,0.03);
    box-shadow: 0 10px 30px rgba(0,0,0,0.02);
    padding: 30px;
    background: #ffffff;
}
.form-label-custom {
    font-weight: 600;
    color: #475569;
    margin-bottom: 8px;
    font-size: 0.95rem;
}
select.form-control-custom, input.form-control-custom {
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
select.form-control-custom:focus, input.form-control-custom:focus {
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
.alert-custom {
    border-radius: 12px;
    padding: 14px 16px;
    font-size: 0.95rem;
    border: none;
    margin-bottom: 25px;
}
.alert-custom-error { background-color: #fef2f2; color: #991b1b; border-left: 4px solid #ef4444; }
.alert-custom-success { background-color: #f0fdf4; color: #166534; border-left: 4px solid #22c55e; }
.input-grade {
    max-width: 120px;
    margin: 0 auto;
    text-align: center;
    font-weight: 700;
    color: #0f172a;
}
</style>

<?php require_once '../includes/sidebar_teacher.php'; ?>

<div class="dash-header">
    <h2 class="dash-title">Upload Final Grades</h2>
</div>

<div class="form-card">
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

    <form method="GET" class="mb-5">
        <label class="form-label-custom">Select Subject to Grade</label>
        <select name="subject_id" class="form-control-custom w-50" required onchange="this.form.submit()">
            <option value="" disabled selected>-- Choose Subject --</option>
            <?php foreach ($subjects as $s): ?>
                <option value="<?= $s['id'] ?>" <?= (isset($_GET['subject_id']) && $_GET['subject_id'] == $s['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($s['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>

    <?php if (isset($_GET['subject_id'])): ?>
    <form method="POST">
        <input type="hidden" name="subject_id" value="<?= htmlspecialchars($_GET['subject_id']) ?>">
        
        <h5 class="fw-bold mb-3 text-slate-800"><i class="bi bi-award-fill text-amber-500 me-2"></i> Grade Roster</h5>
        
        <div class="table-responsive border rounded-3 mb-4">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Student Name</th>
                        <th class="text-center" width="30%">Marks (Out of 100)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($students) > 0): ?>
                        <?php foreach ($students as $student): ?>
                            <tr>
                                <td class="ps-4 fw-semibold text-dark"><?= htmlspecialchars($student['name']) ?></td>
                                <td class="text-center">
                                    <input type="number" name="marks[<?= $student['id'] ?>]" class="form-control-custom input-grade" min="0" max="100" placeholder="0 - 100" value="<?= htmlspecialchars($existingResults[$student['id']] ?? '') ?>">
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="2" class="text-center text-muted py-3">No students found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="text-end">
            <button class="btn-save px-5">
                <i class="bi bi-journal-text me-2"></i> Save / Update Grades
            </button>
        </div>
    </form>
    <?php endif; ?>
</div>

<?php
echo "</div></div>";
require_once '../includes/footer.php';
?>