<?php
session_start();
if(!isset($_SESSION['user']) || !isset($_SESSION['school_id']) || !isset($_SESSION['teacher_id'])){
    echo '<script>location.href = "../login.php";</script>';
}
if(!isset($_POST['rating']) || !isset($_POST['currenturl']) || !isset($_POST['submission_id']) || !isset($_POST['student_id']) || !isset($_POST['points'])){
    header("location: specific_assignment.php?" . $url);
}
include '../connection.php';

$rating = intval($_POST['rating']);
$url = $_POST['currenturl'];
if($url[-1] == '/'){
    $url = substr($url, 0, -1);
}
$submission_id = intval($_POST['submission_id']);

$sql = "UPDATE manual_submission SET result=:result WHERE manual_submission_id=:manual_submission_id";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    'result' => $rating,
    'manual_submission_id' => $submission_id
]);

$teacher_id = $_SESSION['teacher_id'];
$student_id = $_POST['student_id'];
$points_on_pass = intval($_POST['points']);
$points_on_pass = round($rating/100 * $points_on_pass);
$sql = "UPDATE `teacher_student` SET `points`= `points` + $points_on_pass WHERE `teacher_id`=$teacher_id AND `student_id`=$student_id";
$stmt = $pdo->prepare($sql);
$stmt->execute();  
$pdo = null;
header("location: specific_assignment.php?" . $url);
?>