<!-- SIDEBAR -->
<div class="sidebar">

    <h4>Student Portal</h4>

    <!-- Dashboard -->
    <a href="<?= base_url('student/dashboard.php') ?>">
        <i class="bi bi-speedometer2"></i> Dashboard
    </a>

    <!-- Profile -->
    <a href="<?= base_url('student/profile.php') ?>">
        <i class="bi bi-person-fill"></i> My Profile
    </a>

    <!-- Academic -->
    <div class="section">Academic Division</div>

    <a href="<?= base_url('student/courses.php') ?>">
        <i class="bi bi-book-half"></i> Courses
    </a>

    <a href="<?= base_url('student/timetable.php') ?>">
        <i class="bi bi-calendar3"></i> Timetable
    </a>

    <a href="<?= base_url('student/attendance.php') ?>">
        <i class="bi bi-clipboard-check"></i> Attendance
    </a>

    <!-- Assignments -->
    <div class="section">Coursework</div>

    <a href="<?= base_url('student/assignments.php') ?>">
        <i class="bi bi-folder2-open"></i> View Assignments
    </a>

    <a href="<?= base_url('student/submit_assignment.php') ?>">
        <i class="bi bi-cloud-arrow-up"></i> Submit Assignment
    </a>

    <!-- Results -->
    <div class="section">Performance</div>

    <a href="<?= base_url('student/results.php') ?>">
        <i class="bi bi-bar-chart-fill"></i> My Results
    </a>

    <!-- Lectures -->
    <div class="section">Study Material</div>

    <a href="<?= base_url('student/lectures.php') ?>">
        <i class="bi bi-camera-video-fill"></i> Lectures
    </a>

    <!-- Quiz -->
    <div class="section">Assessments</div>

    <a href="<?= base_url('student/attempt_quiz.php') ?>">
        <i class="bi bi-ui-checks-grid"></i> Attempt Quiz
    </a>

    <a href="<?= base_url('student/view_quiz_result.php') ?>">
        <i class="bi bi-graph-up-arrow"></i> Quiz Results
    </a>

</div>

<!-- MAIN CONTENT -->
<div class="main-content">