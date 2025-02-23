<?php
session_start();
require __DIR__.'/../config/database.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $post_id = intval($_POST['post_id']);
    $community_id = intval($_POST['community_id']);
    $comment = trim($_POST['comment']);
    $user_id = $_SESSION['user_id'];

    // Validate form inputs
    if (empty($comment)) {
        echo "<script>console.log('Comment is required.');</script>";
        header("Location: post.php?id=$post_id");
        exit();
    }

    // Set parent_id to NULL
    $parent_id = null;

    // Prepare the SQL statement
    $sql = "INSERT INTO comment (post_id, user_id, community_id, parent_id, comment, created_at) 
            VALUES (:post_id, :user_id, :community_id, :parent_id, :comment, NOW())";
    $stmt = $pdo->prepare($sql);

    // Bind the parameters
    $stmt->bindParam(':post_id', $post_id);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':community_id', $community_id);
    $stmt->bindParam(':parent_id', $parent_id);
    $stmt->bindParam(':comment', $comment);

    // Execute the statement
    if ($stmt->execute()) {
        echo "<script>console.log('Comment added successfully!');</script>";
        header("Location: post.php?id=$post_id");
        exit();
    } else {
        echo "<script>console.log('Error: " . $stmt->errorInfo()[2] . "');</script>";
    }
}
?>