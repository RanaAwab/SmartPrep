<?php
require_once '../config.php';
require_once '../includes/db.php';
require_once '../includes/auth.php';

// 🔐 Protection
requireLogin();
requireRole('teacher');

$error = "";
$success = "";

// Fetch quizzes created by teacher
$teacher_id = $_SESSION['user_id'];

$quizzes = fetchAll("
    SELECT id, title 
    FROM quizzes 
    WHERE teacher_id = ?
", [$teacher_id]);

// Handle form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $quiz_id  = (int) $_POST['quiz_id'];
    $question = trim($_POST['question']);
    $opt1     = trim($_POST['opt1']);
    $opt2     = trim($_POST['opt2']);
    $opt3     = trim($_POST['opt3']);
    $opt4     = trim($_POST['opt4']);
    $correct  = $_POST['correct'];

    if (empty($quiz_id) || empty($question) || empty($opt1) || empty($opt2) || empty($opt3) || empty($opt4)) {
        $error = "All fields are required!";
    } else {
        executeQuery(
            "INSERT INTO mcqs (quiz_id, question, opt1, opt2, opt3, opt4, correct)
             VALUES (?, ?, ?, ?, ?, ?, ?)",
            [$quiz_id, $question, $opt1, $opt2, $opt3, $opt4, $correct]
        );
        $success = "MCQ Question added successfully!";
    }
}

// Page title
$title = "Add MCQ";
require_once '../includes/header.php';
require_once '../includes/sidebar_teacher.php';
?>

<style>
.form-card {
    max-width: 650px;
    margin: 0 auto;
    border-radius: 16px;
    border: 1px solid rgba(0,0,0,0.03);
    box-shadow: 0 10px 30px rgba(0,0,0,0.02);
    padding: 35px 40px;
    background: #ffffff;
}
.form-label-custom {
    font-weight: 600;
    color: #475569;
    margin-bottom: 8px;
    font-size: 0.95rem;
}
.form-control-custom {
    display: block;
    width: 100%;
    padding: 12px 16px;
    font-size: 1rem;
    color: #334155;
    background-color: #f8fafc;
    border: 1.5px solid #e2e8f0;
    border-radius: 12px;
    transition: all 0.3s ease;
}
.form-control-custom:focus {
    background-color: #fff;
    border-color: #3b82f6;
    outline: 0;
    box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15);
}
select.form-control-custom {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23334155' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right 1rem center;
    background-size: 16px 12px;
    padding-right: 2.5rem;
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
}
textarea.form-control-custom {
    min-height: 100px;
    resize: vertical;
}
.btn-save {
    background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
    color: white;
    font-weight: 600;
    padding: 12px 28px;
    border-radius: 12px;
    border: none;
    box-shadow: 0 4px 12px rgba(139, 92, 246, 0.25);
    transition: all 0.2s ease;
}
.btn-save:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 18px rgba(139, 92, 246, 0.35);
    color: white;
}
.dash-title {
    font-weight: 800;
    color: #0f172a;
    font-size: 1.8rem;
    margin-bottom: 30px;
    text-align: center;
}
.alert-custom {
    border-radius: 12px;
    padding: 14px 16px;
    font-size: 0.95rem;
    border: none;
    margin-bottom: 25px;
}
.alert-custom-error { background-color: #fef2f2; color: #991b1b; border-left: 4px solid #ef4444; }
.alert-custom-success { background-color: #f0fdf4; color: #166534; border-left: 4px solid #22c55e; }
.page-top-link {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: #64748b;
    text-decoration: none;
    font-weight: 600;
    margin-bottom: 25px;
    transition: all 0.2s;
}
.page-top-link:hover {
    color: #0f172a;
    transform: translateX(-3px);
}
</style>

<a href="create_quiz.php" class="page-top-link mt-2">
    <i class="bi bi-arrow-left"></i> Step 1: Create Quiz
</a>

<div class="form-card mt-3">
    <h2 class="dash-title">Add Multiple Choice Question</h2>

    <?php if ($error): ?>
        <div class="alert alert-danger alert-custom alert-custom-error d-flex align-items-center">
            <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
            <div><?= htmlspecialchars($error) ?></div>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="alert alert-success alert-custom alert-custom-success d-flex align-items-center">
            <i class="bi bi-check-circle-fill me-2 fs-5"></i>
            <div><?= htmlspecialchars($success) ?></div>
        </div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-4">
            <label class="form-label-custom">Link to Quiz</label>
            <select name="quiz_id" class="form-control-custom" required>
                <option value="" disabled selected>-- Select a Quiz --</option>
                <?php foreach ($quizzes as $q): ?>
                    <option value="<?= $q['id'] ?>"><?= htmlspecialchars($q['title']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-4">
            <label class="form-label-custom">Question Text</label>
            <textarea name="question" class="form-control-custom" placeholder="Type the question here..." required></textarea>
        </div>

        <div class="row mb-4">
            <div class="col-md-6 mb-3 mb-md-0">
                <label class="form-label-custom">Option 1</label>
                <input type="text" name="opt1" class="form-control-custom" placeholder="e.g. CPU" required>
            </div>
            <div class="col-md-6">
                <label class="form-label-custom">Option 2</label>
                <input type="text" name="opt2" class="form-control-custom" placeholder="e.g. GPU" required>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-6 mb-3 mb-md-0">
                <label class="form-label-custom">Option 3</label>
                <input type="text" name="opt3" class="form-control-custom" placeholder="e.g. RAM" required>
            </div>
            <div class="col-md-6">
                <label class="form-label-custom">Option 4</label>
                <input type="text" name="opt4" class="form-control-custom" placeholder="e.g. Hard Drive" required>
            </div>
        </div>

        <div class="mb-4">
            <label class="form-label-custom">Select Correct Answer</label>
            <select name="correct" class="form-control-custom" required>
                <option value="" disabled selected>-- Identify correct option --</option>
                <option value="1">Option 1</option>
                <option value="2">Option 2</option>
                <option value="3">Option 3</option>
                <option value="4">Option 4</option>
            </select>
        </div>

        <div class="text-center mt-2">
            <button class="btn-save w-100"><i class="bi bi-node-plus-fill me-1"></i> Add Question to Quiz</button>
        </div>
    </form>
</div>

<?php
echo "</div></div>";
require_once '../includes/footer.php';
?>