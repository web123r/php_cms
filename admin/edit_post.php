<?php
session_start();
require_once '../db.php';

// Check if logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid post ID.");
}

$id = (int)$_GET['id'];
$error = '';
$success = '';

// Fetch existing post
$stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->execute([$id]);
$post = $stmt->fetch();

if (!$post) {
    die("Post not found.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    if (empty($title) || empty($content)) {
        $error = "Title and Content are required.";
    } else {
        $update_stmt = $pdo->prepare("UPDATE posts SET title = ?, content = ? WHERE id = ?");
        if ($update_stmt->execute([$title, $content, $id])) {
            $success = "Post updated successfully.";
            // Refresh post data
            $post['title'] = $title;
            $post['content'] = $content;
        } else {
            $error = "Error updating post.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Post - My PHP CMS</title>
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
        .success {
            background: #f0f6fc;
            border-left: 4px solid #2271b1;
            padding: 12px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body style="background: #f1f1f1;">
    <div class="admin-header">
        <div><strong>My PHP CMS Dashboard</strong> | <a href="../index.php" target="_blank">View Site</a></div>
        <div>Howdy, <?= htmlspecialchars($_SESSION['admin_username']) ?> | <a href="logout.php">Log Out</a></div>
    </div>

    <div class="container content">
        <h2>Edit Post</h2>
        
        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="success"><?= htmlspecialchars($success) ?> <a href="../post.php?id=<?= $post['id'] ?>" target="_blank">View Post</a></div>
        <?php endif; ?>

        <form method="post" action="edit_post.php?id=<?= $post['id'] ?>">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" name="title" id="title" value="<?= htmlspecialchars($post['title']) ?>" required>
            </div>
            <div class="form-group">
                <label for="content">Content</label>
                <textarea name="content" id="content" rows="15" required><?= htmlspecialchars($post['content']) ?></textarea>
            </div>
            <button type="submit">Update</button>
            <a href="index.php" style="margin-left: 10px; color: #d63638; text-decoration: none;">Cancel</a>
        </form>
    </div>
</body>
</html>
