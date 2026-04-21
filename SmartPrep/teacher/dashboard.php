<?php
require_once '../config.php';
require_once '../includes/db.php';
require_once '../includes/auth.php';

// 🔐 Protection
requireLogin();
requireRole('teacher');

$teacher_id = $_SESSION['user_id'];

// Fetch assigned courses
$courses = fetchAll("
    SELECT c.name 
    FROM teacher_course tc
    JOIN courses c ON tc.course_id = c.id
    WHERE tc.teacher_id = ?
", [$teacher_id]);

$totalCoursesAssigned = count($courses);

// Count students (basic count)
$totalStudents = fetch("SELECT COUNT(*) as count FROM users WHERE role = 'student'")['count'];

// Page title
$title = "Teacher Dashboard";
require_once '../includes/header.php';
?>

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

.metric-courses::before { background: linear-gradient(to bottom, #f59e0b, #fbbf24); }
.metric-students::before { background: linear-gradient(to bottom, #3b82f6, #60a5fa); }

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

.course-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.course-item {
    padding: 14px 18px;
    background: #f8fafc;
    border-radius: 12px;
    margin-bottom: 10px;
    font-weight: 500;
    color: #334155;
    border-left: 4px solid #3b82f6;
    display: flex;
    align-items: center;
    gap: 12px;
    transition: transform 0.2s;
}

.course-item:hover {
    transform: translateX(4px);
    background: #f1f5f9;
}

.course-item i {
    color: #94a3b8;
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

<?php require_once '../includes/sidebar_teacher.php'; ?>

<div class="dashboard-wrapper">
    <div class="dash-header">
        <div>
            <h1 class="dash-title">Teacher Dashboard</h1>
            <div class="dash-subtitle">Welcome back, <?= htmlspecialchars($_SESSION['name']) ?>! Prepare for your upcoming classes.</div>
        </div>
        <div>
            <span class="badge bg-success bg-gradient fs-6 px-4 py-2 rounded-pill shadow-sm">
                <i class="bi bi-calendar3 me-1"></i> <?= date('F j, Y') ?>
            </span>
        </div>
    </div>

    <!-- Metrics Row -->
    <div class="row g-4 mb-4">
        <!-- Assigned Courses Metric -->
        <div class="col-md-6 col-lg-4">
            <div class="metric-card metric-courses">
                <div class="metric-icon shadow-sm">📚</div>
                <div class="metric-info">
                    <div class="metric-value"><?= $totalCoursesAssigned ?></div>
                    <div class="metric-label">My Courses</div>
                </div>
            </div>
        </div>

        <!-- Total Students Metric -->
        <div class="col-md-6 col-lg-4">
            <div class="metric-card metric-students">
                <div class="metric-icon shadow-sm">👨‍🎓</div>
                <div class="metric-info">
                    <div class="metric-value"><?= $totalStudents ?></div>
                    <div class="metric-label">Total Students</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Courses List -->
        <div class="col-lg-6">
            <div class="content-card">
                <h5 class="content-title"><i class="bi bi-journal-bookmark-fill text-warning"></i> Assigned Classes</h5>
                
                <?php if ($courses): ?>
                    <ul class="course-list mt-4">
                        <?php foreach ($courses as $c): ?>
                            <li class="course-item">
                                <i class="bi bi-book"></i>
                                <?= htmlspecialchars($c['name']) ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <div class="alert alert-light border text-center text-muted mt-3" role="alert">
                        <i class="bi bi-info-circle me-2"></i> You have no courses assigned yet.
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="col-lg-6">
            <div class="content-card">
                <h5 class="content-title"><i class="bi bi-grid-1x2-fill text-primary"></i> Rapid Shortcuts</h5>

                <div class="d-flex gap-3 flex-wrap mt-4">
                    <a href="attendance.php" class="btn-action btn-action-primary text-decoration-none shadow-sm">
                        <i class="bi bi-clipboard-check"></i> Take Attendance
                    </a>
                    <a href="upload_assignment.php" class="btn-action btn-action-success text-decoration-none shadow-sm">
                        <i class="bi bi-cloud-arrow-up"></i> Upload Assignment
                    </a>
                    <a href="upload_result.php" class="btn-action btn-action-warning text-decoration-none shadow-sm">
                        <i class="bi bi-bar-chart-fill"></i> Upload Results
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
echo "</div></div>"; // Close layout from header
require_once '../includes/footer.php';
?>