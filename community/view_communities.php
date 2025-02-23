<?php
session_start();
require __DIR__.'/../config/database.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

// Fetch communities the user is part of
$sql = "SELECT c.* FROM community c
        JOIN user_in_community uic ON c.id = uic.community_id
        WHERE uic.user_id = :user_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':user_id', $_SESSION['user_id']);
$stmt->execute();
$communities = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Communities</title>
</head>
<body>
    <h2>All Communities</h2>
    <?php if (count($communities) > 0): ?>
        <ul>
            <?php foreach ($communities as $community): ?>
                <li>
                    <h3><?php echo htmlspecialchars($community['community_name']); ?></h3>
                    <p><?php echo htmlspecialchars($community['description']); ?></p>
                    <p>University ID: <?php echo htmlspecialchars($community['university_id']); ?></p>
                    <p>Institute ID: <?php echo htmlspecialchars($community['institute_id']); ?></p>
                    <p>Course ID: <?php echo htmlspecialchars($community['course_id']); ?></p>
                    <p>Batch ID: <?php echo htmlspecialchars($community['batch_id']); ?></p>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No communities found.</p>
    <?php endif; ?>
    <a href="../dashboard.php"><button>Back to Dashboard</button></a>
</body>
</html>