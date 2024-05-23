<?php
    session_start();
    if (($_SESSION['isAdmin'] != 'yes') || !isset($_SESSION['user']) || !isset($_SESSION['school_id'])){ //check if user is a user and display buttons
        header('location: ../login.php');
    }
    // Approve the teacher by setting the approval column to 1
    if(isset($_POST['teacher_id'])){
        $query = "UPDATE teacher SET approval = 1 WHERE teacher_id = :teacher_id";
        include '../connection.php';
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            'teacher_id' => $_POST['teacher_id']
        ]);
        $pdo = null;
        header('location: teachers.php');
    } else {
        header('location: teachers.php');
    }
?>