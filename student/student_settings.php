<?php
    session_start();
    if(!isset($_SESSION['user']) || !isset($_SESSION['school_id']) || !isset($_SESSION['student_id'])){
        echo '<script>location.href = "../login.php";</script>';
    }
    ?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
	<title>Settings</title>
    <link rel="stylesheet" href="../style.css" />

</head>
<body>
<div class="container" style="text-align:left;">
	<h1>Settings</h1>
  <div class="row" style="background-color:white;">
  <div class="col-sm-3">
    <br>
  <div class="card">
      <div class="card-body">

      <button type="button" class="button" onclick="location.href='student_homepage.php'">Games</button><br><br>
    </div>
    </div>
  </div>
  <div class="col-sm-3">
    <br>
    <div class="card">
        <div class="card-body">
            <button type="button" class="button" onclick="location.href='db_grouper.php'">Discussion Boards</button><br><br>
        </div>
    </div>
  </div>
  <div class="col-sm-3">
    <br>
    <div class="card">
        <div class="card-body">
            <button type="button" class="button" onclick="location.href='student_assignments.php'">Assignments</button><br><br>
        </div>
    </div>
  </div>
  <div class="col-sm-3">
    <br>
    <div class="card">
        <div class="card-body">
            <button type="button" class="button" onclick="location.href='#'" id="activated">Account</button><br><br>
        </div>
    </div>
  </div>
    </div>
  <br>

  <div class="row">
  <div class="col-md-4">

  <div class="card">
      <div class="card-body">
    </div>
    </div>
  </div>
  <div class="col-md-4">
  <br>
    <div class="card">
      <div class="card-body">
      <a href="../teacher/edit_details.php"><button class="button">Edit details</button></a><br><br>
      <a href="../teacher/change_password.php"><button class="button">Change password</button></a><br><br>
      <a href="change_pic.php"><button class="button">Change Picture</button></a><br><br>
      <a href="student_progress.php"><button class="button">Progress</button></a><br><br>
      <a href="group_leaderboards.php"><button class="button">Leaderboards</button></a><br><br>
      <a href="achievements.php"><button class="button">Achievements</button></a><br><br>
      <a href="logout.php"><button class="button">Sign out</button></a><br><br>        
    </div>
    </div>
  </div>
  <div class="col-md-4">
  </div>
  </div>
</div>
</body>
</html>