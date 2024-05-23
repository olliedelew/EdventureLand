<?php
session_start();
if(!isset($_SESSION['user']) || !isset($_SESSION['school_id']) || !isset($_SESSION['teacher_id'])){
  echo '<script>location.href = "../login.php";</script>';
}
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
	<title>Settings</title></head>
<body>
<link rel="stylesheet" href="../style.css" />

<div class="container" style="text-align:left;">
<h1>Settings</h1>
    <div class="row" style="background-color:white;">
          <div class="col-sm-3">
              <br>
              <div class="card">
                  <div class="card-body">

                      <button type="button" class="button" id="activated" onclick="location.href='teacher_profile.php'">Homepage</button><br><br>
                  </div>
              </div>
          </div>
          <div class="col-sm-3">
              <br>
              <div class="card">
                  <div class="card-body">
                      <button type="button" class="button" onclick="location.href='groups.php'">Student Groups</button><br><br> <!-- Student groupmates -->
                  </div>
              </div>
          </div>
          <div class="col-sm-3">
              <br>
              <div class="card">
                  <div class="card-body">
                      <button type="button" class="button" onclick="location.href='assset.php'">Assignments Set</button><br><br>
                  </div>
              </div>
          </div>
          <div class="col-sm-3">
              <br>
              <div class="card">
                  <div class="card-body">
                      <button type="button" class="button" onclick="location.href='set_assignment.php'">Set Assignments</button><br><br>
                  </div>
              </div>
          </div>

      </div>
  <!-- Profile pic -->
  <div class="row">
  <br>
  <br>
  <br>

  <div class="col-md-4">

<div class="card">
    <div class="card-body">
  </div>
  </div>
</div>

  <div class="col-md-4">
  <div class="card">
      <div class="card-body">
      <a href="edit_details.php"><button class="button">Edit details</button></a><br><br>
      <a href="change_password.php"><button class="button">Change password</button></a><br><br>
      <a href="change_pic.php"><button class="button">Change profile picture</button></a><br><br>
      <a href="logout.php"><button class="button">Sign Out</button></a><br><br>
    </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card">
      <div class="card-body">
    </div>
    </div>
  </div>
  </div>
</div>
</body>
</html>