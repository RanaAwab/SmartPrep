<?php
require_once '../config.php';
require_once '../includes/db.php';
require_once '../includes/auth.php';

// 🔐 Protect page
requireLogin();
requireRole('admin');

// Fetch stats
$totalStudents = fetch("SELECT COUNT(*) as count FROM users WHERE role = 'student'")['count'];
$totalTeachers = fetch("SELECT COUNT(*) as count FROM users WHERE role = 'teacher'")['count'];
$totalCourses  = fetch("SELECT COUNT(*) as count FROM courses")['count'] ?? 0;

// Fetch pending approvals for extra dashboard detail
$pendingUsers = fetch("SELECT COUNT(*) as count FROM users WHERE status = 'pending'")['count'] ?? 0;

// Page title
$title = "Admin Dashboard";
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

.metric-students::before { background: linear-gradient(to bottom, #3b82f6, #60a5fa); }
.metric-teachers::before { background: linear-gradient(to bottom, #10b981, #34d399); }
.metric-courses::before { background: linear-gradient(to bottom, #f59e0b, #fbbf24); }
.metric-pending::before { background: linear-gradient(to bottom, #ef4444, #f87171); }

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

.action-card {
    background: #ffffff;
    border-radius: 16px;
    padding: 28px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.02);
    border: 1px solid rgba(0,0,0,0.03);
    margin-top: 2rem;
}

.action-title {
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

.btn-action-primary {
    background: #eff6ff;
    color: #2563eb;
    border-color: #bfdbfe;
}
.btn-action-primary:hover { background: #dbeafe; color: #1d4ed8; transform: translateY(-2px); }

.btn-action-success {
    background: #f0fdf4;
    color: #16a34a;
    border-color: #bbf7d0;
}
.btn-action-success:hover { background: #dcfce7; color: #15803d; transform: translateY(-2px); }

.btn-action-warning {
    background: #fffbeb;
    color: #d97706;
    border-color: #fde68a;
}
.btn-action-warning:hover { background: #fef3c7; color: #b45309; transform: translateY(-2px); }

.btn-action-danger {
    background: #fef2f2;
    color: #dc2626;
    border-color: #fecaca;
}
.btn-action-danger:hover { background: #fee2e2; color: #b91c1c; transform: translateY(-2px); }

</style>

<?php require_once '../includes/sidebar_admin.php'; ?>

<div class="dashboard-wrapper">
    <div class="dash-header">
        <div>
            <h1 class="dash-title">Admin Dashboard</h1>
            <div class="dash-subtitle">Overview of system metrics and quick management actions.</div>
        </div>
        <div>
            <span class="badge bg-primary bg-gradient fs-6 px-4 py-2 rounded-pill shadow-sm">
                <i class="bi bi-calendar3 me-1"></i> <?= date('F j, Y') ?>
            </span>
        </div>
    </div>

    <div class="row g-4">
        <!-- Students -->
        <div class="col-md-6 col-lg-3">
            <div class="metric-card metric-students">
                <div class="metric-icon shadow-sm">👨‍🎓</div>
                <div class="metric-info">
                    <div class="metric-value"><?= $totalStudents ?></div>
                    <div class="metric-label">Students</div>
                </div>
            </div>
        </div>

        <!-- Teachers -->
        <div class="col-md-6 col-lg-3">
            <div class="metric-card metric-teachers">
                <div class="metric-icon shadow-sm">👨‍🏫</div>
                <div class="metric-info">
                    <div class="metric-value"><?= $totalTeachers ?></div>
                    <div class="metric-label">Teachers</div>
                </div>
            </div>
        </div>

        <!-- Courses -->
        <div class="col-md-6 col-lg-3">
            <div class="metric-card metric-courses">
                <div class="metric-icon shadow-sm">📚</div>
                <div class="metric-info">
                    <div class="metric-value"><?= $totalCourses ?></div>
                    <div class="metric-label">Courses</div>
                </div>
            </div>
        </div>

        <!-- Pending Approvals -->
        <div class="col-md-6 col-lg-3">
            <div class="metric-card metric-pending">
                <div class="metric-icon shadow-sm">⏳</div>
                <div class="metric-info">
                    <div class="metric-value"><?= $pendingUsers ?></div>
                    <div class="metric-label">Pending Req.</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="action-card">
        <h5 class="action-title">
            <i class="bi bi-grid-1x2-fill text-primary"></i> Rapid Shortcuts
        </h5>

        <div class="d-flex gap-3 flex-wrap mt-4">
            <a href="manage_students.php" class="btn-action btn-action-primary text-decoration-none shadow-sm">
                <i class="bi bi-people-fill"></i> Manage Students
            </a>
            <a href="manage_teachers.php" class="btn-action btn-action-success text-decoration-none shadow-sm">
                <i class="bi bi-person-video3"></i> Manage Teachers
            </a>
            <a href="manage_courses.php" class="btn-action btn-action-warning text-decoration-none shadow-sm">
                <i class="bi bi-book-half"></i> Manage Courses
            </a>
            <a href="approve_users.php" class="btn-action btn-action-danger text-decoration-none shadow-sm position-relative">
                <i class="bi bi-person-check-fill"></i> Review Approvals
                <?php if($pendingUsers > 0): ?>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-white">
                        <?= $pendingUsers ?>
                    </span>
                <?php endif; ?>
            </a>
        </div>
    </div>
</div>

<?php
// Close layout
echo "</div></div>";

require_once '../includes/footer.php';
?>