<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit();
}

echo "<h1>Welcome, " . htmlspecialchars($_SESSION['username']) . "!</h1>";
echo "<p>Your role is: " . htmlspecialchars($_SESSION['role']) . "</p>";
echo "<a href='auth/logout.php'><button>Logout</button></a>";
?>