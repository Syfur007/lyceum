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

// Fetch universities
$sql = "SELECT id, name FROM university";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$universities = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch institutes
$sql = "SELECT id, name FROM institute";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$institutes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch courses
$sql = "SELECT id, course_title FROM courses";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
    $sql = "UPDATE community SET community_name = :community_name, university_id = :university_id, institute_id = :institute_id, course_id = :course_id, batch_id = :batch_id, description = :description 
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
        header("Location: ../community.php?id=$community_id");
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
        .form-container {
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
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input, textarea, select {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
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
        }
        button:hover {
            background-color: #218838;
        }
        .back-button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            border: none;
            border-radius: 4px;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            text-align: center;
        }
        .back-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Edit Community</h2>
        <form action="edit_community.php?id=<?php echo htmlspecialchars($community_id); ?>" method="POST">
            <label for="community_name">Community Name:</label>
            <input type="text" id="community_name" name="community_name" value="<?php echo htmlspecialchars($community['community_name']); ?>" required><br><br>

            <label for="university_id">University:</label>
            <select id="university_id" name="university_id" required>
                <?php foreach ($universities as $university): ?>
                    <option value="<?php echo $university['id']; ?>" <?php echo $university['id'] == $community['university_id'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($university['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select><br><br>

            <label for="institute_id">Institute:</label>
            <select id="institute_id" name="institute_id" required>
                <?php foreach ($institutes as $institute): ?>
                    <option value="<?php echo $institute['id']; ?>" <?php echo $institute['id'] == $community['institute_id'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($institute['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select><br><br>

            <label for="course_id">Course:</label>
            <select id="course_id" name="course_id" required>
                <?php foreach ($courses as $course): ?>
                    <option value="<?php echo $course['id']; ?>" <?php echo $course['id'] == $community['course_id'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($course['course_title']); ?>
                    </option>
                <?php endforeach; ?>
            </select><br><br>

            <label for="batch_id">Batch ID:</label>
            <input type="number" id="batch_id" name="batch_id" value="<?php echo htmlspecialchars($community['batch_id']); ?>" required><br><br>

            <label for="description">Description:</label>
            <textarea id="description" name="description" required><?php echo htmlspecialchars($community['description']); ?></textarea><br><br>

            <button type="submit">Update Community</button>
        </form>
        <a href="view_communities.php"><button class="back-button">Back to Communities</button></a>
    </div>
</body>
</html>