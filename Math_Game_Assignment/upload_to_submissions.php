<?php
session_start();
if(!isset($_SESSION['user']) || !isset($_SESSION['school_id']) || !isset($_SESSION['student_id'])){
    echo '<script>location.href = "../login.php";</script>';
}
if(!isset($_POST['score_hidden']) || !isset($_POST['passed_hidden']) || !isset($_POST['points_hidden']) || !isset($_POST['incorrect_ones']) || !isset($_POST['duration_hidden']) || !isset($_POST['correct_ones']) || !isset($_POST['assignment_id']) || !isset($_POST['points_on_pass']) || !isset($_POST['teacher_id']) || !isset($_POST['student_group_id'])){
    header('location: ../student/student_assignments.php');
}
include '../connection.php';
$student_id = intval($_SESSION['student_id']);
$percentage_score = intval($_POST['score_hidden']);
$passed = $_POST['passed_hidden'];
$points = intval($_POST['points_hidden']);
$incorrect = intval($_POST['incorrect_ones']);
$duration = intval($_POST['duration_hidden']);
$correct = intval($_POST['correct_ones']);
$assignment_id = intval($_POST['assignment_id']);
$points_on_pass = intval($_POST['points_on_pass']);
$teacher_id = intval($_POST['teacher_id']);
$student_group_id = $_POST['student_group_id'];
$QPM = round(($correct + $incorrect) / $duration);

if ($passed == 'true'){
    $passed = 1;
} else {
    $passed = 0;
}
$points_on_pass = round($points_on_pass * $percentage_score/100);
if ($passed == 1){
$sql = "UPDATE `math_submission` SET `assignment_done`= 1,`result`=$percentage_score,`attempts`= `attempts` + 1, `questions_right`=$correct,`questions_wrong`=$incorrect,`QPM`=$QPM, `points`=$points WHERE `student_id` = $student_id AND `assignment_id` = $assignment_id";
$sql2 = "UPDATE `teacher_student` SET `points`= `points` + $points_on_pass WHERE `teacher_id`=$teacher_id AND `student_id`=$student_id";
$stmt = $pdo->prepare($sql);
$stmt->execute();  

$stmt = $pdo->prepare($sql2);
$stmt->execute();
$pdo = null;
header("location: ../student/student_assignments.php?group_ID=" . $student_group_id);

// Continue to local leaderboard for this assignment? or back to homepage?
} else {
$sql = "UPDATE `math_submission` SET `assignment_done`= 0,`result`=$percentage_score,`attempts`= `attempts` + 1, `questions_right`=$correct,`questions_wrong`=$incorrect,`QPM`=$QPM, `points`=$points WHERE `student_id` = $student_id AND `assignment_id` = $assignment_id";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$pdo = null;
header("location: ../student/student_assignments.php?group_ID=" . $student_group_id);
}
?>