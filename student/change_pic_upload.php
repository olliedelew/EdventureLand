<?php


  // Start session
  session_start();
  if(!isset($_SESSION['user']) || !isset($_SESSION['school_id']) || !isset($_SESSION['student_id'])){
    echo '<script>location.href = "../login.php";</script>';
  }
  include '../connection.php';
  $student_id = intval($_SESSION['student_id']);
  if(!isset($_POST['pic'])){
    $pdo = null;
    header('student_homepage.php');
  }
  $picture = $_POST['pic'];
  $sql = "UPDATE student SET profile_picture='$picture' WHERE student_id=$student_id";
  $stmt = $pdo->prepare($sql);
  $stmt->execute();  
  $pdo = null;
  header("location: student_homepage.php");
?>
