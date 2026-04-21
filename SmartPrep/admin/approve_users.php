<?php
require_once '../config.php';
require_once '../includes/db.php';
require_once '../includes/auth.php';

// 🔐 Protection
requireLogin();
requireRole('admin');

// Handle approve
if (isset($_GET['approve'])) {
    $id = (int) $_GET['approve'];
    executeQuery("UPDATE users SET status = 'approved' WHERE id = ?", [$id]);
    header("Location: approve_users.php");
    exit();
}

// Handle reject (delete)
if (isset($_GET['reject'])) {
    $id = (int) $_GET['reject'];
    executeQuery("DELETE FROM users WHERE id = ? AND status = 'pending'", [$id]);
    header("Location: approve_users.php");
    exit();
}

// Fetch pending users
$users = fetchAll("
    SELECT id, name, email, role 
    FROM users 
    WHERE status = 'pending'
    ORDER BY id DESC
");

// Page title
$title = "Approve Users";
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
.action-btn {
    padding: 6px 12px;
    border-radius: 8px;
    font-weight: 500;
    font-size: 0.85rem;
    transition: all 0.2s;
}
.action-btn-approve { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
.action-btn-approve:hover { background: #bbf7d0; color: #15803d; }
.action-btn-reject { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }
.action-btn-reject:hover { background: #fee2e2; color: #b91c1c; }
.badge-role-teacher { background: #eef2ff; color: #4f46e5; padding: 6px 12px; border-radius: 8px; font-weight: 600; font-size: 0.85rem; }
.badge-role-student { background: #f0fdf4; color: #166534; padding: 6px 12px; border-radius: 8px; font-weight: 600; font-size: 0.85rem; }
</style>

<?php require_once '../includes/sidebar_admin.php'; ?>

<div class="dash-header">
    <h2 class="dash-title">Pending User Approvals</h2>
</div>

<div class="card p-4 border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th width="10%" class="text-center">ID</th>
                    <th>Name</th>
                    <th>Email Address</th>
                    <th>Role Request</th>
                    <th width="20%" class="text-center">Review Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($users): ?>
                    <?php foreach ($users as $u): ?>
                        <tr>
                            <td class="text-center text-muted fw-medium">#<?= $u['id'] ?></td>
                            <td class="fw-semibold text-dark"><?= htmlspecialchars($u['name']) ?></td>
                            <td class="text-secondary"><?= htmlspecialchars($u['email']) ?></td>
                            <td>
                                <?php if ($u['role'] === 'teacher'): ?>
                                    <span class="badge-role-teacher"><i class="bi bi-briefcase-fill me-1"></i> Teacher</span>
                                <?php else: ?>
                                    <span class="badge-role-student"><i class="bi bi-backpack-fill me-1"></i> Student</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center gap-2">
                                <a href="?approve=<?= $u['id'] ?>" class="text-decoration-none action-btn action-btn-approve d-inline-flex align-items-center me-1">
                                    <i class="bi bi-check-circle-fill me-1"></i> Approve
                                </a>
                                <a href="?reject=<?= $u['id'] ?>" class="text-decoration-none action-btn action-btn-reject d-inline-flex align-items-center" onclick="return confirm('Reject this user request?')">
                                    <i class="bi bi-x-circle-fill me-1"></i> Reject
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted py-5">
                            <i class="bi bi-shield-check text-success fs-1 d-block mb-2"></i>
                            <span class="fw-medium">All caught up! No pending user registrations.</span>
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