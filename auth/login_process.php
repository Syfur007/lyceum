<?php
session_start();
require __DIR__.'/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare the SQL statement to check email
    $sql = "SELECT * FROM users WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    // Fetch the user
    $user = $stmt->fetch();

    // Verify the password
    if ($user && password_verify($password, $user['password'])) {
        // Password is correct, create session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        // Redirect to dashboard
        echo "<script>console.log('Login successful!');</script>";
        header("Location: ../dashboard.php");
        exit();
    } else {
        // Invalid credentials
        echo "<script>console.log('Invalid email or password.');</script>";
    }
}
?>