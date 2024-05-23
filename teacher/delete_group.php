	<?php

	session_start();
    if(!isset($_SESSION['user']) || !isset($_SESSION['school_id']) || !isset($_SESSION['teacher_id'])){
        echo '<script>location.href = "../login.php";</script>';
    } 
    if(!isset($_POST['group_id'])){
        header('location: groups.php');
    }
    include '../connection.php';


    $group_id = $_POST['group_id'];
    if(strpos($groupID, "'") !== false){
        // There is a comma in groupID then
        $explodedId = explode("'", $groupID);
        $groupID = $explodedId[0];
    }
    // Only do this if there are no students left to delete 
    // Or give a warning that doing this will delete all students from group
    $sql = "DELETE FROM Student_student_group WHERE student_group_id = $group_id";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $sql = "DELETE FROM teacher_student_group WHERE student_group_id = $group_id";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $sql = "SELECT * FROM assignment INNER JOIN math_assignment ON math_assignment.math_assignment_id = assignment.math_id WHERE math_assignment.student_group_id = $group_id";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $assignment_ids = array();
	while($row = $stmt->fetch()){
        array_push($assignment_ids, $row['assignment_id']);
	}

    foreach ($assignment_ids as $ids) {
        $sql = "DELETE FROM math_submission WHERE assignment_id = $ids";

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
        $sql = "DELETE FROM manual_submission WHERE assignment_id = $ids";

        $stmt = $pdo->prepare($sql);
        $stmt->execute();
    }


    $sql = "SELECT * FROM assignment INNER JOIN quiz_assignment ON quiz_assignment.quiz_assignment_id = assignment.quiz_id WHERE quiz_assignment.student_group_id = $group_id";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $assignment_ids = array();
	while($row = $stmt->fetch()){
        array_push($assignment_ids, $row['assignment_id']);
	}

    foreach ($assignment_ids as $ids) {
        $sql = "DELETE FROM quiz_submission WHERE assignment_id = $ids";

        $stmt = $pdo->prepare($sql);
        $stmt->execute();
    }

    $sql = "DELETE FROM assignment WHERE student_group_id = $group_id";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();


    $sql = "DELETE FROM math_assignment WHERE student_group_id = $group_id";

    $stmt = $pdo->prepare($sql);
    if($stmt->execute() === TRUE){
        echo 'success';
    } else {
        echo 'faile';
    }

    $sql = "DELETE FROM manual_assignment WHERE student_group_id = $group_id";

    $stmt = $pdo->prepare($sql);
    if($stmt->execute() === TRUE){
        echo 'successssss';
    } else {
        echo 'faileeee';
    }

    $sql = "SELECT * FROM quiz_assignment WHERE student_group_id = $group_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $quizzes = array();
    while($row = $stmt->fetch()){
        // $quizID = $row['quizID'];
        array_push($quizzes, $row['quiz_assignment_id']);
    }
    
    
    foreach ($quizzes as $ids) {
        $sql = "DELETE FROM questions_and_answers WHERE quiz_assignment_id = $ids";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
    }
    
    $sql = "DELETE FROM quiz_assignment WHERE student_group_id = $group_id";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    echo 'here';
    
    
    $sql = "DELETE FROM discussion_board WHERE student_group_id = $group_id";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    
    
    $sql = "DELETE FROM student_group WHERE student_group_id = $group_id";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $pdo = null;
    header('location: groups.php');
	?>