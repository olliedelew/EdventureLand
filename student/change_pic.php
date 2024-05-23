<?php 
session_start();
if(!isset($_SESSION['user']) || !isset($_SESSION['school_id']) || !isset($_SESSION['student_id'])){
  echo '<script>location.href = "../login.php";</script>';
}
include '../connection.php';
// if(($_SESSION['isStaff'] == 'yes') && isset($_SESSION['user'])) //check if user is a user and display buttons
//     {
    ?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
	<title>Change Profile Picture</title>
  <link rel="stylesheet" href="../style.css" />
	<style type="text/css">

    img, input {
      display: block;
      margin-left: auto;
      margin-right: auto;
      width: 50%;

    }

    .grey-image{
        filter: brightness(20%);
    }


img:hover, input:hover{
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

                        <button type="button" class="button" onclick="location.href='student_homepage.php'">Games</button><br><br>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <br>
                <div class="card">
                    <div class="card-body">
                        <button type="button" class="button" onclick="location.href='db_grouper.php'">Discussion Boards</button><br><br> <!-- Student groupmates -->
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
                        <button type="button" class="button" id="activated" onclick="location.href='student_settings.php'">Account</button><br><br>
                    </div>
                </div>
            </div>

        </div>
        <br>
    <div class="col-md-4">
    <?php       

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

      $sql = "SELECT badge_id FROM student_badge WHERE student_id = :student_id";  
      $stmt = $pdo->prepare($sql);
      $stmt->execute([
        'student_id' => $sid
      ]);
      $stmt->setFetchMode(PDO::FETCH_ASSOC);
      $badges_array = array();
      while($row = $stmt->fetch()){
        array_push($badges_array, $row['badge_id']);
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

  <!--  -->

  <div class="col-md-3">
      <?php
        if(in_array(1, $badges_array)){ //2,11,12
      ?>
            <form action="change_pic_upload.php" method="post">
                <input type="hidden" name="pic" value="avatar11.png">
                <input type="image" name="submit" src="profile_pictures/avatar11.png" alt="Profile Picture" >
            </form>
      <?php
          } else {
      ?>
            <img src="profile_pictures/avatar11.png" alt="Thumbnail" class="grey-image">
      <?php
          }
      ?>
  </div>

  <div class="col-md-3">
      <?php
        if(in_array(2, $badges_array)){ //2,11,12
      ?>
            <form action="change_pic_upload.php" method="post">
                <input type="hidden" name="pic" value="avatar12.png">
                <input type="image" name="submit" src="profile_pictures/avatar12.png" alt="Profile Picture" >
            </form>
      <?php
          } else {
      ?>
            <img src="profile_pictures/avatar12.png" alt="Thumbnail" class="grey-image">
      <?php
          }
      ?>
  </div>

  </div>
  <br>

  <div class="row">
  <div class="col-md-3">
      <?php
        if(in_array(11, $badges_array)){ //2,11,12
      ?>
            <form action="change_pic_upload.php" method="post">
                <input type="hidden" name="pic" value="avatar13.png">
                <input type="image" name="submit" src="profile_pictures/avatar13.png" alt="Profile Picture" >
            </form>

      <?php
          } else {
      ?>
            <img src="profile_pictures/avatar13.png" alt="Thumbnail" class="grey-image">
      <?php
          }
      ?>
  </div>

  <div class="col-md-3">
      <?php
        if(in_array(12, $badges_array)){ //2,11,12
      ?>
            <form action="change_pic_upload.php" method="post">
                <input type="hidden" name="pic" value="avatar14.png">
                <input type="image" name="submit" src="profile_pictures/avatar14.png" alt="Profile Picture" >
            </form>      
      <?php
          } else {
      ?>
            <img src="profile_pictures/avatar14.png" alt="Thumbnail" class="grey-image">
      <?php
          }
      ?>
  </div>

  <div class="col-md-3">
      <?php
        if(in_array(3, $badges_array)){ //3, 4, 5, 6
      ?>
            <form action="change_pic_upload.php" method="post">
                <input type="hidden" name="pic" value="avatar15.png">
                <input type="image" name="submit" src="profile_pictures/avatar15.png" alt="Profile Picture" >
            </form> 
      <?php
          } else {
      ?>
            <img src="profile_pictures/avatar15.png" alt="Thumbnail" class="grey-image">
      <?php
          }
      ?>
  </div>

  <div class="col-md-3">
      <?php
        if(in_array(4, $badges_array)){ //3, 4, 5, 6
      ?>
            <form action="change_pic_upload.php" method="post">
                <input type="hidden" name="pic" value="avatar16.png">
                <input type="image" name="submit" src="profile_pictures/avatar16.png" alt="Profile Picture" >
            </form> 
      <?php
          } else {
      ?>
            <img src="profile_pictures/avatar16.png" alt="Thumbnail" class="grey-image">
      <?php
          }
      ?>
  </div>


  </div>
  <br>

  <div class="row">
  <div class="col-md-3">
      <?php
        if(in_array(5, $badges_array)){ //3, 4, 5, 6
      ?>
            <form action="change_pic_upload.php" method="post">
                <input type="hidden" name="pic" value="avatar17.png">
                <input type="image" name="submit" src="profile_pictures/avatar17.png" alt="Profile Picture" >
            </form> 
      <?php
          } else {
      ?>
            <img src="profile_pictures/avatar17.png" alt="Thumbnail" class="grey-image">
      <?php
          }
      ?>
  </div>

  <div class="col-md-3">
      <?php
        if(in_array(6, $badges_array)){ //3, 4, 5, 6
      ?>
            <form action="change_pic_upload.php" method="post">
                <input type="hidden" name="pic" value="avatar18.png">
                <input type="image" name="submit" src="profile_pictures/avatar18.png" alt="Profile Picture" >
            </form> 
      <?php
          } else {
      ?>
            <img src="profile_pictures/avatar18.png" alt="Thumbnail" class="grey-image">
      <?php
          }
      ?>
  </div>

  <div class="col-md-3">
      <?php
        if(in_array(7, $badges_array)){ //7, 8, 9, 10
      ?>
            <form action="change_pic_upload.php" method="post">
                <input type="hidden" name="pic" value="avatar19.png">
                <input type="image" name="submit" src="profile_pictures/avatar19.png" alt="Profile Picture" >
            </form> 
      <?php
          } else {
      ?>
            <img src="profile_pictures/avatar19.png" alt="Thumbnail" class="grey-image">
      <?php
          }
      ?>
  </div>

  <div class="col-md-3">
      <?php
        if(in_array(8, $badges_array)){ //7, 8, 9, 10
      ?>
        <form action="change_pic_upload.php" method="post">
            <input type="hidden" name="pic" value="avatar20.png">
            <input type="image" name="submit" src="profile_pictures/avatar20.png" alt="Profile Picture" >
        </form> 
      <?php
          } else {
      ?>
            <img src="profile_pictures/avatar20.png" alt="Thumbnail" class="grey-image">
      <?php
          }
      ?>
  </div>


</div>
<br>

<div class="row">
<div class="col-md-3">
      <?php
        if(in_array(9, $badges_array)){ //7, 8, 9, 10
      ?>
        <form action="change_pic_upload.php" method="post">
            <input type="hidden" name="pic" value="avatar21.png">
            <input type="image" name="submit" src="profile_pictures/avatar21.png" alt="Profile Picture" >
        </form> 
      <?php
          } else {
      ?>
            <img src="profile_pictures/avatar21.png" alt="Thumbnail" class="grey-image">
      <?php
          }
      ?>
  </div>

  <div class="col-md-3">
      <?php
        if(in_array(10, $badges_array)){ //7, 8, 9, 10
      ?>
        <form action="change_pic_upload.php" method="post">
            <input type="hidden" name="pic" value="avatar22.png">
            <input type="image" name="submit" src="profile_pictures/avatar22.png" alt="Profile Picture" >
        </form> 
      <?php
          } else {
      ?>
            <img src="profile_pictures/avatar22.png" alt="Thumbnail" class="grey-image">
      <?php
          }
      ?>
  </div>

  <div class="col-md-3">
      <?php
        if(in_array(13, $badges_array)){ //13, 14, 15, 16
      ?>
        <form action="change_pic_upload.php" method="post">
            <input type="hidden" name="pic" value="avatar23.png">
            <input type="image" name="submit" src="profile_pictures/avatar23.png" alt="Profile Picture" >
        </form> 
      <?php
          } else {
      ?>
            <img src="profile_pictures/avatar23.png" alt="Thumbnail" class="grey-image">
      <?php
          }
      ?>
  </div>

  <div class="col-md-3">
      <?php
        if(in_array(14, $badges_array)){ //13, 14, 15, 16
      ?>
        <form action="change_pic_upload.php" method="post">
            <input type="hidden" name="pic" value="avatar24.png">
            <input type="image" name="submit" src="profile_pictures/avatar24.png" alt="Profile Picture" >
        </form> 
      <?php
          } else {
      ?>
            <img src="profile_pictures/avatar24.png" alt="Thumbnail" class="grey-image" >
      <?php
          }
      ?>
  </div>

</div>
<br>

<div class="row">

<div class="col-md-3">
      <?php
        if(in_array(15, $badges_array)){ //13, 14, 15, 16
      ?>
        <form action="change_pic_upload.php" method="post">
            <input type="hidden" name="pic" value="avatar25.png">
            <input type="image" name="submit" src="profile_pictures/avatar25.png" alt="Profile Picture" >
        </form> 
      <?php
          } else {
      ?>
            <img src="profile_pictures/avatar25.png" alt="Thumbnail" class="grey-image">
      <?php
          }
      ?>
  </div>

  <div class="col-md-3">
      <?php
        if(in_array(16, $badges_array)){ //13, 14, 15, 16
      ?>
              <form action="change_pic_upload.php" method="post">
            <input type="hidden" name="pic" value="avatar26.png">
            <input type="image" name="submit" src="profile_pictures/avatar26.png" alt="Profile Picture" >
        </form> 
      <?php
          } else {
      ?>
            <img src="profile_pictures/avatar26.png" alt="Thumbnail" class="grey-image">
      <?php
          }
      ?>
  </div>

</div>



  </div>

<?php  
?>
</div>
</body>
</html>