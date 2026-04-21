<?php
require_once '../config.php';
require_once '../includes/db.php';
require_once '../includes/auth.php';

// 🔐 Protection
requireLogin();
requireRole('admin');

$error = "";
$success = "";

// Fetch data
$subjects = fetchAll("SELECT id, name FROM subjects");
$teachers = fetchAll("SELECT id, name FROM users WHERE role = 'teacher'");

// Handle form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $subject_id = (int) $_POST['subject_id'];
    $teacher_id = (int) $_POST['teacher_id'];
    $day        = $_POST['day'];
    $time       = $_POST['time'];

    if (empty($subject_id) || empty($teacher_id) || empty($day) || empty($time)) {
        $error = "All fields are required!";
    } else {

        $insert = executeQuery(
            "INSERT INTO timetable (subject_id, teacher_id, day, time) VALUES (?, ?, ?, ?)",
            [$subject_id, $teacher_id, $day, $time]
        );

        if ($insert) {
            $success = "Timetable entry added!";
        } else {
            $error = "Something went wrong!";
        }
    }
}

// Fetch timetable
$timetable = fetchAll("
    SELECT t.id, s.name AS subject, u.name AS teacher, t.day, t.time
    FROM timetable t
    JOIN subjects s ON t.subject_id = s.id
    JOIN users u ON t.teacher_id = u.id
    ORDER BY FIELD(t.day, 'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'), t.time
");

// Page title
$title = "Timetable";
require_once '../includes/header.php';
?>

<?php require_once '../includes/sidebar_admin.php'; ?>

<h2 class="mb-4">Manage Timetable</h2>

<!-- Add Timetable -->
<div class="card shadow p-4 mb-4">

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="POST" class="row g-3">

        <div class="col-md-3">
            <label>Subject</label>
            <select name="subject_id" class="form-select" required>
                <option value="">Select</option>
                <?php foreach ($subjects as $s): ?>
                    <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-3">
            <label>Teacher</label>
            <select name="teacher_id" class="form-select" required>
                <option value="">Select</option>
                <?php foreach ($teachers as $t): ?>
                    <option value="<?= $t['id'] ?>"><?= htmlspecialchars($t['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-2">
            <label>Day</label>
            <select name="day" class="form-select" required>
                <option>Monday</option>
                <option>Tuesday</option>
                <option>Wednesday</option>
                <option>Thursday</option>
                <option>Friday</option>
                <option>Saturday</option>
            </select>
        </div>

        <div class="col-md-2">
            <label>Time</label>
            <input type="time" name="time" class="form-control" required>
        </div>

        <div class="col-md-2 d-flex align-items-end">
            <button class="btn btn-primary w-100">Add</button>
        </div>

    </form>

</div>

<!-- Timetable List -->
<div class="card shadow p-3">

    <h5 class="mb-3">Full Timetable</h5>

    <table class="table table-bordered text-center">

        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Subject</th>
                <th>Teacher</th>
                <th>Day</th>
                <th>Time</th>
            </tr>
        </thead>

        <tbody>
            <?php if ($timetable): ?>
                <?php foreach ($timetable as $t): ?>
                    <tr>
                        <td><?= $t['id'] ?></td>
                        <td><?= htmlspecialchars($t['subject']) ?></td>
                        <td><?= htmlspecialchars($t['teacher']) ?></td>
                        <td><?= $t['day'] ?></td>
                        <td><?= $t['time'] ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">No timetable entries</td>
                </tr>
            <?php endif; ?>
        </tbody>

    </table>

</div>

<?php
echo "</div></div>";
require_once '../includes/footer.php';
?>