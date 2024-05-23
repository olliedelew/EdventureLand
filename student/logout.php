<?php
    session_start();
    if(!isset($_SESSION['user']) || !isset($_SESSION['school_id']) || !isset($_SESSION['student_id'])){
      echo '<script>location.href = "../login.php";</script>';
    }

    // Destroy session
    if(session_destroy()) {
        // Redirecting To Home Page
        header("Location: ../index.php");
    }
?>
