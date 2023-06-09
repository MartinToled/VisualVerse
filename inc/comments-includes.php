<?php 
session_start();
require '../php/connect.php';
// check if the comment form has been submitted
if (isset($_POST['submit_comment'])) {
    // retrieve the comment text and the post ID from the form
    $comment_text = $_POST['comment'];
    $post_id = $_POST['post_id'];

    $created_at = date('Y-m-d H:i:s');

    // retrieve the username of the logged-in user from the session
    $user_id = $_SESSION['id'];
    $user_name = $_SESSION['username'];

    // insert the new comment into the database
    $stmt = $conn->prepare("INSERT INTO comments (post_id, user_id, user_name, comments, created_at) VALUES (:post_id, :user_id, :user_name, :comment_text, :created_at)");
    $stmt->bindParam(':post_id', $post_id);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':user_name', $user_name);
    $stmt->bindParam(':comment_text', $comment_text);
    $stmt->bindParam(':created_at', $created_at);
    $stmt->execute();
}header('Location: '.$_SERVER['HTTP_REFERER']);

exit();
?>