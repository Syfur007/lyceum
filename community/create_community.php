<?php
session_start();

// Check if the user is logged in and has the appropriate role
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['teacher', 'admin'])) {
    header("Location: ../auth/login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Community</title>
</head>
<body>
    <h2>Create a New Community</h2>
    <form action="create_community_process.php" method="POST">
        <label for="name">Community Name:</label>
        <input type="text" id="name" name="name" required><br><br>

        <label for="university_id">University ID:</label>
        <input type="number" id="university_id" name="university_id" required><br><br>

        <label for="institute_id">Institute ID:</label>
        <input type="number" id="institute_id" name="institute_id" required><br><br>

        <label for="course_id">Course ID:</label>
        <input type="number" id="course_id" name="course_id" required><br><br>

        <label for="batch_id">Batch ID:</label>
        <input type="number" id="batch_id" name="batch_id" required><br><br>

        <label for="description">Description:</label>
        <textarea id="description" name="description" required></textarea><br><br>

        <button type="submit">Create Community</button>
    </form>
</body>
</html>