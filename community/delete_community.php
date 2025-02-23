<?php
session_start();
require __DIR__.'/../config/database.php';

// Check if the user is logged in and is a teacher
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
    header("Location: ../auth/login.php");
    exit();
}

// Get the community ID from the URL
$community_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Prepare the SQL statement
$sql = "DELETE FROM community WHERE id = :community_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':community_id', $community_id);

// Execute the statement
if ($stmt->execute()) {
    echo "<script>console.log('Community deleted successfully!');</script>";
    header("Location: view_communities.php");
    exit();
} else {
    echo "<script>console.log('Error: " . $stmt->errorInfo()[2] . "');</script>";
}
?>