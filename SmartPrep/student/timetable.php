<?php
require_once '../config.php';
require_once '../includes/db.php';
require_once '../includes/auth.php';

// 🔐 Protection
requireLogin();
requireRole('student');

// Fetch timetable
$timetable = fetchAll("
    SELECT t.day, t.time, s.name AS subject, u.name AS teacher
    FROM timetable t
    JOIN subjects s ON t.subject_id = s.id
    JOIN users u ON t.teacher_id = u.id
    ORDER BY FIELD(t.day, 'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'), t.time
");

// Page title
$title = "Timetable";
require_once '../includes/header.php';
?>

<?php require_once '../includes/sidebar_student.php'; ?>

<div class="dashboard-wrapper">
    <div class="dash-header">
        <div>
            <h1 class="dash-title">My Timetable</h1>
            <div class="dash-subtitle">Your weekly schedule for all classes.</div>
        </div>
    </div>

    <div class="content-card">
        <table class="table table-hover text-center align-middle border-light">
            <thead class="table-light text-secondary">
                <tr>
                    <th>Day</th>
                    <th>Time</th>
                    <th>Subject</th>
                    <th>Teacher</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($timetable): ?>
                    <?php foreach ($timetable as $t): ?>
                        <tr>
                            <td class="fw-bold text-primary bg-primary bg-opacity-10 border-bottom border-white"><?= $t['day'] ?></td>
                            <td class="text-muted fw-medium"><i class="bi bi-clock"></i> <?= $t['time'] ?></td>
                            <td class="fw-bold text-dark"><?= htmlspecialchars($t['subject']) ?></td>
                            <td><span class="badge bg-light text-dark border border-secondary border-opacity-25 px-3 py-2"><i class="bi bi-person-video3 text-warning me-1"></i> <?= htmlspecialchars($t['teacher']) ?></span></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-muted py-5 text-center">
                            <i class="bi bi-calendar-x fs-1 d-block mb-3 text-secondary opacity-50"></i>
                            No timetable assigned to you yet.
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