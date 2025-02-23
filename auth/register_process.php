<?php

require __DIR__.'/../../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $dob = $_POST['dob'];
    $university_id = $_POST['university_id'];
    $institution_id = $_POST['institution_id'];
    $batch_id = $_POST['batch_id'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Get the current timestamp
    $created_at = date('Y-m-d H:i:s');

    // Prepare the SQL statement
    $sql = "INSERT INTO users (username, first_name, last_name, dob, university_id, institution_id, batch_id, email, password, role, created_at) 
            VALUES (:username, :first_name, :last_name, :dob, :university_id, :institution_id, :batch_id, :email, :password, :role, :created_at)";
    $stmt = $pdo->prepare($sql);

    // Bind the parameters
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':first_name', $first_name);
    $stmt->bindParam(':last_name', $last_name);
    $stmt->bindParam(':dob', $dob);
    $stmt->bindParam(':university_id', $university_id);
    $stmt->bindParam(':institution_id', $institution_id);
    $stmt->bindParam(':batch_id', $batch_id);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashed_password);
    $stmt->bindParam(':role', $role);
    $stmt->bindParam(':created_at', $created_at);

    // Execute the statement
    if ($stmt->execute()) {
        echo "<script>console.log('User registered successfully!');</script>";
        header("Location: /login.php");
        exit();
    } else {
        echo "<script>console.log('Error: " . $stmt->errorInfo()[2] . "');</script>";
    }
}
?>