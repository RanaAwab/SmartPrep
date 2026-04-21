<?php
require_once '../config.php';
require_once '../includes/db.php';
require_once '../includes/auth.php';

// 🔐 Protection
requireLogin();
requireRole('student');

$student_id = $_SESSION['user_id'];

// Fetch total assignments
$totalAssignments = fetch("SELECT COUNT(*) as count FROM assignments")['count'];

// Fetch total subjects
$totalSubjects = fetch("SELECT COUNT(*) as count FROM subjects")['count'];

// Fetch results count
$totalResults = fetch(
    "SELECT COUNT(*) as count FROM results WHERE student_id = ?",
    [$student_id]
)['count'];

// Page title
$title = "Student Dashboard";
require_once '../includes/header.php';
?>

<?php require_once '../includes/sidebar_student.php'; ?>

<style>
@import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap');

.dashboard-wrapper {
    font-family: 'Outfit', sans-serif;
    color: #1e293b;
    animation: fadeIn 0.4s ease-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.dash-header {
    margin-bottom: 2rem;
    padding-bottom: 1.2rem;
    border-bottom: 1px solid #f1f5f9;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 15px;
}

.dash-title {
    font-weight: 800;
    font-size: 2.2rem;
    color: #0f172a;
    margin: 0;
}

.dash-subtitle {
    color: #64748b;
    font-size: 1.05rem;
    margin-top: 5px;
}

.metric-card {
    border-radius: 16px;
    padding: 24px;
    background: #ffffff;
    box-shadow: 0 10px 30px rgba(0,0,0,0.02);
    border: 1px solid rgba(0,0,0,0.03);
    position: relative;
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: space-between;
    height: 100%;
}

.metric-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.06);
}

.metric-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 6px;
    height: 100%;
}

.metric-subjects::before { background: linear-gradient(to bottom, #d946ef, #f472b6); }
.metric-assignments::before { background: linear-gradient(to bottom, #10b981, #34d399); }
.metric-results::before { background: linear-gradient(to bottom, #f59e0b, #fbbf24); }

.metric-icon {
    font-size: 2.5rem;
    background: #f8fafc;
    width: 70px;
    height: 70px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 16px;
}

.metric-info {
    text-align: right;
}

.metric-value {
    font-size: 2.75rem;
    font-weight: 800;
    color: #0f172a;
    line-height: 1;
    margin-bottom: 5px;
}

.metric-label {
    font-weight: 600;
    color: #64748b;
    font-size: 0.95rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.content-card {
    background: #ffffff;
    border-radius: 16px;
    padding: 28px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.02);
    border: 1px solid rgba(0,0,0,0.03);
    height: 100%;
}

.content-title {
    font-weight: 700;
    font-size: 1.35rem;
    margin-bottom: 20px;
    color: #1e293b;
    display: flex;
    align-items: center;
    gap: 10px;
}

.btn-action {
    border-radius: 12px;
    font-weight: 600;
    padding: 14px 22px;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    transition: all 0.2s ease;
    border: 1px solid transparent;
}

.btn-action-primary { background: #eff6ff; color: #2563eb; border-color: #bfdbfe; }
.btn-action-primary:hover { background: #dbeafe; color: #1d4ed8; transform: translateY(-2px); }

.btn-action-success { background: #f0fdf4; color: #16a34a; border-color: #bbf7d0; }
.btn-action-success:hover { background: #dcfce7; color: #15803d; transform: translateY(-2px); }

.btn-action-warning { background: #fffbeb; color: #d97706; border-color: #fde68a; }
.btn-action-warning:hover { background: #fef3c7; color: #b45309; transform: translateY(-2px); }
</style>

<div class="dashboard-wrapper">
    <div class="dash-header">
        <div>
            <h1 class="dash-title">Student Dashboard</h1>
            <div class="dash-subtitle">Welcome back, <?= htmlspecialchars($_SESSION['name'] ?? 'Student') ?>! Here's your academic summary.</div>
        </div>
        <div>
            <span class="badge bg-primary bg-gradient fs-6 px-4 py-2 rounded-pill shadow-sm">
                <i class="bi bi-calendar3 me-1"></i> <?= date('F j, Y') ?>
            </span>
        </div>
    </div>

    <!-- Metrics Row -->
    <div class="row g-4 mb-4">
        <!-- Subjects -->
        <div class="col-md-6 col-lg-4">
            <div class="metric-card metric-subjects">
                <div class="metric-icon shadow-sm">📖</div>
                <div class="metric-info">
                    <div class="metric-value"><?= $totalSubjects ?></div>
                    <div class="metric-label">Subjects</div>
                </div>
            </div>
        </div>

        <!-- Assignments -->
        <div class="col-md-6 col-lg-4">
            <div class="metric-card metric-assignments">
                <div class="metric-icon shadow-sm">📂</div>
                <div class="metric-info">
                    <div class="metric-value"><?= $totalAssignments ?></div>
                    <div class="metric-label">Assignments</div>
                </div>
            </div>
        </div>

        <!-- Results -->
        <div class="col-md-6 col-lg-4">
            <div class="metric-card metric-results">
                <div class="metric-icon shadow-sm">📊</div>
                <div class="metric-info">
                    <div class="metric-value"><?= $totalResults ?></div>
                    <div class="metric-label">My Results</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="content-card">
        <h5 class="content-title"><i class="bi bi-lightning-charge-fill text-warning"></i> Quick Actions</h5>
        
        <div class="d-flex gap-3 flex-wrap mt-4">
            <a href="assignments.php" class="btn-action btn-action-primary text-decoration-none shadow-sm">
                <i class="bi bi-folder2-open"></i> View Assignments
            </a>
            <a href="submit_assignment.php" class="btn-action btn-action-success text-decoration-none shadow-sm">
                <i class="bi bi-cloud-arrow-up"></i> Submit Assignment
            </a>
            <a href="view_quiz_result.php" class="btn-action btn-action-warning text-decoration-none shadow-sm">
                <i class="bi bi-bar-chart-fill"></i> View Results
            </a>
        </div>
    </div>
</div>

<?php
echo "</div></div>";
require_once '../includes/footer.php';
?>