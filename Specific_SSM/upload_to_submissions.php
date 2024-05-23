<?php
session_start();
if(!isset($_SESSION['user']) || !isset($_SESSION['school_id']) || !isset($_SESSION['student_id'])){
    echo '<script>location.href = "../login.php";</script>';
}
if(!isset($_SESSION['student_id']) || !isset($_POST['lifelines_used']) || !isset($_POST['questions_right']) || !isset($_POST['assignmentid']) || !isset($_POST['total_questions']) || !isset($_POST['points_on_completion']) || !isset($_POST['teacher_id'])){
    header('location: ../student/student_assignments.php');
}
include '../connection.php';

$student_id = intval($_SESSION['student_id']);
$lifelines_used = $_POST['lifelines_used'];
$questions_right = $_POST['questions_right'];
$assignment_id = intval($_POST['assignmentid']);
$questions_total = intval($_POST['total_questions']);
$tot_res = round($questions_right/$questions_total*100);
if($tot_res >= intval($_POST['hiddenpasspercentage'])){
    $sql = "UPDATE `quiz_submission` SET `assignment_done`= 1,`result`= $tot_res,`lifelines_used`= '$lifelines_used',`attempts`= `attempts` + 1,`questions_right`=$questions_right,`questions_total`=$questions_total WHERE `student_id` = $student_id AND `assignment_id` = $assignment_id";
} else {
    $sql = "UPDATE `quiz_submission` SET `assignment_done`= 0,`result`= $tot_res,`lifelines_used`= '$lifelines_used',`attempts`= `attempts` + 1,`questions_right`=$questions_right,`questions_total`=$questions_total WHERE `student_id` = $student_id AND `assignment_id` = $assignment_id";
}

$stmt = $pdo->prepare($sql);
$stmt->execute();  

$points_on_pass = intval($_POST['points_on_completion']);
$teacher_id = intval($_POST['teacher_id']);
if($tot_res >= intval($_POST['hiddenpasspercentage'])){
    $points_on_pass = round($points_on_pass * $questions_right/$questions_total);
    $sql = "UPDATE `teacher_student` SET `points`= `points` + $points_on_pass WHERE `teacher_id`=$teacher_id AND `student_id`=$student_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
}
$pdo = null;
header("location: ../student/student_assignments.php");
?>