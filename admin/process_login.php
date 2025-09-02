<?php
session_start();
include 'db_connection.php'; // Make sure to create and include your database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Create a prepared statement
    if ($stmt = $conn->prepare('SELECT id, password FROM admins WHERE email = ?')) {
        // Bind parameters (s = string, i = int, b = blob, etc)
        $stmt->bind_param('s', $email);
        $stmt->execute();
        // Store the result so we can check if the account exists in the database.
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $hashed_password);
            $stmt->fetch();
            // Verify the password.
            if (password_verify($password, $hashed_password)) {
                // Verification success! User has logged-in!
                session_regenerate_id();
                $_SESSION['loggedin'] = TRUE;
                $_SESSION['name'] = $email;
                $_SESSION['id'] = $id;
                header('Location: admin_dashboard.php');
            } else {
                echo 'Incorrect password!';
            }
        } else {
            echo 'Incorrect email!';
        }
        $stmt->close();
    }
}
?>