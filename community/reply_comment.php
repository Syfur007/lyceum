<?php
session_start();
require __DIR__.'/../config/database.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

// Get the post ID and parent comment ID from the URL
$post_id = isset($_GET['post_id']) ? intval($_GET['post_id']) : 0;
$parent_id = isset($_GET['parent_id']) ? intval($_GET['parent_id']) : 0;

// Fetch post details
$sql = "SELECT * FROM posts WHERE id = :post_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':post_id', $post_id);
$stmt->execute();
$post = $stmt->fetch();

if (!$post) {
    echo "<p>Post not found.</p>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reply to Comment</title>
</head>
<body>
    <h2>Reply to Comment</h2>
    <form action="create_comment.php" method="POST">
        <input type="hidden" name="post_id" value="<?php echo htmlspecialchars($post_id); ?>">
        <input type="hidden" name="community_id" value="<?php echo htmlspecialchars($post['community_id']); ?>">
        <input type="hidden" name="parent_id" value="<?php echo htmlspecialchars($parent_id); ?>">
        <label for="comment">Comment:</label>
        <textarea id="comment" name="comment" required></textarea><br><br>
        <button type="submit">Reply</button>
    </form>
    <a href="post.php?id=<?php echo htmlspecialchars($post_id); ?>"><button>Back to Post</button></a>
</body>
</html>