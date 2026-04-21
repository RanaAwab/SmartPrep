<!-- SIDEBAR -->
<div class="sidebar">

    <h4>Admin Panel</h4>

    <!-- Dashboard -->
    <a href="<?= base_url('admin/dashboard.php') ?>">
        <i class="bi bi-speedometer2"></i> Dashboard
    </a>

    <!-- Users -->
    <div class="section">Users Management</div>

    <a href="<?= base_url('admin/manage_students.php') ?>">
        <i class="bi bi-people-fill"></i> Students
    </a>

    <a href="<?= base_url('admin/manage_teachers.php') ?>">
        <i class="bi bi-person-video3"></i> Teachers
    </a>

    <!-- Used a subtle gold/yellow hex instead of raw emoji for a premium feel -->
    <a href="<?= base_url('admin/approve_users.php') ?>" style="color: #fbbf24;">
        <i class="bi bi-person-check-fill"></i> Approvals
    </a>

    <!-- Academic -->
    <div class="section">Academic Division</div>

    <a href="<?= base_url('admin/manage_departments.php') ?>">
        <i class="bi bi-building"></i> Departments
    </a>

    <a href="<?= base_url('admin/manage_courses.php') ?>">
        <i class="bi bi-book-half"></i> Courses
    </a>

    <a href="<?= base_url('admin/manage_subjects.php') ?>">
        <i class="bi bi-journal-text"></i> Subjects
    </a>

    <!-- Management -->
    <div class="section">Operations</div>

    <a href="<?= base_url('admin/assign_teacher.php') ?>">
        <i class="bi bi-person-workspace"></i> Assign Teacher
    </a>

    <a href="<?= base_url('admin/timetable.php') ?>">
        <i class="bi bi-calendar3"></i> Timetable
    </a>

    <a href="<?= base_url('admin/announcements.php') ?>">
        <i class="bi bi-megaphone-fill"></i> Announcements
    </a>

</div>

<!-- MAIN CONTENT -->
<div class="main-content">