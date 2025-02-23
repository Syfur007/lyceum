<?php
session_start();
require __DIR__.'/../config/database.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

// Get the post ID from the URL
$post_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch post details
$sql = "SELECT * FROM posts WHERE id = :post_id AND user_id = :user_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':post_id', $post_id);
$stmt->bindParam(':user_id', $_SESSION['user_id']);
$stmt->execute();
$post = $stmt->fetch();

if (!$post) {
    echo "<p>Post not found or you do not have permission to edit this post.</p>";
    exit();
}

// Fetch categories
$sql = "SELECT * FROM post_category";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$categories = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Post</title>
</head>
<body>
    <h2>Edit Post</h2>
    <form action="update_post.php" method="POST">
        <input type="hidden" name="post_id" value="<?php echo htmlspecialchars($post_id); ?>">
        <input type="hidden" name="community_id" value="<?php echo htmlspecialchars($post['community_id']); ?>">
        <label for="post_title">Post Title:</label>
        <input type="text" id="post_title" name="post_title" value="<?php echo htmlspecialchars($post['post_title']); ?>" required><br><br>

        <label for="post_description">Post Description:</label>
        <textarea id="post_description" name="post_description" required><?php echo htmlspecialchars($post['post_description']); ?></textarea><br><br>

        <label for="category_id">Category:</label>
        <select id="category_id" name="category_id" required>
            <?php foreach ($categories as $category): ?>
                <option value="<?php echo htmlspecialchars($category['id']); ?>" <?php echo $category['id'] == $post['category_id'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($category['title']); ?></option>
            <?php endforeach; ?>
        </select><br><br>

        <button type="submit">Update Post</button>
    </form>
    <a href="community.php?id=<?php echo htmlspecialchars($post['community_id']); ?>"><button>Back to Community</button></a>
</body>
</html>