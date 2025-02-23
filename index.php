<?php
session_start();

// Redirect logged-in users to the dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lyceum Homepage</title>
</head>
<body>
    <h1>Welcome to Lyceum</h1>
    <p>Please choose an option below:</p>
    <a href="auth/login.php"><button>Login</button></a>
    <a href="auth/register.php"><button>Register</button></a>
</body>
</html>