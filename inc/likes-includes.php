<?php
include '../php/connect.php';
session_start();

if (isset($_POST['liked'])) {
    $user_id = $_SESSION['id'];
    $post_id = $_POST['postid'];

    // Check if the user already liked the post
    $liked = $conn->query("SELECT * FROM likes WHERE liked_user = $user_id AND post_like = $post_id");
    if ($liked->rowCount() == 0) {
        $conn->query("INSERT INTO likes (liked_user, post_like) VALUES ($user_id, $post_id)");

        $result = $conn->query("SELECT * FROM uploads WHERE uploads_id = $post_id");
        $row = $result->fetch();
        $n = $row['likes_count'];
        $conn->query("UPDATE uploads SET likes_count = $n + 1 WHERE uploads_id = $post_id");

        echo json_encode(['status' => 'success', 'action' => 'liked', 'likes_count' => $n + 1]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Already liked this post']);
    }

    exit();
} elseif (isset($_POST['unliked'])) {
    $user_id = $_SESSION['id'];
    $post_id = $_POST['postid'];

    // Check if the user already liked the post
    $liked = $conn->query("SELECT * FROM likes WHERE liked_user = $user_id AND post_like = $post_id");
    if ($liked->rowCount() > 0) {
        $conn->query("DELETE FROM likes WHERE post_like = $post_id AND liked_user = $user_id");

        $result = $conn->query("SELECT * FROM uploads WHERE uploads_id = $post_id");
        $row = $result->fetch();
        $n = $row['likes_count'];
        $conn->query("UPDATE uploads SET likes_count = $n - 1 WHERE uploads_id = $post_id");

        echo json_encode(['status' => 'success', 'action' => 'unliked', 'likes_count' => $n - 1]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'You have not liked this post']);
    }

    exit();
}
?>
