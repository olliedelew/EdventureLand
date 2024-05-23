
<?php
	session_start();
    if(!isset($_SESSION['user']) || !isset($_SESSION['school_id']) || !isset($_SESSION['teacher_id'])){
        echo '<script>location.href = "../login.php";</script>';
    } 
	if(!isset($_POST['assignment_id']) || !isset($_POST['actual_assignment_id'])){
		header('location: assset.php');
	}
    include '../connection.php';

    $manual_assignment_id = $_POST['actual_assignment_id'];
    $assignment_id = $_POST['assignment_id'];

    $sql = "DELETE FROM manual_submission WHERE assignment_id = $assignment_id";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $sql = "DELETE FROM assignment WHERE manual_id = $manual_assignment_id";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();


    $sql = "DELETE FROM manual_assignment WHERE manual_assignment_id = $manual_assignment_id";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $pdo = null;
    header('location: assset.php');

?>
