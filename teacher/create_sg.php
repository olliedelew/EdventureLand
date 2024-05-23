<?php

// Start session
session_start();
if(!isset($_SESSION['user']) || !isset($_SESSION['school_id']) || !isset($_SESSION['teacher_id'])){
    echo '<script>location.href = "../login.php";</script>';
} 
include '../connection.php';

if(!isset($_POST['ingHidden']) || !isset($_POST['sg_name'])){
    $pdo = null;
    header("location: groups.php");
}
if(!empty($_POST['ingHidden']) && !empty($_POST['sg_name'])){

$student_ids = $_POST['ingHidden'];
$name = $_POST['sg_name'];
$student_array = explode(',', $student_ids);
$students = count($student_array);

$sql = "INSERT INTO `student_group`(`name`, `number_of_students`) VALUES (:name, :number_of_students)";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    'name' => $name,
    'number_of_students' => $students
]);
$last_id = $pdo->lastInsertId();

$teachID = intval($_SESSION['teacher_id']);

$sql = "INSERT INTO `teacher_student_group`(`teacher_id`, `student_group_id`) VALUES (:teacher_id, :student_group_id)";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    'teacher_id' => $teachID,
    'student_group_id' => $last_id
]);

foreach ($student_array as $student) {
    $sql = "INSERT INTO `Student_student_group`(`student_group_id`, `student_id`) VALUES (:student_group_id, :student_id)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'student_group_id' => $last_id,
        'student_id' => $student
    ]);
    
    $sql = "INSERT IGNORE INTO `teacher_student`(`teacher_id`, `student_id`, `points`) VALUES (:teacher_id, :student_id, :points)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'teacher_id' => $teachID,
            'student_id' => $student,
            'points' => 0
        ]);
}
$pdo = null;
header("location: groups.php?group_ID=$last_id");

} else {
    // ERROR
    $pdo = null;
    header("location: groups.php");
}
?>
