<?php
session_start();
require_once '../db.php';

// Check if logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    if (empty($title) || empty($content)) {
        $error = "Title and Content are required.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO posts (title, content) VALUES (?, ?)");
        if ($stmt->execute([$title, $content])) {
            header("Location: index.php");
            exit;
        } else {
            $error = "Error saving post.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Post - My PHP CMS</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        .admin-header {
            background: #1d2327;
            color: #fff;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .admin-header a {
            color: #fff;
            text-decoration: none;
        }
    </style>
</head>
<body style="background: #f1f1f1;">
    <div class="admin-header">
        <div><strong>My PHP CMS Dashboard</strong> | <a href="../index.php" target="_blank">View Site</a></div>
        <div>Howdy, <?= htmlspecialchars($_SESSION['admin_username']) ?> | <a href="logout.php">Log Out</a></div>
    </div>

    <div class="container content">
        <h2>Add New Post</h2>
        
        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="post" action="add_post.php">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" name="title" id="title" value="<?= isset($_POST['title']) ? htmlspecialchars($_POST['title']) : '' ?>" required>
            </div>
            <div class="form-group">
                <label for="content">Content</label>
                <textarea name="content" id="content" rows="15" required><?= isset($_POST['content']) ? htmlspecialchars($_POST['content']) : '' ?></textarea>
            </div>
            <button type="submit">Publish</button>
            <a href="index.php" style="margin-left: 10px; color: #d63638; text-decoration: none;">Cancel</a>
        </form>
    </div>
</body>
</html>
