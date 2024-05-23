<?php
session_start();
if(!isset($_SESSION['user']) || !isset($_SESSION['school_id']) || (!isset($_SESSION['student_id']) && !isset($_SESSION['teacher_id']))){
    echo '<script>location.href = "../login.php";</script>';
}
include '../connection.php';

?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
	<title>Change Password</title>
	<style type="text/css">
    .login-button{
  background-color: green;
  color: white;
  width: 25%;
  padding: 7px 10px;
  border: 5px solid black;
  cursor: pointer;
  font-size: 20px;

}
    .texty {
  width: 30%;
  padding: 12px 20px;
  margin: 0px 0;
  display: inline-block;
  border: 1px solid black;
  box-sizing: border-box;
}

	</style>
</head>
<body>
<link rel="stylesheet" href="../style.css" />

<div class="container" style="text-align:center;">
<?php 
          if ($_SERVER["REQUEST_METHOD"] == "POST"){
            $pswdErr = $newpswdErr = $newcpswdErr = "";
            $oldpassword = $_POST['oldpassword'];
            $password = $_POST['password'];
            $cpassword = $_POST['confirmpassword'];
            if ($password != $cpassword) {
              $newpswdErr = "<br>Passwords not the same";      
              $newcpswdErr = "<br>Passwords not the same";      
            }
            if(strlen($password) < 6){
              $newpswdErr = "<br>Password is too short";
              $newcpswdErr = "<br>Password is too short";
            }
            if(strlen($password) > 20){
              $newpswdErr = "<br>Password is too long";
              $newcpswdErr = "<br>Password is too long";
            }

            if($_SESSION['isStaff'] == 'yes'){
                $tid = $_SESSION['teacher_id'];
                $sql = "SELECT * FROM teacher WHERE teacher_id = :teacher_id";
                $stmt = $pdo->prepare($sql);
        
                $stmt->execute([
                'teacher_id' => $tid
                ]);
                $stmt->setFetchMode(PDO::FETCH_ASSOC);
                while($row = $stmt->fetch()){
                    $hash = $row['password'];
                }
            } else {
                $sid = $_SESSION['student_id'];
                $sql = "SELECT * FROM student WHERE student_id = :student_id";
                $stmt = $pdo->prepare($sql);
        
                $stmt->execute([
                'student_id' => $sid
                ]);
                $stmt->setFetchMode(PDO::FETCH_ASSOC);
                while($row = $stmt->fetch()){
                    $hash = $row['password'];
                }
            }
            if (password_verify($oldpassword, $hash)) {
                $pswdErr = '';
            } else {
                $pswdErr = '<br>Incorrect password';
            }

            if($newcpswdErr == '' &&  $pswdErr == '' &&  $newpswdErr == ''){
                $pswdUpload = password_hash($password, PASSWORD_DEFAULT);
                if($_SESSION['isStaff'] == 'yes'){
                    $sql = "UPDATE `teacher` SET `password`='$pswdUpload' WHERE `teacher_id`=$tid";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute();  
                    $pdo = null;
                    header("location: teacher_profile.php");
                } else {
                    $sql = "UPDATE `student` SET `password`='$pswdUpload' WHERE `student_id`=$sid";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute();  
                    $pdo = null;
                    header("location: ../student/student_homepage.php");
                }
            }
        }
    
    ?>

<h1 style="text-align:left;">Change Password</h1>
<?php
if($_SESSION['isStaff'] == 'yes'){
?>
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
      <?php
} else if ($_SESSION['isStaff'] == 'no'){
      ?>
        <div class="row" style="background-color: white;">
            <div class="col-sm-3">
                <br>
                <div class="card">
                    <div class="card-body">

                        <button type="button" class="button" onclick="location.href='../student/student_homepage.php'">Games</button><br><br>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <br>
                <div class="card">
                    <div class="card-body">
                        <button type="button" class="button" onclick="location.href='../student/db_grouper.php'">Discussion Boards</button><br><br> <!-- Student groupmates -->
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <br>
                <div class="card">
                    <div class="card-body">
                        <button type="button" class="button" onclick="location.href='../student/student_assignments.php'">Assignments</button><br><br>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <br>
                <div class="card">
                    <div class="card-body">
                        <button type="button" class="button" id="activated" onclick="location.href='../student/student_settings.php'">Account</button><br><br>
                    </div>
                </div>
                </div>
                </div>

      <?php
} else {
    $pdo = null;
    header("location: ../login.php");  
}
      ?>

    <form name = "myForm" id = "myForm" method = "post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <br>
    <br>
            <p><b>Current Password</b></p><input type="password" class="texty" name="oldpassword" placeholder="Old Password" required>
            <span class="error">* <?php echo $pswdErr;?></span>
            <br>
            <br>
            <p><b>New Password</b></p><input type="password" class="texty" name="password" placeholder="Password" required>
            <span class="error">* <?php echo $newpswdErr;?></span>
            <br>
            <br>
            <p><b>Confirm New Password</b></p><input type="password" class="texty" name="confirmpassword" placeholder="Confirm Password" required>
            <span class="error">* <?php echo $newcpswdErr;?></span>
            <div>
                <br>
            <input type="submit" name="submit" value="Update Profile" class="login-button">
            <br><br>
    </form>

</body>
</html>