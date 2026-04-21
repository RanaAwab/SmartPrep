<?php
require_once '../config.php';
require_once '../includes/db.php';
require_once '../includes/auth.php';

// 🔐 Protection
requireLogin();
requireRole('student');

$student_id = $_SESSION['user_id'];

// Fetch attendance
$attendance = fetchAll("
    SELECT a.date, a.status, s.name AS subject
    FROM attendance a
    JOIN subjects s ON a.subject_id = s.id
    WHERE a.student_id = ?
    ORDER BY a.date DESC
", [$student_id]);

// Page title
$title = "My Attendance";
require_once '../includes/header.php';
?>

<?php require_once '../includes/sidebar_student.php'; ?>

<div class="dashboard-wrapper">
    <div class="dash-header">
        <div>
            <h1 class="dash-title">My Attendance</h1>
            <div class="dash-subtitle">Track your class attendance records.</div>
        </div>
    </div>

    <div class="content-card">
        <table class="table table-hover text-center align-middle border-light">
            <thead class="table-light text-secondary">
                <tr>
                    <th>Date</th>
                    <th>Subject</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($attendance): ?>
                    <?php foreach ($attendance as $a): ?>
                        <tr>
                            <td class="text-muted fw-medium"><i class="bi bi-calendar-event me-1 text-primary"></i> <?= $a['date'] ?></td>
                            <td class="fw-bold text-dark"><?= htmlspecialchars($a['subject']) ?></td>
                            <td>
                                <?php if ($a['status'] === 'present'): ?>
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-2 rounded-pill"><i class="bi bi-check-circle-fill me-1"></i> Present</span>
                                <?php else: ?>
                                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-3 py-2 rounded-pill"><i class="bi bi-x-circle-fill me-1"></i> Absent</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" class="text-muted py-5 text-center">
                            <i class="bi bi-clipboard-x fs-1 d-block mb-3 text-secondary opacity-50"></i>
                            No attendance records available.
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