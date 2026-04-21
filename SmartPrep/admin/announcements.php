<?php
require_once '../config.php';
require_once '../includes/db.php';
require_once '../includes/auth.php';

// 🔐 Protection
requireLogin();
requireRole('admin');

$error = "";
$success = "";

// Handle delete
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];

    executeQuery("DELETE FROM announcements WHERE id = ?", [$id]);

    header("Location: announcements.php");
    exit();
}

// Handle form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title   = trim($_POST['title']);
    $message = trim($_POST['message']);

    if (empty($title) || empty($message)) {
        $error = "All fields are required!";
    } else {

        $insert = executeQuery(
            "INSERT INTO announcements (title, message) VALUES (?, ?)",
            [$title, $message]
        );

        if ($insert) {
            $success = "Announcement posted!";
        } else {
            $error = "Something went wrong!";
        }
    }
}

// Fetch announcements
$announcements = fetchAll("SELECT * FROM announcements ORDER BY id DESC");

// Page title
$title = "Announcements";
require_once '../includes/header.php';
?>

<?php require_once '../includes/sidebar_admin.php'; ?>

<h2 class="mb-4">Announcements</h2>

<!-- Add Announcement -->
<div class="card shadow p-4 mb-4">

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="POST">

        <div class="mb-3">
            <label class="form-label">Title</label>
            <input type="text" name="title" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Message</label>
            <textarea name="message" class="form-control" rows="4" required></textarea>
        </div>

        <button class="btn btn-primary">Post Announcement</button>

    </form>

</div>

<!-- Announcement List -->
<div class="card shadow p-3">

    <h5 class="mb-3">All Announcements</h5>

    <?php if ($announcements): ?>
        <?php foreach ($announcements as $a): ?>
            <div class="border rounded p-3 mb-3">

                <div class="d-flex justify-content-between">
                    <h5><?= htmlspecialchars($a['title']) ?></h5>
                    <a href="?delete=<?= $a['id'] ?>" 
                       class="btn btn-sm btn-danger"
                       onclick="return confirm('Delete this announcement?')">
                        Delete
                    </a>
                </div>

                <p class="mt-2"><?= nl2br(htmlspecialchars($a['message'])) ?></p>

            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No announcements yet.</p>
    <?php endif; ?>

</div>

<?php
echo "</div></div>";
require_once '../includes/footer.php';
?>