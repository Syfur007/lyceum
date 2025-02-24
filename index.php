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
    <title>Home: Lyceum</title>
    <link rel="stylesheet" href="css/home.css">
</head>

<body>

    <div class="main">
        <div class="navbar">
            <div class="icon">
                <h2 class="logo">LYCEUM</h2>
            </div>

            <div class="menu">
                <ul>
                    <li><a href="#">HOME</a></li>
                    <li><a href="#">ABOUT</a></li>
                    <li><a href="#">FEATURES</a></li>
                    <li><a href="#">CONTACT</a></li>
                </ul>
            </div>

        </div>
        <div class="content">
            <h1>Welcome to <br><span>Lyceum</span> <br>Connecting Minds, Empowering Learning</h1>
            <p class="par">Lyceum is a collaborative platform that bridges the gap between students<br> and faculty, fostering seamless communication, resource sharing, and academic <br> discussions within course-based communities.</p>

            <button class="cn"><a href="auth/register.php">JOIN US</a></button>

            <form class="form" action="auth/login_process.php" method="POST">
                <h2>Login Here</h2>
                <input type="email" name="email" placeholder="Enter Email Here" required>
                <input type="password" name="password" placeholder="Enter Password Here" required>
                <button class="btnn" type="submit">Login</button>

                <p class="link">Don't have an account<br>
                    <a href="auth/register.php">Sign up </a> here</a>
                </p>
                <!-- <p class="liw">Log in with</p>

                <div class="icons">
                    <a href="#"><ion-icon name="logo-facebook"></ion-icon></a>
                    <a href="#"><ion-icon name="logo-instagram"></ion-icon></a>
                    <a href="#"><ion-icon name="logo-twitter"></ion-icon></a>
                    <a href="#"><ion-icon name="logo-google"></ion-icon></a>
                    <a href="#"><ion-icon name="logo-skype"></ion-icon></a>
                </div> -->

            </form>
        </div>
    </div>
    </div>
    </div>
    <script src="https://unpkg.com/ionicons@5.4.0/dist/ionicons.js"></script>
</body>

</html>