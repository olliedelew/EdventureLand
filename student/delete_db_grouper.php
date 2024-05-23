<?php
session_start();

if(!isset($_SESSION['user']) || !isset($_SESSION['school_id']) || (!isset($_SESSION['student_id']) && !isset($_SESSION['teacher_id']))){
    echo '<script>location.href = "../login.php";</script>';
}

include '../connection.php';

if(!isset($_POST['discussion_board_id'])){
    header('location: db_grouper.php');
}

$id = $_POST['discussion_board_id'];
$sql = "SELECT reply_id FROM discussion_board where discussion_board_id = $id";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$stmt->setFetchMode(PDO::FETCH_ASSOC);
while ($row = $stmt->fetch()) {
    if(empty($row['reply_id'])){
        $url = 'db_grouper.php';
    } else {
        $url = 'db_grouper_discussion.php';
    }
}

$sql = "DELETE FROM discussion_board where discussion_board_id = $id";
$stmt = $pdo->prepare($sql);
$stmt->execute();
header('location: ' . $url);

?>