<?php
require_once '../config.php';
require_once '../includes/db.php';
require_once '../includes/auth.php';

// 🔐 Protection
requireLogin();
requireRole('student');

$student_id = $_SESSION['user_id'];

// Fetch quiz results
$results = fetchAll("
    SELECT qr.score, q.title
    FROM quiz_results qr
    JOIN quizzes q ON qr.quiz_id = q.id
    WHERE qr.student_id = ?
    ORDER BY qr.id DESC
", [$student_id]);

// Page title
$title = "Quiz Results";
require_once '../includes/header.php';
?>

<?php require_once '../includes/sidebar_student.php'; ?>

<div class="dashboard-wrapper">
    <div class="dash-header">
        <div>
            <h1 class="dash-title">My Quiz Results</h1>
            <div class="dash-subtitle">Review your performance across all completed quizzes.</div>
        </div>
    </div>

    <div class="content-card">
        <table class="table table-hover text-center align-middle border-light">
            <thead class="table-light text-secondary">
                <tr>
                    <th class="text-start ps-4">Quiz Title</th>
                    <th>Score</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($results): ?>
                    <?php foreach ($results as $r): ?>
                        <tr>
                            <td class="text-start ps-4 fw-semibold text-dark">
                                <i class="bi bi-card-checklist text-primary me-2"></i> <?= htmlspecialchars($r['title']) ?>
                            </td>
                            <td>
                                <span class="badge bg-primary badge-lg fs-6 px-3 py-2 rounded-pill shadow-sm">
                                    Score: <strong class="fs-5 ms-1"><?= $r['score'] ?></strong>
                                </span>
                            </td>
                            <td>
                                <?php if ($r['score'] > 0): ?>
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 py-2 px-3"><i class="bi bi-star-fill me-1 text-warning"></i> Completed</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 py-2 px-3">Completed</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" class="text-muted py-5 text-center">
                            <i class="bi bi-journal-x fs-1 d-block mb-3 text-secondary opacity-50"></i>
                            No quiz results available yet.
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