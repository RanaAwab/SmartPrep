<?php
require_once '../config.php';
require_once '../includes/db.php';
require_once '../includes/auth.php';

// 🔐 Protection
requireLogin();
requireRole('teacher'); // Fixed from 'student' to 'teacher'

$teacher_id = $_SESSION['user_id'];

// 🎓 Academic Results (Fetched for this specific teacher)
$results = fetchAll("
    SELECT u.name AS student, s.name AS subject, r.marks
    FROM results r
    JOIN users u ON r.student_id = u.id
    JOIN subjects s ON r.subject_id = s.id
    WHERE r.teacher_id = ?
    ORDER BY s.name ASC, u.name ASC
", [$teacher_id]);

// 🧠 Quiz Results (Fetched for quizzes created by this teacher)
$quizResults = fetchAll("
    SELECT u.name AS student, q.title AS quiz, qr.score
    FROM quiz_results qr
    JOIN users u ON qr.student_id = u.id
    JOIN quizzes q ON qr.quiz_id = q.id
    WHERE q.teacher_id = ?
    ORDER BY q.title ASC, qr.score DESC
", [$teacher_id]);

// Page title
$title = "Student Results";
require_once '../includes/header.php';
?>

<style>
.section-title {
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}
.badge-score {
    font-size: 0.95rem;
    padding: 6px 12px;
    border-radius: 8px;
    font-weight: 600;
}
.badge-excellent { background-color: #dcfce7; color: #166534; }
.badge-good { background-color: #eff6ff; color: #1e40af; }
.badge-average { background-color: #fffbeb; color: #b45309; }
.badge-poor { background-color: #fef2f2; color: #991b1b; }
</style>

<?php require_once '../includes/sidebar_teacher.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold m-0 text-slate-800">Student Results Overview</h2>
</div>

<div class="row g-4">
    <!-- 🎓 Academic Results -->
    <div class="col-lg-6">
        <div class="card shadow p-4 h-100">
            <h5 class="section-title"><i class="bi bi-journal-check text-primary"></i> Academic Results</h5>

            <?php if ($results): ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Subject</th>
                                <th class="text-center">Marks</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($results as $r): ?>
                                <?php
                                    $score = $r['marks'];
                                    $badge = 'badge-average';
                                    if ($score >= 80) $badge = 'badge-excellent';
                                    elseif ($score >= 60) $badge = 'badge-good';
                                    elseif ($score < 40) $badge = 'badge-poor';
                                ?>
                                <tr>
                                    <td class="fw-medium text-dark"><?= htmlspecialchars($r['student']) ?></td>
                                    <td class="text-secondary"><?= htmlspecialchars($r['subject']) ?></td>
                                    <td class="text-center">
                                        <span class="badge-score <?= $badge ?>"><?= $score ?>/100</span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-light border text-center text-muted mt-2">
                    <i class="bi bi-info-circle me-2"></i> No academic results published yet.
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- 🧠 Quiz Results -->
    <div class="col-lg-6">
        <div class="card shadow p-4 h-100">
            <h5 class="section-title"><i class="bi bi-lightning-charge-fill text-warning"></i> Quiz Results</h5>

            <?php if ($quizResults): ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Quiz Title</th>
                                <th class="text-center">Score</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($quizResults as $q): ?>
                                <?php
                                    $score = $q['score'];
                                    // Normally quizzes are out of a dynamic total, assuming /10 or similar visually, using primary color
                                ?>
                                <tr>
                                    <td class="fw-medium text-dark"><?= htmlspecialchars($q['student']) ?></td>
                                    <td class="text-secondary"><?= htmlspecialchars($q['quiz']) ?></td>
                                    <td class="text-center">
                                        <span class="badge-score bg-primary text-white bg-opacity-75"><?= $score ?> pts</span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-light border text-center text-muted mt-2">
                    <i class="bi bi-info-circle me-2"></i> No quiz submissions available yet.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
echo "</div></div>";
require_once '../includes/footer.php';
?>