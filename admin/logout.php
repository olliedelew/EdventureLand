<?php
    session_start();
    if (($_SESSION['isAdmin'] != 'yes') || !isset($_SESSION['user']) || !isset($_SESSION['school_id'])){ //check if user is a user and display buttons
        header('location: ../login.php');
    }

    // Destroy session
    if(session_destroy()) {
        // Redirecting To Home Page
        header("Location: ../index.php");
    } else {
        header("Location: ../index.php");
    }
?>
