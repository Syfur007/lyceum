<?php
session_start();
require __DIR__.'/../config/database.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

// Get the post ID from the URL
$post_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch post details
$sql = "SELECT p.*, 
               (SELECT SUM(vote) FROM vote_in_post WHERE post_id = p.id) AS votes 
        FROM posts p 
        WHERE p.id = :post_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':post_id', $post_id);
$stmt->execute();
$post = $stmt->fetch();

if (!$post) {
    echo "<p>Post not found.</p>";
    exit();
}

// Fetch comments for the post
$sql = "SELECT * FROM comment WHERE post_id = :post_id ORDER BY created_at ASC";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':post_id', $post_id);
$stmt->execute();
$comments = $stmt->fetchAll();

// Function to display comments recursively
function display_comments($comments, $parent_id = null, $level = 0) {
    $html = '';
    foreach ($comments as $comment) {
        if ($comment['parent_id'] == $parent_id) {
            $html .= '<div style="margin-left: ' . ($level * 20) . 'px;">';
            $html .= '<p>' . htmlspecialchars($comment['comment']) . ' - <small>Posted by User ID: ' . htmlspecialchars($comment['user_id']) . '</small></p>';
            $html .= '<form action="create_comment.php" method="POST">';
            $html .= '<input type="hidden" name="post_id" value="' . htmlspecialchars($comment['post_id']) . '">';
            $html .= '<input type="hidden" name="community_id" value="' . htmlspecialchars($comment['community_id']) . '">';
            $html .= '<input type="hidden" name="parent_id" value="' . htmlspecialchars($comment['id']) . '">';
            $html .= '<label for="comment">Reply:</label>';
            $html .= '<textarea id="comment" name="comment" required></textarea><br><br>';
            $html .= '<button type="submit">Reply</button>';
            $html .= '</form>';
            $html .= display_comments($comments, $comment['id'], $level + 1);
            $html .= '</div>';
        }
    }
    return $html;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($post['post_title']); ?></title>
    <script>
        function vote(postId, vote) {
            const formData = new FormData();
            formData.append('post_id', postId);
            formData.append('vote', vote);

            fetch('vote_post.php', {
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
</head>
<body>
    <h2><?php echo htmlspecialchars($post['post_title']); ?></h2>
    <p><?php echo htmlspecialchars($post['post_description']); ?></p>
    <p>Posted by User ID: <?php echo htmlspecialchars($post['user_id']); ?></p>
    <p>Created at: <?php echo htmlspecialchars($post['created_at']); ?></p>
    <?php if ($post['created_at'] != $post['modified_at']): ?>
        <p>Last modified: <?php echo htmlspecialchars($post['modified_at']); ?> (<?php echo time_elapsed_string($post['modified_at']); ?> ago)</p>
    <?php endif; ?>
    <p>Votes: <span id="votes-<?php echo htmlspecialchars($post['id']); ?>"><?php echo htmlspecialchars($post['votes'] ?? 0); ?></span></p>
    <button onclick="vote(<?php echo htmlspecialchars($post['id']); ?>, 1)">Upvote</button>
    <button onclick="vote(<?php echo htmlspecialchars($post['id']); ?>, -1)">Downvote</button>

    <h3>Comments</h3>
    <?php echo display_comments($comments); ?>

    <h3>Add a Comment</h3>
    <form action="create_comment.php" method="POST">
        <input type="hidden" name="post_id" value="<?php echo htmlspecialchars($post_id); ?>">
        <input type="hidden" name="community_id" value="<?php echo htmlspecialchars($post['community_id']); ?>">
        <input type="hidden" name="parent_id" value="">
        <label for="comment">Comment:</label>
        <textarea id="comment" name="comment" required></textarea><br><br>
        <button type="submit">Add Comment</button>
    </form>
    <a href="community.php?id=<?php echo htmlspecialchars($post['community_id']); ?>"><button>Back to Community</button></a>
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