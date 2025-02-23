<?php
session_start();
require __DIR__.'/../config/database.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
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

// Fetch posts in the community
$sql = "SELECT * FROM posts WHERE community_id = :community_id";
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

// Fetch categories
$sql = "SELECT * FROM post_category";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$categories = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($community['community_name']); ?></title>
</head>
<body>
    <h2><?php echo htmlspecialchars($community['community_name']); ?></h2>
    <p><?php echo htmlspecialchars($community['description']); ?></p>
    
    <h3>Members</h3>
    <?php if (count($members) > 0): ?>
        <ul>
            <?php foreach ($members as $member): ?>
                <li>
                    <?php echo htmlspecialchars($member['first_name'] . ' ' . $member['last_name'] . ' (' . $member['username'] . ')'); ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No members found in this community.</p>
    <?php endif; ?>

    <h3>Create a Post</h3>
    <form action="create_post.php" method="POST">
        <input type="hidden" name="community_id" value="<?php echo htmlspecialchars($community_id); ?>">
        <label for="post_title">Post Title:</label>
        <input type="text" id="post_title" name="post_title" required><br><br>

        <label for="post_description">Post Description:</label>
        <textarea id="post_description" name="post_description" required></textarea><br><br>

        <label for="category_id">Category:</label>
        <select id="category_id" name="category_id" required>
            <?php foreach ($categories as $category): ?>
                <option value="<?php echo htmlspecialchars($category['id']); ?>"><?php echo htmlspecialchars($category['title']); ?></option>
            <?php endforeach; ?>
        </select><br><br>

        <button type="submit">Create Post</button>
    </form>

    <h3>Posts</h3>
    <?php if (count($posts) > 0): ?>
        <ul>
            <?php foreach ($posts as $post): ?>
                <li>
                    <h4><?php echo htmlspecialchars($post['post_title']); ?></h4>
                    <p><?php echo htmlspecialchars($post['post_description']); ?></p>
                    <p>Posted by User ID: <?php echo htmlspecialchars($post['user_id']); ?></p>
                    <p>Created at: <?php echo htmlspecialchars($post['created_at']); ?></p>
                    <?php if ($post['created_at'] != $post['modified_at']): ?>
                        <p>Last modified: <?php echo htmlspecialchars($post['modified_at']); ?> (<?php echo time_elapsed_string($post['modified_at']); ?> ago)</p>
                    <?php endif; ?>
                    <?php if ($post['user_id'] == $_SESSION['user_id']): ?>
                        <a href="edit_post.php?id=<?php echo htmlspecialchars($post['id']); ?>">Edit</a>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No posts found in this community.</p>
    <?php endif; ?>
    <a href="../dashboard.php"><button>Back to Dashboard</button></a>
</body>
</html>
<?php
function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}
?>