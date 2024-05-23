
	<?php

	session_start();

    if(!isset($_SESSION['user']) || !isset($_SESSION['school_id']) || !isset($_SESSION['teacher_id'])){
        echo '<script>location.href = "../login.php";</script>';
    }

	if(!isset($_POST['assignment_id']) || !isset($_POST['actual_assignment_id'])){
		header('location: assset.php');
	}
    include '../connection.php';

	$aid_test = $_POST['assignment_id'];

	$sql = "DELETE FROM math_submission WHERE assignment_id = $aid_test";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();

	$sql = "DELETE FROM assignment WHERE assignment_id = $aid_test";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();

	$aid = $_POST['actual_assignment_id'];
	$sql = "DELETE FROM math_assignment WHERE math_assignment_id = $aid"; 

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $pdo = null;

    header("location: assset.php");
	?>
