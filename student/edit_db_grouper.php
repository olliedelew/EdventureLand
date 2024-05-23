<?php
session_start();

if(!isset($_SESSION['user']) || !isset($_SESSION['school_id']) || (!isset($_SESSION['student_id']) && !isset($_SESSION['teacher_id']))){
    echo '<script>location.href = "../login.php";</script>';
}

if(!isset($_POST['body']) || !isset($_POST['title'])){
    header('db_grouper.php');
}
include '../connection.php';

if(isset($_POST['discussion_board_id'])){
    $reply_id = $_POST['discussion_board_id'];
}else {
        $reply_id = NULL;
}

$anon = 0;
if($_SESSION['isStaff'] == 'no'){
    if(isset($_POST['anon'])){
        $anon = 1;
    } else {
        $anon = 0;
    }
}
$body = $_POST['body'];
$title = $_POST['title'];
$body = nl2br($body);

$sql = "UPDATE discussion_board SET title = :title, body = :body, anonymous = $anon WHERE discussion_board_id = $reply_id";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    'body' => $body,
    'title' => $title
]);
$last_id = $pdo->lastInsertId();
    if(!isset($_POST['discussion_board_id'])){
        $_POST['discussion_board_id'] = $last_id;
    }
    $_SESSION['POST'] = $_POST;
    $pdo = null;
    header('location: db_grouper_discussion.php');
?>