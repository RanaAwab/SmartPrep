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

// Fetch quizzes
$quizzes = fetchAll("SELECT id, title FROM quizzes");

// Handle submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $quiz_id = (int) $_POST['quiz_id'];
    $answers = $_POST['answers'] ?? [];

    if (empty($quiz_id) || empty($answers)) {
        $error = "Please answer all questions!";
    } else {

        $score = 0;

        foreach ($answers as $mcq_id => $ans) {

            $correct = fetch("SELECT correct FROM mcqs WHERE id = ?", [$mcq_id]);

            if ($correct && $correct['correct'] == $ans) {
                $score++;
            }
        }

        // Save result
        executeQuery(
            "INSERT INTO quiz_results (quiz_id, student_id, score)
             VALUES (?, ?, ?)",
            [$quiz_id, $student_id, $score]
        );

        $success = "Quiz submitted! Your score: " . $score;
    }
}

// Fetch MCQs if quiz selected
$mcqs = [];
if (isset($_GET['quiz_id'])) {
    $quiz_id = (int) $_GET['quiz_id'];

    $mcqs = fetchAll("SELECT * FROM mcqs WHERE quiz_id = ?", [$quiz_id]);
}

// Page
$title = "Attempt Quiz";
require_once '../includes/header.php';
require_once '../includes/sidebar_student.php';
?>

<div class="dashboard-wrapper">
    <div class="dash-header">
        <div>
            <h1 class="dash-title">Attempt Quiz</h1>
            <div class="dash-subtitle">Test your knowledge and take assigned quizzes.</div>
        </div>
    </div>

    <div class="content-card mb-4 border-left-primary" style="border-left: 5px solid #3b82f6;">
        <form method="GET" class="d-flex gap-3 align-items-center flex-wrap">
            <div class="flex-grow-1">
                <select name="quiz_id" class="form-select border-light shadow-sm" required style="background-color: #f8fafc; padding: 0.75rem 1rem;">
                    <option value="">Select Quiz to Attempt</option>
                    <?php foreach ($quizzes as $q): ?>
                        <option value="<?= $q['id'] ?>" <?= isset($_GET['quiz_id']) && $_GET['quiz_id'] == $q['id'] ? 'selected' : '' ?>><?= htmlspecialchars($q['title']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button class="btn-action btn-action-primary m-0 shadow-sm border-0 d-inline-flex" style="padding: 0.75rem 1.5rem;">
                <i class="bi bi-play-circle-fill"></i> Load Quiz
            </button>
        </form>
    </div>

    <?php if ($mcqs): ?>
        <div class="content-card mt-4">
            <?php if ($error): ?>
                <div class="alert alert-danger border-0 border-start border-4 border-danger fw-medium mb-4 d-flex align-items-center gap-2">
                    <i class="bi bi-exclamation-octagon-fill"></i> <?= $error ?>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success border-0 border-start border-4 border-success fw-medium mb-4 d-flex align-items-center gap-2">
                    <i class="bi bi-trophy-fill"></i> <?= $success ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <input type="hidden" name="quiz_id" value="<?= $quiz_id ?>">

                <?php foreach ($mcqs as $index => $m): ?>
                    <div class="quiz-question-card bg-light bg-opacity-50 p-4 rounded-4 mb-4 border border-light">
                        <div class="fw-bold mb-3 d-flex align-items-start gap-2 fs-5 text-dark">
                            <span class="badge bg-primary rounded-pill"><?= $index + 1 ?></span>
                            <?= htmlspecialchars($m['question']) ?>
                        </div>

                        <div class="d-flex flex-column gap-2 ms-4">
                            <label class="form-check-label option-label border rounded-3 p-3 bg-white shadow-sm cursor-pointer transition-all">
                                <input class="form-check-input me-2" type="radio" name="answers[<?= $m['id'] ?>]" value="1"> <?= htmlspecialchars($m['opt1']) ?>
                            </label>
                            <label class="form-check-label option-label border rounded-3 p-3 bg-white shadow-sm cursor-pointer transition-all">
                                <input class="form-check-input me-2" type="radio" name="answers[<?= $m['id'] ?>]" value="2"> <?= htmlspecialchars($m['opt2']) ?>
                            </label>
                            <label class="form-check-label option-label border rounded-3 p-3 bg-white shadow-sm cursor-pointer transition-all">
                                <input class="form-check-input me-2" type="radio" name="answers[<?= $m['id'] ?>]" value="3"> <?= htmlspecialchars($m['opt3']) ?>
                            </label>
                            <label class="form-check-label option-label border rounded-3 p-3 bg-white shadow-sm cursor-pointer transition-all">
                                <input class="form-check-input me-2" type="radio" name="answers[<?= $m['id'] ?>]" value="4"> <?= htmlspecialchars($m['opt4']) ?>
                            </label>
                        </div>
                    </div>
                <?php endforeach; ?>

                <div class="text-end mt-5">
                    <button class="btn-action btn-action-success border-0 shadow fs-5 px-5">
                        <i class="bi bi-check-circle-fill"></i> Submit Quiz
                    </button>
                </div>
            </form>
        </div>
    <?php endif; ?>
</div>

<style>
.cursor-pointer { cursor: pointer; }
.transition-all { transition: all 0.2s ease; }
.option-label:hover { background-color: #f1f5f9 !important; border-color: #cbd5e1 !important; transform: translateX(5px); }
</style>

<?php
echo "</div></div>";
require_once '../includes/footer.php';
?>