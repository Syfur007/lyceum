<?php
session_start();
require __DIR__ . '/../config/database.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit();
}

// Get the post ID and vote value from the request
$post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
$vote = isset($_POST['vote']) ? intval($_POST['vote']) : 0;
$user_id = $_SESSION['user_id'];

// Fetch the community ID for the post
$sql = "SELECT community_id FROM posts WHERE id = :post_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':post_id', $post_id);
$stmt->execute();
$post = $stmt->fetch();

if (!$post) {
    echo json_encode(['status' => 'error', 'message' => 'Post not found']);
    exit();
}

$community_id = $post['community_id'];

// Check if the user has already voted on this post
$sql = "SELECT * FROM vote_in_post WHERE user_id = :user_id AND post_id = :post_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':user_id', $user_id);
$stmt->bindParam(':post_id', $post_id);
$stmt->execute();
$existing_vote = $stmt->fetch();

if ($existing_vote) {
    // Update the existing vote
    $sql = "UPDATE vote_in_post SET vote = :vote, modified_at = NOW() WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':vote', $vote);
    $stmt->bindParam(':id', $existing_vote['id']);
} else {
    // Insert a new vote
    $sql = "INSERT INTO vote_in_post (user_id, post_id, community_id, vote, created_at, modified_at) VALUES (:user_id, :post_id, :community_id, :vote, NOW(), NOW())";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':post_id', $post_id);
    $stmt->bindParam(':community_id', $community_id);
    $stmt->bindParam(':vote', $vote);
}

if ($stmt->execute()) {
    // Calculate the new vote count
    $sql = "SELECT SUM(vote) AS votes FROM vote_in_post WHERE post_id = :post_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':post_id', $post_id);
    $stmt->execute();
    $result = $stmt->fetch();
    $votes = $result['votes'] ?? 0;

    echo json_encode(['status' => 'success', 'votes' => $votes]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to update vote']);
}
?>