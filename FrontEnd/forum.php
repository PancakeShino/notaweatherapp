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
    $stmt = $db->query("SELECT forum_posts.*, users.username FROM forum_posts JOIN users ON forum_posts.user_id = users.user_id ORDER BY created_at DESC");
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
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1 class="mb-4">Forum</h1>

        <!-- New Post Form -->
        <div class="card mb-4">
            <div class="card-header">Create New Post</div>
            <div class="card-body">
                <form method="POST" action="forum.php">
                    <div class="mb-3">
                        <label for="title" class="form-label">Post Title</label>
                        <input type="text" id="title" name="title" class="form-control" placeholder="Post Title" required>
                    </div>
                    <div class="mb-3">
                        <label for="content" class="form-label">Post Content</label>
                        <textarea id="content" name="content" class="form-control" rows="4" placeholder="Post Content" required></textarea>
                    </div>
                    <button type="submit" name="submit" class="btn btn-primary">Create Post</button>
                </form>
            </div>
        </div>

        <!-- All Posts -->
        <div class="forum-posts">
            <?php foreach ($posts as $post): ?>
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><?php echo htmlspecialchars($post['title']); ?></h5>
                        <small class="text-muted">Posted by <?php echo htmlspecialchars($post['username']); ?> on <?php echo date('F j, Y, g:i a', strtotime($post['created_at'])); ?></small>
                    </div>
                    <div class="card-body">
                        <p class="card-text">
                            <?php echo nl2br(htmlspecialchars($post['content'])); ?>
                        </p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>