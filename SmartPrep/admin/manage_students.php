<?php
require_once '../config.php';
require_once '../includes/db.php';
require_once '../includes/auth.php';

// 🔐 Protection
requireLogin();
requireRole('admin');

// Handle delete
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    executeQuery("DELETE FROM users WHERE id = ? AND role = 'student'", [$id]);
    header("Location: manage_students.php");
    exit();
}

// Fetch students
$students = fetchAll("SELECT * FROM users WHERE role = 'student' ORDER BY id DESC");

// Page title
$title = "Manage Students";
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
.btn-action-primary {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    color: white;
    font-weight: 600;
    padding: 10px 20px;
    border-radius: 12px;
    border: none;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.25);
    transition: all 0.2s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}
.btn-action-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 18px rgba(59, 130, 246, 0.35);
    color: white;
}
.action-btn {
    padding: 6px 12px;
    border-radius: 8px;
    font-weight: 500;
    font-size: 0.85rem;
    transition: all 0.2s;
}
.action-btn-edit { background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe; }
.action-btn-edit:hover { background: #dbeafe; color: #1d4ed8; }
.action-btn-delete { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }
.action-btn-delete:hover { background: #fee2e2; color: #b91c1c; }
.badge-role {
    background: #f0fdf4;
    color: #166534;
    padding: 6px 12px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.85rem;
}
</style>

<?php require_once '../includes/sidebar_admin.php'; ?>

<div class="dash-header">
    <h2 class="dash-title">Manage Students</h2>
    <a href="add_student.php" class="btn-action-primary">
        <i class="bi bi-person-plus-fill"></i> Add Student
    </a>
</div>

<div class="card p-4 border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th width="10%" class="text-center">ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th width="20%" class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($students): ?>
                    <?php foreach ($students as $student): ?>
                        <tr>
                            <td class="text-center text-muted fw-medium">#<?= $student['id'] ?></td>
                            <td class="fw-semibold text-dark">
                                <i class="bi bi-person-badge text-secondary me-2"></i>
                                <?= htmlspecialchars($student['name']) ?>
                            </td>
                            <td class="text-secondary"><?= htmlspecialchars($student['email']) ?></td>
                            <td><span class="badge-role">Student</span></td>
                            <td class="text-center gap-2">
                                <a href="edit_student.php?id=<?= $student['id'] ?>" class="text-decoration-none action-btn action-btn-edit d-inline-flex align-items-center me-1">
                                    <i class="bi bi-pencil-square me-1"></i> Edit
                                </a>
                                <a href="?delete=<?= $student['id'] ?>" class="text-decoration-none action-btn action-btn-delete d-inline-flex align-items-center" onclick="return confirm('Delete this student?')">
                                    <i class="bi bi-trash3 me-1"></i> Delete
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">No students found. Click 'Add Student' to create an account.</td>
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