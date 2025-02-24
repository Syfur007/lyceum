<?php
session_start();
require_once '../config/database.php'; // Include your database connection file

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get the user ID from the query parameter or session
$user_id = isset($_GET['id']) ? intval($_GET['id']) : $_SESSION['user_id'];

// Prepare and execute the query to get user information
$sql = "
    SELECT 
        users.*, 
        university.name AS university_name, 
        institute.name AS institute_name 
    FROM 
        users 
    JOIN 
        university ON users.university_id = university.id 
    JOIN 
        institute ON users.institution_id = institute.id 
    WHERE 
        users.id = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id', $user_id);
$stmt->execute();
$user = $stmt->fetch();

// Check if the user exists
if (!$user) {
    echo "User not found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .profile-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        p {
            margin: 10px 0;
        }

        .button {
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            border: none;
            border-radius: 4px;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            text-align: center;
            margin-bottom: 10px;
            margin-top: 10px;
        }

        .button:hover {
            background-color: #218838;
        }

        .logout-button {
            width: 100%;
            padding: 10px;
            background-color: #dc3545;
            border: none;
            border-radius: 4px;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            text-align: center;
            margin-top: 20px;
        }

        .logout-button:hover {
            background-color: #c82333;
        }
    </style>
</head>

<body>
    <div class="profile-container">
        <h2>User Profile</h2>
        <p><strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
        <p><strong>First Name:</strong> <?php echo htmlspecialchars($user['first_name']); ?></p>
        <p><strong>Last Name:</strong> <?php echo htmlspecialchars($user['last_name']); ?></p>
        <p><strong>Date of Birth:</strong> <?php echo htmlspecialchars($user['dob']); ?></p>
        <p><strong>University:</strong> <?php echo htmlspecialchars($user['university_name']); ?></p>
        <p><strong>Institution:</strong> <?php echo htmlspecialchars($user['institute_name']); ?></p>
        <p><strong>Batch ID:</strong> <?php echo htmlspecialchars($user['batch_id']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
        <p><strong>Role:</strong> <?php echo htmlspecialchars($user['role']); ?></p>
        <?php if ($user_id == $_SESSION['user_id']): ?>
            <a href="edit_profile.php" class="button">Edit Profile</a>
        <?php endif; ?>
        <form action="logout.php" method="POST">
            <button type="submit" class="logout-button">Logout</button>
        </form>
    </div>
</body>

</html>