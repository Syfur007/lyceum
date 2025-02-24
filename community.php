<?php
session_start();
require __DIR__ . '/config/database.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit();
}

// Get the community ID from the URL
$community_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch community details with university, institute, and course names
$sql = "SELECT c.*, u.name AS university_name, i.name AS institute_name, co.course_title AS course_name 
        FROM community c
        JOIN university u ON c.university_id = u.id
        JOIN institute i ON c.institute_id = i.id
        JOIN courses co ON c.course_id = co.id AND c.institute_id = co.institute_id
        WHERE c.id = :community_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':community_id', $community_id);
$stmt->execute();
$community = $stmt->fetch();

if (!$community) {
    echo "<p>Community not found.</p>";
    exit();
}

// Fetch posts in the community
$sql = "SELECT p.*, 
               (SELECT SUM(vote) FROM vote_in_post WHERE post_id = p.id) AS votes,
               u.username
        FROM posts p 
        JOIN users u ON p.user_id = u.id
        WHERE p.community_id = :community_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':community_id', $community_id);
$stmt->execute();
$posts = $stmt->fetchAll();

// Fetch members in the community
$sql = "SELECT u.id, u.username, u.first_name, u.last_name FROM users u
        JOIN user_in_community uic ON u.id = uic.user_id
        WHERE uic.community_id = :community_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':community_id', $community_id);
$stmt->execute();
$members = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($community['community_name']); ?></title>
    <link rel="stylesheet" href="css/community.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
</head>

<body>
    <div class="topnav">
        <a href="index.php">Home</a>
        <a href="community.php">Community</a>
        <a href="profile.php">Profile</a>
        <a href="auth/logout.php" style="float:right">Logout</a>
    </div>

    <div class="hero">
        <h1><?php echo htmlspecialchars($community['course_name']); ?></h1>
    </div>

    <div class="main">
        <div class="main-top">
            <?php foreach ($posts as $post): ?>
                <article class="post">
                    <div class="post-vote">
                        <button class="btn" onclick="vote(<?php echo htmlspecialchars($post['id']); ?>, 1)"><img class="vote upvote" src="img/arrow.png"></img></button>
                        <span id="votes-<?php echo htmlspecialchars($post['id']); ?>"><?php echo htmlspecialchars($post['votes'] ?? 0); ?></span>
                        <button class="btn" onclick="vote(<?php echo htmlspecialchars($post['id']); ?>, -1)"><img class="vote downvote" src="img/arrow.png"></img></button>
                    </div>
                    <div class="post-content">
                        <a href="post.php?id=<?php echo htmlspecialchars($post['id']); ?>">
                            <h2><?php echo htmlspecialchars($post['post_title']); ?></h2>
                            <p><?php echo htmlspecialchars($post['post_description']); ?></p>
                        </a>
                        <div class="post-footer">
                            <span>by <a href="profile.php?id=<?php echo htmlspecialchars($post['user_id']); ?>"><?php echo htmlspecialchars($post['username']); ?></a></span>
                            <span class="date">at <?php echo htmlspecialchars($post['created_at']); ?></span>
                            <?php if ($post['user_id'] == $_SESSION['user_id']): ?>
                                <a href="community/edit_post.php?id=<?php echo htmlspecialchars($post['id']); ?>">Edit</a>
                                <a href="community/delete_post.php?id=<?php echo htmlspecialchars($post['id']); ?>" onclick="return confirm('Are you sure you want to delete this post?');">Delete</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
        <div class="main-right">
            <h3>Members</h3>
            <?php if (count($members) > 0): ?>
                <div class="members">
                    <?php foreach ($members as $member): ?>
                        <div>
                            <?php echo htmlspecialchars($member['first_name'] . ' ' . $member['last_name'] . ' (' . $member['username'] . ')'); ?>
                    </div>
                    <?php endforeach; ?>
                    </div>
            <?php else: ?>
                <p>No members found in this community.</p>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function vote(postId, vote) {
            const formData = new FormData();
            formData.append('post_id', postId);
            formData.append('vote', vote);

            fetch('community/vote_post.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        document.getElementById('votes-' + postId).innerText = data.votes;
                    } else {
                        console.error(data.message);
                    }
                })
                .catch(error => console.error('Error:', error));
        }
    </script>
</body>

</html>