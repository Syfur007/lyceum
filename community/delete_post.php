<?php
session_start();
require __DIR__ . '/../config/database.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

// Get the post ID from the URL
$post_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch the post details to check if the user has permission to delete it
$sql = "SELECT * FROM posts WHERE id = :post_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':post_id', $post_id);
$stmt->execute();
$post = $stmt->fetch();

if (!$post) {
    echo "<p>Post not found.</p>";
    exit();
}

// Check if the logged-in user is the owner of the post or an admin
if ($post['user_id'] != $_SESSION['user_id'] && $_SESSION['role'] != 'admin') {
    echo "<p>You do not have permission to delete this post.</p>";
    exit();
}

// Delete the post
$sql = "DELETE FROM posts WHERE id = :post_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':post_id', $post_id);

if ($stmt->execute()) {
    echo "<script>console.log('Post deleted successfully!');</script>";
    header("Location: ../community.php?id=" . $post['community_id']);
    exit();
} else {
    echo "<script>console.log('Error: " . $stmt->errorInfo()[2] . "');</script>";
}
?>