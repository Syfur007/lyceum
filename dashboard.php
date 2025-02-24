<?php
session_start();
require 'config/database.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

// Fetch communities the user is part of with university, institute, and course details
$sql = "SELECT c.*, u.name AS university_name, i.name AS institute_name, co.course_title, co.course_code 
        FROM community c
        JOIN user_in_community uic ON c.id = uic.community_id
        JOIN university u ON c.university_id = u.id
        JOIN institute i ON c.institute_id = i.id
        JOIN courses co ON c.course_id = co.id AND c.institute_id = co.institute_id
        WHERE uic.user_id = :user_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':user_id', $_SESSION['user_id']);
$stmt->execute();
$communities = $stmt->fetchAll();
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Dashboard | By Code Info</title>
    <link rel="stylesheet" href="css/dashboard.css" />
    <!-- Font Awesome Cdn Link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
</head>

<body>
    <div class="container">
        <nav>
            <ul>
                <li><a href="index.php" class="logo">
                        <img src="/logo.jpg" alt="">
                        <span class="nav-item">DashBoard</span>
                    </a></li>
                <li><a href="index.php">
                        <i class="fas fa-home"></i>
                        <span class="nav-item">Home</span>
                    </a></li>
                <li><a href="">
                        <i class="fas fa-user"></i>
                        <span class="nav-item">Profile</span>
                    </a></li>
                <li><a href="">
                        <i class="fas fa-wallet"></i>
                        <span class="nav-item">Wallet</span>
                    </a></li>
                <li><a href="">
                        <i class="fas fa-chart-bar"></i>
                        <span class="nav-item">Analytics</span>
                    </a></li>
                <li><a href="">
                        <i class="fas fa-tasks"></i>
                        <span class="nav-item">Tasks</span>
                    </a></li>
                <li><a href="">
                        <i class="fas fa-cog"></i>
                        <span class="nav-item">Settings</span>
                    </a></li>
                <li><a href="">
                        <i class="fas fa-question-circle"></i>
                        <span class="nav-item">Help</span>
                    </a></li>
                <li><a href="auth/logout.php" class="logout">
                        <i class="fas fa-sign-out-alt"></i>
                        <span class="nav-item">Log out</span>
                    </a></li>
            </ul>
        </nav>

        <section class="main">
            <!-- <div class="main-top">
                <h1>Skills</h1>
                <i class="fas fa-user-cog"></i>
            </div>
            <div class="main-skills">
                <div class="card">
                    <i class="fas fa-laptop-code"></i>
                    <h3>Web development</h3>
                    <p>Join Over 1 million Students.</p>
                    <button>Get Started</button>
                </div>
                <div class="card">
                    <i class="fab fa-wordpress"></i>
                    <h3>WordPress</h3>
                    <p>Join Over 3 million Students.</p>
                    <button>Get Started</button>
                </div>
                <div class="card">
                    <i class="fas fa-palette"></i>
                    <h3>Graphic design</h3>
                    <p>Join Over 2 million Students.</p>
                    <button>Get Started</button>
                </div>
                <div class="card">
                    <i class="fab fa-app-store-ios"></i>
                    <h3>iOS development</h3>
                    <p>Join Over 1 million Students.</p>
                    <button>Get Started</button>
                </div>
            </div> -->

            <section class="main-course">
                <h1>My courses</h1>
                <div class="course-box">
                    <ul>
                        <li class="active">In progress</li>
                        <li>Explore</li>
                        <li>Incoming</li>
                        <li>Finished</li>
                        <li>
                            <?php if (in_array($_SESSION['role'], ['teacher', 'admin'])): ?>
                                <a href="create_community.php"><button>Create Community</button></a>
                            <?php endif; ?>
                        </li>
                    </ul>
                    <div class="course">
                        <?php foreach ($communities as $community): ?>
                            <div class="box">
                                <a href="community/community.php?id=<?php echo htmlspecialchars($community['id']); ?>">
                                    <h3><?php echo htmlspecialchars($community['community_name']); ?></h3>
                                    <p><?php echo htmlspecialchars($community['course_title']); ?> (<?php echo htmlspecialchars($community['course_code']); ?>)</p>
                                    <p><?php echo htmlspecialchars($community['institute_name']); ?></p>
                                    <p><?php echo htmlspecialchars($community['university_name']); ?></p>
                                    <p>Batch: <?php echo htmlspecialchars($community['batch_id']); ?></p>
                                    <?php if ($_SESSION['role'] === 'teacher'): ?>
                                </a>
                                        <a href="edit_community.php?id=<?php echo htmlspecialchars($community['id']); ?>"><button>Edit</button></a>
                                        <a href="delete_community.php?id=<?php echo htmlspecialchars($community['id']); ?>" onclick="return confirm('Are you sure you want to delete this community?');"><button>Delete</button></a>
                                    <?php endif; ?>

                            </div>
                        <?php endforeach; ?>
                        <div class="box">
                            <h3>CSS</h3>
                            <p>50% - progress</p>
                            <button>Continue</button>
                            <i class="fab fa-css3-alt css"></i>
                        </div>
                        <!-- <div class="box">
                            <h3>JavaScript</h3>
                            <p>30% - progress</p>
                            <button>Continue</button>
                            <i class="fab fa-js-square js"></i>
                        </div> -->
                    </div>
                </div>
            </section>
        </section>
    </div>
</body>

</html>