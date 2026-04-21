<?php
require_once '../config.php';
require_once '../includes/db.php';
require_once '../includes/auth.php';

// 🔐 Protection
requireLogin();
requireRole('student');

$student_id = $_SESSION['user_id'];
$error = "";
$success = "";

// Fetch assignments
$assignments = fetchAll("SELECT id, title FROM assignments ORDER BY deadline ASC");

// Handle form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $assignment_id = (int) $_POST['assignment_id'];
    $content       = trim($_POST['content']);

    if (empty($assignment_id) || empty($content)) {
        $error = "All fields are required!";
    } else {

        // Prevent duplicate submission
        $exists = fetch(
            "SELECT id FROM submissions WHERE assignment_id = ? AND student_id = ?",
            [$assignment_id, $student_id]
        );

        if ($exists) {
            $error = "You already submitted this assignment!";
        } else {

            $insert = executeQuery(
                "INSERT INTO submissions (assignment_id, student_id, content)
                 VALUES (?, ?, ?)",
                [$assignment_id, $student_id, $content]
            );

            if ($insert) {
                $success = "Assignment submitted successfully!";
            } else {
                $error = "Something went wrong!";
            }
        }
    }
}

// Page title
$title = "Submit Assignment";
require_once '../includes/header.php';
?>

<?php require_once '../includes/sidebar_student.php'; ?>

<div class="dashboard-wrapper">
    <div class="dash-header">
        <div>
            <h1 class="dash-title">Submit Assignment</h1>
            <div class="dash-subtitle">Upload and finalize your coursework.</div>
        </div>
    </div>

    <div class="content-card">
        <?php if ($error): ?>
            <div class="alert alert-danger border-0 border-start border-4 border-danger fw-medium d-flex align-items-center gap-2">
                <i class="bi bi-exclamation-octagon-fill"></i> <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success border-0 border-start border-4 border-success fw-medium d-flex align-items-center gap-2">
                <i class="bi bi-check-circle-fill"></i> <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-4">
                <label class="form-label fw-bold text-dark">Select Assignment</label>
                <select name="assignment_id" class="form-select border-light shadow-sm" required style="background-color: #f8fafc; padding: 0.75rem 1rem;">
                    <option value="">Choose an assignment...</option>
                    <?php foreach ($assignments as $a): ?>
                        <option value="<?= $a['id'] ?>">
                            <?= htmlspecialchars($a['title']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-4">
                <label class="form-label fw-bold text-dark">Your Submission</label>
                <textarea name="content" class="form-control border-light shadow-sm" rows="6" placeholder="Type your response here or provide an external link..." required style="background-color: #f8fafc; padding: 1rem;"></textarea>
            </div>

            <button class="btn-action btn-action-success w-100 justify-content-center text-decoration-none border-0 shadow-sm">
                <i class="bi bi-cloud-arrow-up-fill"></i> Submit Work
            </button>
        </form>
    </div>
</div>

<style>
.dashboard-wrapper { animation: fadeIn 0.4s ease-out; }
@keyframes fadeIn { from { opacity:0; transform:translateY(10px); } to { opacity:1; transform:translateY(0); } }
</style>

<?php
echo "</div></div>";
require_once '../includes/footer.php';
?>