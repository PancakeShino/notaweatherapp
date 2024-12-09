<?php
session_start();

// Check if user is logged in
/*if (!isset($_SESSION['validLogin'])) {
    header("login.php");
    exit();
}*/

include('header.php');

$username = "dom"; 
$password = "Aviator@1337"; 
$dsn = 'mysql:host=10.243.45.150;dbname=login';

try {
    $db = new PDO($dsn, $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
        $title = trim($_POST['title']);
        $content = trim($_POST['content']);
        $user_id = $_SESSION['user_id']; // make sure user_id stored in session

        $stmt = $db->prepare("INSERT INTO forum_posts (user_id, title, content) VALUES (?, ?, ?)");
        $stmt->execute([$user_id, $title, $content]);
        
        header("Location: forum.php");
        exit();
    }

    // grab all posts
    $stmt = $db->query("
        SELECT forum_posts.*, users.username 
        FROM forum_posts 
        JOIN users ON forum_posts.user_id = users.user_id 
        ORDER BY created_at DESC
    ");
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forum</title>
    <style>
        .forum-post {
            border: 1px solid #ddd;
            margin: 10px 0;
            padding: 15px;
            border-radius: 5px;
        }
        .post-header {
            border-bottom: 1px solid #eee;
            margin-bottom: 10px;
            padding-bottom: 5px;
        }
        .post-form {
            margin: 20px 0;
            padding: 15px;
            background: #f9f9f9;
            border-radius: 5px;
        }
        .post-form input, .post-form textarea {
            width: 100%;
            margin: 5px 0;
            padding: 5px;
        }
    </style>
</head>
<body>
    <main>
        <h1>Forum</h1>

        <!-- new post form -->
        <div class="post-form">
            <h2>Create New Post</h2>
            <form method="POST" action="forum.php">
                <input type="text" name="title" placeholder="Post Title" required><br>
                <textarea name="content" rows="4" placeholder="Post Content" required></textarea><br>
                <input type="submit" name="submit" value="Create Post">
            </form>
        </div>

        <!-- all posts -->
        <div class="forum-posts">
            <?php foreach ($posts as $post): ?>
                <div class="forum-post">
                    <div class="post-header">
                        <h3><?php echo htmlspecialchars($post['title']); ?></h3>
                        <small>Posted by <?php echo htmlspecialchars($post['username']); ?> 
                               on <?php echo date('F j, Y, g:i a', strtotime($post['created_at'])); ?></small>
                    </div>
                    <div class="post-content">
                        <?php echo nl2br(htmlspecialchars($post['content'])); ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </main>
</body>
</html>