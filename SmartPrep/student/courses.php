<?php
require_once '../config.php';
require_once '../includes/db.php';
require_once '../includes/auth.php';

// 🔐 Protection
requireLogin();
requireRole('student');

// Fetch courses
$courses = fetchAll("SELECT * FROM courses ORDER BY name ASC");

// Page title
$title = "Courses";
require_once '../includes/header.php';
?>

<?php require_once '../includes/sidebar_student.php'; ?>

<div class="dashboard-wrapper">
    <div class="dash-header">
        <div>
            <h1 class="dash-title">Courses</h1>
            <div class="dash-subtitle">Explore the subjects you are enrolled in.</div>
        </div>
    </div>

    <div class="content-card">
        <table class="table table-hover text-center align-middle border-light">
            <thead class="table-light text-secondary">
                <tr>
                    <th>ID</th>
                    <th>Course Name</th>
                    <th>Course Code</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($courses): ?>
                    <?php foreach ($courses as $course): ?>
                        <tr>
                            <td class="fw-bold text-muted"><?= $course['id'] ?></td>
                            <td class="fw-semibold text-primary"><?= htmlspecialchars($course['name']) ?></td>
                            <td><span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25"><?= htmlspecialchars($course['code']) ?></span></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" class="text-muted py-4">No courses available</td>
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