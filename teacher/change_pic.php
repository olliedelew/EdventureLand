<?php 
session_start();

  if(!isset($_SESSION['user']) || !isset($_SESSION['school_id']) || !isset($_SESSION['teacher_id'])){
    echo '<script>location.href = "../login.php";</script>';
  }   
  include '../connection.php';

  ?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
	<title>Change Picture</title>
  <link rel="stylesheet" href="../style.css" />
	<style type="text/css">

img, input {
      display: block;
      margin-left: auto;
      margin-right: auto;
      width: 50%;

    }

    img:hover ,input:hover{
      filter: brightness(50%);
      cursor: pointer;
    }
    
	</style>
</head>
<body>
<div class="container" style="text-align:left;">

<h1>Change profile picture</h1>
  <div class="row" style="background-color: white;">
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
        <br>

    <div class="col-md-4">
    <?php       

        if(isset($_SESSION['teacher_id'])){
                
        $sql = "SELECT profile_picture FROM teacher WHERE teacher_id = :teacher_id";
  
        $teachid = $_SESSION['teacher_id'];
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'teacher_id' => $teachid
        ]);
        
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        while($row = $stmt->fetch()){
            if(!empty($row["profile_picture"])){
                echo('<img src="profile_pictures/'. $row["profile_picture"] .'" alt="Thumbnail" >');
            }else{
                echo('<img src="emptyIcon.png" alt="Thumbnail" >');
            }
        }      
      } elseif (isset($_SESSION['student_id'])) {
        $sql = "SELECT profile_picture FROM student WHERE student_id = :student_id";
  
        $sid = $_SESSION['student_id'];
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'student_id' => $sid
        ]);
        
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        while($row = $stmt->fetch()){
            if(!empty($row["profile_picture"])){
                echo('<img src="profile_pictures/'. $row["profile_picture"] .'" alt="Thumbnail" >');
            }else{
                echo('<img src="emptyIcon.png" alt="Thumbnail" >');
            }
        }      

      }
      $pdo = null;
    ?>
    </div>
    <div class="col-md-4">

    </div>
    <div class="col-md-4">

    </div>
  <div class="col-md-12">
  <hr>

  <div class="row">
  <div class="col-md-3">
        <form action="change_pic_upload.php" method="post">
        <input type="hidden" name="pic" value="avatar1.png">
        <input type="image" name="submit" src="profile_pictures/avatar1.png" alt="Profile Picture" >
        </form>
  </div>
  <div class="col-md-3">
        <form action="change_pic_upload.php" method="post">
          <input type="hidden" name="pic" value="avatar2.png">
          <input type="image" name="submit" src="profile_pictures/avatar2.png" alt="Profile Picture" >
        </form>
  </div>

  <div class="col-md-3">
      <form action="change_pic_upload.php" method="post">
          <input type="hidden" name="pic" value="avatar3.png">
          <input type="image" name="submit" src="profile_pictures/avatar3.png" alt="Profile Picture" >
      </form>
  </div>
  <div class="col-md-3">
      <form action="change_pic_upload.php" method="post">
          <input type="hidden" name="pic" value="avatar4.png">
          <input type="image" name="submit" src="profile_pictures/avatar4.png" alt="Profile Picture" >
      </form>
  </div>

  </div>
  <br>
  <div class="row">
  <div class="col-md-3">
      <form action="change_pic_upload.php" method="post">
          <input type="hidden" name="pic" value="avatar5.png">
          <input type="image" name="submit" src="profile_pictures/avatar5.png" alt="Profile Picture" >
      </form>
  </div>
  <div class="col-md-3">
      <form action="change_pic_upload.php" method="post">
          <input type="hidden" name="pic" value="avatar6.png">
          <input type="image" name="submit" src="profile_pictures/avatar6.png" alt="Profile Picture" >
      </form>
  </div>

  <div class="col-md-3">
      <form action="change_pic_upload.php" method="post">
          <input type="hidden" name="pic" value="avatar7.png">
          <input type="image" name="submit" src="profile_pictures/avatar7.png" alt="Profile Picture" >
      </form>
  </div>
  <div class="col-md-3">
      <form action="change_pic_upload.php" method="post">
          <input type="hidden" name="pic" value="avatar8.png">
          <input type="image" name="submit" src="profile_pictures/avatar8.png" alt="Profile Picture" >
      </form>
  </div>

  </div>
  <br>
  <div class="row">
  <div class="col-md-3">
      <form action="change_pic_upload.php" method="post">
          <input type="hidden" name="pic" value="avatar9.png">
          <input type="image" name="submit" src="profile_pictures/avatar9.png" alt="Profile Picture" >
      </form>
  </div>  
  <div class="col-md-3">
      <form action="change_pic_upload.php" method="post">
          <input type="hidden" name="pic" value="avatar10.png">
          <input type="image" name="submit" src="profile_pictures/avatar10.png" alt="Profile Picture" >
      </form>
  </div> 

  </div>


  </div>
</div>
</body>
</html>