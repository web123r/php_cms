<?php
require_once 'db.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid post ID.");
}

$id = (int)$_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->execute([$id]);
$post = $stmt->fetch();

if (!$post) {
    die("Post not found.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($post['title']) ?> - My PHP CMS</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="container">
            <h1><a href="index.php">My PHP CMS</a></h1>
            <a href="admin/login.php">Admin Login</a>
        </div>
    </header>

    <div class="container content">
        <div class="post">
            <h2><?= htmlspecialchars($post['title']) ?></h2>
            <div class="post-meta">Published on <?= date('F j, Y, g:i a', strtotime($post['created_at'])) ?></div>
            <div class="post-content">
                <?= nl2br(htmlspecialchars($post['content'])) ?>
            </div>
            <p><a href="index.php">&larr; Back to home</a></p>
        </div>
    </div>
</body>
</html>
