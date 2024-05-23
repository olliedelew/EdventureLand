<?php

// Start session
session_start();
if(!isset($_SESSION['user']) || !isset($_SESSION['school_id']) || !isset($_SESSION['teacher_id'])){
  echo '<script>location.href = "../login.php";</script>';
}
include '../connection.php';

$teacher_id = intval($_SESSION['teacher_id']);
if(!isset($_POST['pic'])){
  $pdo = null;
  header('student_homepage.php');
}
$picture = $_POST['pic'];
$sql = "UPDATE teacher SET profile_picture='$picture' WHERE teacher_id=$teacher_id";
$stmt = $pdo->prepare($sql);
$stmt->execute();  
$pdo = null;
header("location: teacher_profile.php");
?>
