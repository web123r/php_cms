<?php
session_start();
require_once '../db.php';

// Check if logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

// Fetch posts
$stmt = $pdo->query("SELECT * FROM posts ORDER BY created_at DESC");
$posts = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - My PHP CMS</title>
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
        .admin-header a:hover {
            color: #72aee6;
        }
    </style>
</head>
<body>
    <div class="admin-header">
        <div><strong>My PHP CMS Dashboard</strong> | <a href="../index.php" target="_blank">View Site</a></div>
        <div>Howdy, <?= htmlspecialchars($_SESSION['admin_username']) ?> | <a href="logout.php">Log Out</a></div>
    </div>

    <div class="container content">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h2 style="margin: 0;">Posts</h2>
            <a href="add_post.php" class="button">Add New</a>
        </div>

        <table class="wp-list-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($posts)): ?>
                    <tr><td colspan="3">No posts found.</td></tr>
                <?php else: ?>
                    <?php foreach ($posts as $post): ?>
                        <tr>
                            <td><strong><a href="edit_post.php?id=<?= $post['id'] ?>"><?= htmlspecialchars($post['title']) ?></a></strong></td>
                            <td><?= date('Y/m/d g:i a', strtotime($post['created_at'])) ?></td>
                            <td>
                                <a href="edit_post.php?id=<?= $post['id'] ?>" style="color: #2271b1; text-decoration: none;">Edit</a> | 
                                <a href="delete_post.php?id=<?= $post['id'] ?>" style="color: #d63638; text-decoration: none;" onclick="return confirm('Are you sure you want to delete this post?');">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
