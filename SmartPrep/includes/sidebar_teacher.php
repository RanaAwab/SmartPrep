<!-- SIDEBAR -->
<div class="sidebar">

    <h4>Teacher Panel</h4>

    <!-- Dashboard -->
    <a href="<?= base_url('teacher/dashboard.php') ?>">
        <i class="bi bi-speedometer2"></i> Dashboard
    </a>

    <!-- Profile -->
    <a href="<?= base_url('teacher/profile.php') ?>">
        <i class="bi bi-person-fill"></i> My Profile
    </a>

    <!-- Academic -->
    <div class="section">Academic Division</div>

    <a href="<?= base_url('teacher/manage_courses.php') ?>">
        <i class="bi bi-book-half"></i> My Courses
    </a>

    <a href="<?= base_url('teacher/student_list.php') ?>">
        <i class="bi bi-people-fill"></i> Students
    </a>

    <a href="<?= base_url('teacher/attendance.php') ?>">
        <i class="bi bi-clipboard-check"></i> Attendance
    </a>

    <!-- Assignments -->
    <div class="section">Coursework</div>

    <a href="<?= base_url('teacher/upload_assignment.php') ?>">
        <i class="bi bi-cloud-arrow-up"></i> Upload Assignment
    </a>

    <a href="<?= base_url('teacher/manage_assignment.php') ?>">
        <i class="bi bi-folder2-open"></i> Manage Assignments
    </a>

    <!-- Results -->
    <div class="section">Evaluations</div>

    <a href="<?= base_url('teacher/upload_result.php') ?>">
        <i class="bi bi-bar-chart-fill"></i> Update Results
    </a>

    <a href="<?= base_url('teacher/view_result.php') ?>">
        <i class="bi bi-graph-up-arrow"></i> View Results
    </a>

    <!-- Lectures -->
    <div class="section">Media Content</div>

    <a href="<?= base_url('teacher/upload_lecture.php') ?>">
        <i class="bi bi-camera-video-fill"></i> Upload Lecture
    </a>

    <!-- Quiz -->
    <div class="section">Assessments</div>

    <a href="<?= base_url('teacher/create_quiz.php') ?>">
        <i class="bi bi-ui-checks-grid"></i> Create Quiz
    </a>

    <a href="<?= base_url('teacher/add_mcq.php') ?>">
        <i class="bi bi-plus-square-fill"></i> Add MCQs
    </a>

</div>

<!-- MAIN CONTENT -->
<div class="main-content">