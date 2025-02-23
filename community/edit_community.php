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

// Fetch community details
$sql = "SELECT * FROM community WHERE id = :community_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':community_id', $community_id);
$stmt->execute();
$community = $stmt->fetch();

if (!$community) {
    echo "<p>Community not found.</p>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $community_name = trim($_POST['community_name']);
    $university_id = intval($_POST['university_id']);
    $institute_id = intval($_POST['institute_id']);
    $course_id = intval($_POST['course_id']);
    $batch_id = intval($_POST['batch_id']);
    $description = trim($_POST['description']);

    // Validate form inputs
    if (empty($community_name) || empty($description)) {
        echo "<script>console.log('All fields are required.');</script>";
        header("Location: edit_community.php?id=$community_id");
        exit();
    }

    // Prepare the SQL statement
    $sql = "UPDATE community SET community_name = :community_name, university_id = :university_id, institute_id = :institute_id, course_id = :course_id, batch_id = :batch_id, description = :description, modified_at = NOW() 
            WHERE id = :community_id";
    $stmt = $pdo->prepare($sql);

    // Bind the parameters
    $stmt->bindParam(':community_name', $community_name);
    $stmt->bindParam(':university_id', $university_id);
    $stmt->bindParam(':institute_id', $institute_id);
    $stmt->bindParam(':course_id', $course_id);
    $stmt->bindParam(':batch_id', $batch_id);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':community_id', $community_id);

    // Execute the statement
    if ($stmt->execute()) {
        echo "<script>console.log('Community updated successfully!');</script>";
        header("Location: view_communities.php");
        exit();
    } else {
        echo "<script>console.log('Error: " . $stmt->errorInfo()[2] . "');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Community</title>
</head>
<body>
    <h2>Edit Community</h2>
    <form action="edit_community.php?id=<?php echo htmlspecialchars($community_id); ?>" method="POST">
        <label for="community_name">Community Name:</label>
        <input type="text" id="community_name" name="community_name" value="<?php echo htmlspecialchars($community['community_name']); ?>" required><br><br>

        <label for="university_id">University ID:</label>
        <input type="number" id="university_id" name="university_id" value="<?php echo htmlspecialchars($community['university_id']); ?>" required><br><br>

        <label for="institute_id">Institute ID:</label>
        <input type="number" id="institute_id" name="institute_id" value="<?php echo htmlspecialchars($community['institute_id']); ?>" required><br><br>

        <label for="course_id">Course ID:</label>
        <input type="number" id="course_id" name="course_id" value="<?php echo htmlspecialchars($community['course_id']); ?>" required><br><br>

        <label for="batch_id">Batch ID:</label>
        <input type="number" id="batch_id" name="batch_id" value="<?php echo htmlspecialchars($community['batch_id']); ?>" required><br><br>

        <label for="description">Description:</label>
        <textarea id="description" name="description" required><?php echo htmlspecialchars($community['description']); ?></textarea><br><br>

        <button type="submit">Update Community</button>
    </form>
    <a href="view_communities.php"><button>Back to Communities</button></a>
</body>
</html>