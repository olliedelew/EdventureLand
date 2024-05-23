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
	<title>Edit Details</title>
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



        // When form submitted, insert values into the database.
          if ($_SERVER["REQUEST_METHOD"] == "POST"){
            $fnErr = $lnErr = $usrErr = $emailErr = "";
            $username = stripslashes($_POST['username']);
            //escapes special characters in a string
            $email    = stripslashes($_POST['email']);
            $firstName = $_POST['fn'];
            $lastName = $_POST['ln'];

            if (strpos($username, ' ') !== false) {
              $usrErr = '<br>Username should not contain spaces';
          } else {
              if (strlen($username) < 6) {
                  $usrErr = "<br>Username too short";
              } elseif (strlen($username) > 14) {
                  $usrErr = "<br>Username too long";
              }
          }
          if (strpos($firstName, ' ') !== false) {
            $fnErr = '<br>First name should not contain spaces';
            } else {
              if (strlen($firstName) < 3) {
                $fnErr = "<br>First name is too short";
              } elseif (strlen($firstName) > 30) {
                $fnErr = "<br>First name is too long";
              }
            }
            if (strpos($lastName, ' ') !== false) {
              $lnErr = '<br>First name should not contain spaces';
              } else {
                if (strlen($lastName) < 3) {
                  $lnErr = "<br>Last name is too short";
                } elseif (strlen($lastName) > 30) {
                  $lnErr = "<br>Last name is too long";
                }
              }
            if($_SESSION['isStaff'] == 'no'){
                $sql = "SELECT * FROM student WHERE username = :username AND student_id != :student_id";
                $stmt = $pdo->prepare($sql);
                $sid = $_SESSION['student_id'];
                $stmt->execute([
                    'username' => $username,
                    'student_id' => $sid
                  ]);      
            } else {
                $sql = "SELECT * FROM student WHERE username = :username";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    'username' => $username
                  ]);
            }

            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $counter = 0;
            while($row = $stmt->fetch()){
              $counter += 1;
            }
            if($_SESSION['isStaff'] == 'yes'){
                $sql = "SELECT * FROM teacher WHERE username = :username AND teacher_id != :teacher_id";
                $stmt = $pdo->prepare($sql);
                $tid = $_SESSION['teacher_id'];
                $stmt->execute([
                    'username' => $username,
                    'teacher_id' => $tid
                  ]);      
            } else {
                $sql = "SELECT * FROM teacher WHERE username = :username";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    'username' => $username
                  ]);
    
            }
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            while($row = $stmt->fetch()){
              $counter += 1;
            }
        
            if ($counter > 0) {
              $usrErr = "<br>Username already taken";
            }
            
            if($_SESSION['isStaff'] == 'no'){
              $sql = "SELECT * FROM student WHERE email = :email AND student_id != :student_id";
              $stmt = $pdo->prepare($sql);
              $sid = $_SESSION['student_id'];
              $stmt->execute([
                  'email' => $email,
                  'student_id' => $sid
                ]);      
          } else {
              $sql = "SELECT * FROM student WHERE email = :email";
              $stmt = $pdo->prepare($sql);
              $stmt->execute([
                  'email' => $email
                ]);
          }

          $stmt->setFetchMode(PDO::FETCH_ASSOC);
          $counter = 0;
          while($row = $stmt->fetch()){
            $counter += 1;
          }

            if($_SESSION['isStaff'] == 'yes'){
              $sql = "SELECT * FROM teacher WHERE email = :email AND teacher_id != :teacher_id";
              $stmt = $pdo->prepare($sql);
              $tid = $_SESSION['teacher_id'];
              $stmt->execute([
                  'email' => $email,
                  'teacher_id' => $tid
                ]);      
          } else {
              $sql = "SELECT * FROM teacher WHERE email = :email";
              $stmt = $pdo->prepare($sql);
              $stmt->execute([
                  'email' => $email
                ]);
  
          }
          $stmt->setFetchMode(PDO::FETCH_ASSOC);
          while($row = $stmt->fetch()){
            $counter += 1;
          }

          if ($counter > 0) {
            $emailErr = "<br>Email already taken";
          }
          
    
            if($usrErr == '' &&  $fnErr == '' &&  $lnErr == '' &&  $emailErr == ''){
                $name = $firstName . ' ' . $lastName;
                if($_SESSION['isStaff'] == 'yes'){
                    $tid = $_SESSION['teacher_id'];
                    $sql = "UPDATE `teacher` SET `username`='$username', `email`='$email', `name`='$name', `first_name`='$firstName', `surname`='$lastName' WHERE `teacher_id`=$tid";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute();  
                    $pdo = null;
                    header("location: teacher_profile.php");    
                } else {
                    $sid = $_SESSION['student_id'];
                    $sql = "UPDATE `student` SET `username`='$username', `email`='$email', `name`='$name', `first_name`='$firstName', `surname`='$lastName' WHERE `student_id`=$sid";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute();  
                    $pdo = null;
                    header("location: ../student/student_homepage.php");
                }
            }
        }
    
    ?>

<h1 style="text-align:left;">Edit Details</h1>
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


<?php
if($_SESSION['isStaff'] == 'yes'){
$sql = "SELECT * FROM teacher WHERE teacher_id = :teacher_id";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    'teacher_id' => $_SESSION['teacher_id']
]);
} else if ($_SESSION['isStaff'] == 'no') {
    $sql = "SELECT * FROM student WHERE student_id = :student_id";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'student_id' => $_SESSION['student_id']
    ]);    
}

$stmt->setFetchMode(PDO::FETCH_ASSOC);

while($row = $stmt->fetch()){
    $username = $row['username'];
    $email = $row['email'];
    $fn = $row['first_name'];
    $ln = $row['surname'];
}
?>

    <form name = "myForm" id = "myForm" method = "post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <br>
    <br>
            <p><b>Username</b></p><input type="text" class="texty" name="username" placeholder="Username" value="<?php echo $username; ?>" required />
            <span class="error"><?php echo $usrErr;?></span>
            <br>
            <br>
            <p><b>First Name</b></p><input type="text" class="texty" name="fn" placeholder="First Name" value="<?php echo $fn; ?>"required />
            <span class="error"><?php echo $fnErr;?></span> 
            <br>
            <br>
            <p><b>Last Name</b></p><input type="text" class="texty" name="ln" placeholder="Last Name" value="<?php echo $ln; ?>" required />
            <span class="error"><?php echo $lnErr;?></span>
            <br>
            <br>
            <p><b>Personal Email</b></p><input type="email" class="texty" name="email" placeholder="Email" value="<?php echo $email; ?>" /> 
            <span class="error"><?php echo $emailErr;?></span>
            <br>
            <br>
            <input type="submit" name="submit" value="Update Profile" class="login-button">
            <br><br>
    </form>
</body>
</html>