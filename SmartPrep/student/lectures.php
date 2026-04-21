<?php
require_once '../config.php';
require_once '../includes/db.php';
require_once '../includes/auth.php';

// 🔐 Protection
requireLogin();
requireRole('student');

// Fetch lectures
$lectures = fetchAll("
    SELECT l.title, l.file, s.name AS subject
    FROM lectures l
    JOIN subjects s ON l.subject_id = s.id
    ORDER BY l.id DESC
");

// Page title
$title = "Lectures";
require_once '../includes/header.php';
?>

<?php require_once '../includes/sidebar_student.php'; ?>

<div class="dashboard-wrapper">
    <div class="dash-header">
        <div>
            <h1 class="dash-title">Lectures</h1>
            <div class="dash-subtitle">Access your course materials and video lectures.</div>
        </div>
    </div>

    <div class="row g-4">
        <?php if ($lectures): ?>
            <?php foreach ($lectures as $lec): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="content-card h-100 d-flex flex-column transition-hover shadow-sm border-0">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-primary bg-opacity-10 p-3 rounded-circle me-3 text-primary">
                                <i class="bi bi-file-earmark-play-fill fs-4"></i>
                            </div>
                            <h5 class="fw-bold mb-0 text-dark"><?= htmlspecialchars($lec['title']) ?></h5>
                        </div>
                        <p class="text-muted mb-4 small fw-medium">
                            <i class="bi bi-book-half me-1"></i> <?= htmlspecialchars($lec['subject']) ?>
                        </p>
                        <div class="mt-auto">
                            <a href="../uploads/<?= $lec['file'] ?>" class="btn-action btn-action-primary w-100 text-center text-decoration-none justify-content-center" download>
                                <i class="bi bi-cloud-arrow-down-fill"></i> Download
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-light border text-center text-muted p-4 rounded-4">
                    <i class="bi bi-info-circle fs-3 d-block mb-2 text-secondary"></i>
                    No lectures available
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.transition-hover:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.06) !important;
}
</style>

<?php
echo "</div></div>";
require_once '../includes/footer.php';
?>