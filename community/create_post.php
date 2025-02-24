<?php
session_start();
require __DIR__.'/../config/database.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $community_id = intval($_POST['community_id']);
    $post_title = trim($_POST['post_title']);
    $post_description = trim($_POST['post_description']);
    $category_id = intval($_POST['category_id']);
    $user_id = $_SESSION['user_id'];

    // Validate form inputs
    if (empty($post_title) || empty($post_description) || empty($category_id)) {
        echo "<script>console.log('All fields are required.');</script>";
        header("Location: community.php?id=$community_id");
        exit();
    }

    // Prepare the SQL statement
    $sql = "INSERT INTO posts (user_id, community_id, category_id, post_title, post_description, created_at) 
            VALUES (:user_id, :community_id, :category_id, :post_title, :post_description, NOW())";
    $stmt = $pdo->prepare($sql);

    // Bind the parameters
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':community_id', $community_id);
    $stmt->bindParam(':category_id', $category_id);
    $stmt->bindParam(':post_title', $post_title);
    $stmt->bindParam(':post_description', $post_description);

    // Execute the statement
    if ($stmt->execute()) {
        echo "<script>console.log('Post created successfully!');</script>";
        header("Location: ../community.php?id=$community_id");
        exit();
    } else {
        echo "<script>console.log('Error: " . $stmt->errorInfo()[2] . "');</script>";
    }
}
?>