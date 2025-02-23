<?php
session_start();
require __DIR__.'/../config/database.php';

// Check if the user is logged in and has the appropriate role
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['teacher', 'admin'])) {
    header("Location: ../auth/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $university_id = trim($_POST['university_id']);
    $institute_id = trim($_POST['institute_id']);
    $course_id = trim($_POST['course_id']);
    $batch_id = trim($_POST['batch_id']);
    $description = trim($_POST['description']);

    // Validate form inputs
    if (empty($name) || empty($university_id) || empty($institute_id) || empty($course_id) || empty($batch_id) || empty($description)) {
        echo "<script>console.log('All fields are required.');</script>";
        header("Location: create_community.php");
        exit();
    }

    // Prepare the SQL statement
    $sql = "INSERT INTO community (community_name, university_id, institute_id, course_id, batch_id, description) 
            VALUES (:name, :university_id, :institute_id, :course_id, :batch_id, :description)";
    $stmt = $pdo->prepare($sql);

    // Bind the parameters
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':university_id', $university_id);
    $stmt->bindParam(':institute_id', $institute_id);
    $stmt->bindParam(':course_id', $course_id);
    $stmt->bindParam(':batch_id', $batch_id);
    $stmt->bindParam(':description', $description);

    // Execute the statement
    if ($stmt->execute()) {
        echo "<script>console.log('Community created successfully!');</script>";
        header("Location: ../dashboard.php");
        exit();
    } else {
        echo "<script>console.log('Error: " . $stmt->errorInfo()[2] . "');</script>";
    }
}
?>