<?php
require_once '../config.php';
require_once '../includes/db.php';
require_once '../includes/auth.php';

// 🔐 Protection
requireLogin();
requireRole('teacher');

// Fetch students
$students = fetchAll("
    SELECT id, name, email
    FROM users
    WHERE role = 'student'
    ORDER BY name ASC
");

// Page title
$title = "Student List";
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
</style>

<?php require_once '../includes/sidebar_teacher.php'; ?>

<div class="dash-header">
    <h2 class="dash-title">Comprehensive Student Directory</h2>
</div>

<div class="table-card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th width="10%" class="text-center">Roll No (ID)</th>
                    <th>Student Name</th>
                    <th>Email Address</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($students): ?>
                    <?php foreach ($students as $student): ?>
                        <tr>
                            <td class="text-center text-muted fw-medium">#<?= $student['id'] ?></td>
                            <td class="fw-semibold text-dark">
                                <i class="bi bi-person-badge text-emerald-500 text-success me-2"></i><?= htmlspecialchars($student['name']) ?>
                            </td>
                            <td class="text-secondary"><?= htmlspecialchars($student['email']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" class="text-center text-muted py-4">No students enrolled yet.</td>
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