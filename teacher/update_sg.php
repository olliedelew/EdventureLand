<?php


// Start session
session_start();
if(!isset($_SESSION['user']) || !isset($_SESSION['school_id']) || !isset($_SESSION['teacher_id'])){
  echo '<script>location.href = "../login.php";</script>';
}

if(!isset($_POST['ingHidden']) || !isset($_POST['sg_name']) || !isset($_POST['group_id'])){
    header('groups.php');
}
include '../connection.php';
$student_ids = $_POST['ingHidden'];
$name = $_POST['sg_name'];
$student_array = array_map('intval', explode(',', $student_ids));
$students = count($student_array);
$group_id = intval($_POST['group_id']);
$teachID = intval($_SESSION['teacher_id']);

// Add something to fix the group name and also to fix if when editing group you remove all group members and then click create save edit it creates the group with no users!!! big no no
$sql = "UPDATE student_group SET name='$name', number_of_students=$students WHERE student_group_id=$group_id";
$stmt = $pdo->prepare($sql);
$stmt->execute();

foreach ($student_array as $student) {
    echo ' groupid: ';
    echo $group_id;
    echo ' student: ';
    echo $student;
    $sql =  "INSERT IGNORE INTO Student_student_group (student_group_id, student_id) VALUES ($group_id, $student)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $sql =  "INSERT IGNORE INTO teacher_student (teacher_id, student_id, points) VALUES ($teachID, $student, 0)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

}

$sql = "SELECT student_id FROM Student_student_group WHERE student_group_id = :group_id"; //WHERE teacher_id = $_SESSION[isStaff] then get student_id where it is the same as group id
$stmt = $pdo->prepare($sql);
$stmt->execute([
    'group_id' => $group_id
]);  

$stmt->setFetchMode(PDO::FETCH_ASSOC);
$ids = [];
while($row = $stmt->fetch()){
    array_push($ids, $row['student_id']);
}

$del_id = array_diff($ids, $student_array);
foreach ($del_id as $id) {
    $sql = "SELECT * FROM assignment INNER JOIN quiz_assignment ON quiz_assignment.quiz_assignment_id = assignment.quiz_id WHERE quiz_assignment.student_group_id = $group_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $assignment_ids = array();
	while($row = $stmt->fetch()){
        array_push($assignment_ids, $row['assignment_id']);
	}

    foreach ($assignment_ids as $ids) {
        $sql = "DELETE FROM quiz_submission WHERE assignment_id = $ids AND student_id = $id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
    }

    $sql = "SELECT * FROM assignment INNER JOIN manual_assignment ON manual_assignment.manual_assignment_id = assignment.manual_id WHERE manual_assignment.student_group_id = $group_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $assignment_ids = array();
	while($row = $stmt->fetch()){
        array_push($assignment_ids, $row['assignment_id']);
	}

    foreach ($assignment_ids as $ids) {
        $sql = "DELETE FROM manual_submission WHERE assignment_id = $ids AND student_id = $id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
    }

    $sql = "SELECT * FROM assignment INNER JOIN math_assignment ON math_assignment.math_assignment_id = assignment.math_id WHERE math_assignment.student_group_id = $group_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $assignment_ids = array();
	while($row = $stmt->fetch()){
        array_push($assignment_ids, $row['assignment_id']);
	}

    foreach ($assignment_ids as $ids) {
        $sql = "DELETE FROM math_submission WHERE assignment_id = $ids AND student_id = $id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
    }

    $sql = "DELETE FROM discussion_board WHERE student_id = $id AND student_group_id = $group_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

	$sql = "DELETE FROM Student_student_group WHERE student_id = $id AND student_group_id = $group_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();


}                   
$pdo = null;
$url = 'location: groups.php?group_ID=' . $group_id;
header($url);
?>
