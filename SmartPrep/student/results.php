<?php
require_once '../config.php';
require_once '../includes/db.php';
require_once '../includes/auth.php';

// 🔐 Protection
requireLogin();
requireRole('student');

$student_id = $_SESSION['user_id'];

// Fetch results
$results = fetchAll("
    SELECT s.name AS subject, r.marks
    FROM results r
    JOIN subjects s ON r.subject_id = s.id
    WHERE r.student_id = ?
    ORDER BY s.name ASC
", [$student_id]);

// Page title
$title = "My Results";
require_once '../includes/header.php';
?>

<?php require_once '../includes/sidebar_student.php'; ?>

<h2 class="mb-4">My Results</h2>

<div class="card shadow p-3">

    <table class="table table-bordered table-hover text-center align-middle">

        <thead class="table-dark">
            <tr>
                <th>Subject</th>
                <th>Marks</th>
            </tr>
        </thead>

        <tbody>
            <?php if ($results): ?>
                <?php foreach ($results as $r): ?>
                    <tr>
                        <td><?= htmlspecialchars($r['subject']) ?></td>
                        <td><?= $r['marks'] ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="2">No results available</td>
                </tr>
            <?php endif; ?>
        </tbody>

    </table>

</div>

<?php
echo "</div></div>";
require_once '../includes/footer.php';
?>