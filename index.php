<?php
require_once 'db.php';

// Fetch all posts ordered by newest first
$stmt = $pdo->query("SELECT * FROM posts ORDER BY created_at DESC");
$posts = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My PHP CMS</title>
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
        <?php if (empty($posts)): ?>
            <p>No posts found.</p>
        <?php else: ?>
            <?php foreach ($posts as $post): ?>
                <div class="post">
                    <h2><a href="post.php?id=<?= $post['id'] ?>"><?= htmlspecialchars($post['title']) ?></a></h2>
                    <div class="post-meta">Published on <?= date('F j, Y', strtotime($post['created_at'])) ?></div>
                    <div class="post-content">
                        <?php 
                        // Show a short snippet of content
                        $snippet = strip_tags($post['content']);
                        if (strlen($snippet) > 200) {
                            $snippet = substr($snippet, 0, 200) . '...';
                        }
                        echo nl2br(htmlspecialchars($snippet));
                        ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html>
