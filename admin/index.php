<?php
    if (isset($_SESSION['user_id']) and $_SESSION['role'] == 'admin') {
        header("Location: dashboard.php");
    } 
    else {
        header("Location: admin_login.php");
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
</body>
</html>