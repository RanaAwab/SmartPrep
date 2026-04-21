<?php
require_once '../config.php';
require_once '../includes/db.php';
require_once '../includes/auth.php';

// 🔐 Protection
requireLogin();
requireRole('student');

// Fetch assignments
$assignments = fetchAll("
    SELECT a.id, a.title, a.description, a.deadline, s.name AS subject
    FROM assignments a
    JOIN subjects s ON a.subject_id = s.id
    ORDER BY a.deadline ASC
");

// Page title
$title = "Assignments";
require_once '../includes/header.php';
?>

<?php require_once '../includes/sidebar_student.php'; ?>

<div class="dashboard-wrapper">
    <div class="dash-header">
        <div>
            <h1 class="dash-title">Assignments</h1>
            <div class="dash-subtitle">Track your upcoming tasks and deadlines.</div>
        </div>
    </div>

    <div class="row g-4">
        <?php if ($assignments): ?>
            <?php foreach ($assignments as $a): ?>
                <div class="col-md-6 mb-3">
                    <div class="content-card h-100 transition-hover border-0 shadow-sm position-relative">
                        <div class="position-absolute top-0 start-0 w-100 h-100 bg-warning opacity-10 rounded-4" style="z-index:-1; filter:blur(20px);"></div>
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h5 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                                <i class="bi bi-journal-text text-warning"></i> 
                                <?= htmlspecialchars($a['title']) ?>
                            </h5>
                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-3 py-2 rounded-pill">
                                <i class="bi bi-clock me-1"></i> <?= date('M d, Y', strtotime($a['deadline'])) ?>
                            </span>
                        </div>
                        
                        <div class="mb-3 text-secondary small fw-medium">
                            <span class="badge bg-light text-dark border"><i class="bi bi-tag-fill me-1"></i> <?= htmlspecialchars($a['subject']) ?></span>
                        </div>

                        <?php if (!empty($a['description'])): ?>
                            <p class="text-muted small border-start border-3 border-warning ps-3 mb-0">
                                <?= nl2br(htmlspecialchars($a['description'])) ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-light border text-center text-muted p-4 rounded-4">
                    <i class="bi bi-check-circle fs-3 d-block mb-2 text-success"></i>
                    No pending assignments. You're all caught up!
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.transition-hover { transition: transform 0.3s ease, box-shadow 0.3s ease; }
.transition-hover:hover { transform: translateY(-3px); box-shadow: 0 15px 35px rgba(0,0,0,0.06) !important; }
</style>

<?php
echo "</div></div>";
require_once '../includes/footer.php';
?>