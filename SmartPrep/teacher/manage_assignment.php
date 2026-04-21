<?php
require_once '../config.php';
require_once '../includes/db.php';
require_once '../includes/auth.php';

// 🔐 Protection
requireLogin();
requireRole('teacher');

$teacher_id = $_SESSION['user_id'];

// Handle delete
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    executeQuery(
        "DELETE FROM assignments WHERE id = ? AND teacher_id = ?",
        [$id, $teacher_id]
    );
    header("Location: manage_assignment.php");
    exit();
}

// Fetch assignments
$assignments = fetchAll("
    SELECT a.id, a.title, a.deadline, s.name AS subject
    FROM assignments a
    JOIN subjects s ON a.subject_id = s.id
    WHERE a.teacher_id = ?
    ORDER BY a.id DESC
", [$teacher_id]);

// Page title
$title = "Manage Assignments";
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
.action-btn-delete { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }
.action-btn-delete:hover { background: #fee2e2; color: #b91c1c; }
.badge-table {
    background: #f1f5f9;
    color: #475569;
    padding: 6px 12px;
    border-radius: 6px;
    font-family: inherit;
    font-weight: 600;
    letter-spacing: 0.5px;
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
    <h2 class="dash-title">My Assignments</h2>
    <a href="upload_assignment.php" class="btn-action-primary">
        <i class="bi bi-file-earmark-plus-fill"></i> New Assignment
    </a>
</div>

<div class="table-card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th width="10%" class="text-center">ID</th>
                    <th>Assignment Title</th>
                    <th>Subject</th>
                    <th>Deadline</th>
                    <th width="15%" class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($assignments): ?>
                    <?php foreach ($assignments as $a): ?>
                        <tr>
                            <td class="text-center text-muted fw-medium">#<?= $a['id'] ?></td>
                            <td class="fw-semibold text-dark">
                                <i class="bi bi-journal-code text-primary me-2"></i><?= htmlspecialchars($a['title']) ?>
                            </td>
                            <td class="text-secondary"><?= htmlspecialchars($a['subject']) ?></td>
                            <td><span class="badge-table"><i class="bi bi-calendar3 me-1"></i><?= $a['deadline'] ?></span></td>
                            <td class="text-center">
                                <a href="?delete=<?= $a['id'] ?>" class="text-decoration-none action-btn action-btn-delete d-inline-flex align-items-center" onclick="return confirm('Delete this assignment?')">
                                    <i class="bi bi-trash3 me-1"></i> Delete
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted py-5">
                            <i class="bi bi-archive text-secondary fs-1 d-block mb-3"></i>
                            <span class="fw-medium">No assignments published. Click 'New Assignment' to begin.</span>
                        </td>
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