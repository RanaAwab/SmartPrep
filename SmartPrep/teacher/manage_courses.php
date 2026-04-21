<?php
require_once '../config.php';
require_once '../includes/db.php';
require_once '../includes/auth.php';

// 🔐 Protection
requireLogin();
requireRole('teacher');

$teacher_id = $_SESSION['user_id'];

// Fetch assigned courses
$courses = fetchAll("
    SELECT c.id, c.name, c.code
    FROM teacher_course tc
    JOIN courses c ON tc.course_id = c.id
    WHERE tc.teacher_id = ?
    ORDER BY c.name ASC
", [$teacher_id]);

// Page title
$title = "My Courses";
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
.table-card {
    border-radius: 16px;
    border: 1px solid rgba(0,0,0,0.03);
    box-shadow: 0 10px 30px rgba(0,0,0,0.02);
    padding: 25px;
    background: #ffffff;
}
.badge-code {
    background: #f1f5f9;
    color: #475569;
    padding: 6px 12px;
    border-radius: 6px;
    font-family: monospace;
    font-weight: 600;
    letter-spacing: 0.5px;
}
</style>

<?php require_once '../includes/sidebar_teacher.php'; ?>

<div class="dash-header">
    <h2 class="dash-title">My Assigned Courses</h2>
</div>

<div class="table-card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th width="10%" class="text-center">Ref ID</th>
                    <th>Course Name</th>
                    <th>Course Code</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($courses): ?>
                    <?php foreach ($courses as $course): ?>
                        <tr>
                            <td class="text-center text-muted fw-medium">#<?= $course['id'] ?></td>
                            <td class="fw-semibold text-dark">
                                <i class="bi bi-journal-bookmark text-amber-500 text-warning me-2"></i><?= htmlspecialchars($course['name']) ?>
                            </td>
                            <td><span class="badge-code"><?= htmlspecialchars($course['code']) ?></span></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" class="text-center text-muted py-4">No courses currently assigned.</td>
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