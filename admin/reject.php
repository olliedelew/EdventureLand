<?php
    session_start();
    if (($_SESSION['isAdmin'] != 'yes') || !isset($_SESSION['user']) || !isset($_SESSION['school_id'])){ //check if user is a user and display buttons
        header('location: ../login.php');
    }
    // Unapprove the teacher by setting the approval column to 0 stopping them from logging in
    if(isset($_POST['teacher_id'])){
        include '../connection.php';
        $query = "UPDATE teacher SET approval = 0 WHERE teacher_id = :teacher_id";

        $stmt2 = $pdo->prepare($query);
        $stmt2->execute([
            'teacher_id' => $_POST['teacher_id']
        ]);
        $pdo = null;
        header('location: teachers.php');
    } else {
        header('location: teachers.php');
    }
?>