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
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <title>Homepage</title>
  <style type="text/css">
    .button {
      background-color: blue;
      color: white;
      font-size: 18px;
      padding: 14px 30px;
      width: 100%
    }

    img {
      display: block;
      margin-left: auto;
      margin-right: auto;
      width: 300px;
      height: 300px;

    }

    a:hover {
      cursor: default;
    }

    #hello {
      border: 5px solid black;
    }

  </style>
    <link rel="stylesheet" href="../style.css" />

</head>

<body>
  <div class="container" style="text-align:left;">
      <h1>Profile Page</h1>
      <hr>
      <!-- Profile pic -->
      <div class="row">
        <div class="col-sm-6">
          <div class="card">
            <div class="card-body">
              <!-- Clickable image allows image to be changed -->
              <div class="parent" style="text-align: center;">
                <label>
                  <!-- Do a php thing here to get the profile picture of teacher (get src)-->
                  <?php

                  $sql = "SELECT profile_picture FROM teacher WHERE teacher_id = :teacher_id";
                  $teachid = $_SESSION['teacher_id'];
                  $stmt = $pdo->prepare($sql);
                  $stmt->execute([
                    'teacher_id' => $teachid
                  ]);

                  $stmt->setFetchMode(PDO::FETCH_ASSOC);
                  while ($row = $stmt->fetch()) {
                    if (!empty($row["profile_picture"])) {
                      echo ('<a href="change_pic.php"><img src="profile_pictures/' . $row["profile_picture"] . '" class="image1"></a>');
                    } else {
                      echo ('<a href="change_pic.php"><img src="emptyIcon.png" class="image1"></a>');
                    }
                  }

                  ?>
                </label>
              </div>
              <br>
              <?php
              $sql = "SELECT * FROM teacher INNER JOIN school ON school.school_id = teacher.school_id WHERE teacher_id = :teacher_id";
              $teachid = $_SESSION['teacher_id'];
              $stmt = $pdo->prepare($sql);
              $stmt->execute([
                'teacher_id' => $teachid
              ]);

              $stmt->setFetchMode(PDO::FETCH_ASSOC);
              while ($row = $stmt->fetch()) {
                echo '<h2 class="card-title">' . $row["name"] . '</h5>';
                echo '<br>
                <h2 class="card-title">' . $row['school_name'] . '</h5>';
              }
              ?>

            </div>
          </div>
        </div>
        <div class="col-sm-6">
          <div class="card">
            <div class="card-body">
              <button type="button" class="button" onclick="location.href='groups.php'">Student Groups</button><br><br>
              <button type="button" class="button" onclick="location.href='assset.php'">Assignments set</button><br><br>
              <button type="button" class="button" onclick="location.href='set_assignment.php'">Set assignments</button><br><br>
              <button type="button" class="button" onclick="location.href='../student/db_grouper.php'">Discussion Boards</button><br><br>
              <button type="button" class="button" onclick="location.href='settings.php'">Settings</button><br><br>
            </div>
          </div>
        </div>
      </div>
<div class="row" style="background-color:white;">
      <div class="col-sm-6" id='hello'>
        <h3 style="text-align:center"><u>Highest Performing Students</u></h3>
        <?php
          $sql = "SELECT * FROM teacher_student WHERE teacher_id = :teacher_id ORDER BY points DESC LIMIT 5";

          $teachid = $_SESSION['teacher_id'];
          $stmt = $pdo->prepare($sql);
          $stmt->execute([
            'teacher_id' => $teachid
          ]);

          $stmt->setFetchMode(PDO::FETCH_ASSOC);

          $ids = array();
          $points = array();
          
          while($row = $stmt->fetch()){
            array_push($ids, intval($row['student_id']));
            array_push($points, $row['points']);
          }
          $sql = "SELECT * FROM student WHERE student_id = :student_id"; //Order by???
          $counter = 0;

          foreach ($ids as $id) {
            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                'student_id' => $id
            ]);  
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            while($row = $stmt->fetch()){
              $counter++;
              echo '<form id="highest'. $counter . '" method="post" action="progress.php">';
              echo '<input type="hidden" name="sid" value="'. $id .'">';
              echo '<h4 style="text-align:center">' . $counter . ') ' . '<a href="javascript:{}" onclick="document.getElementById(`highest'. $counter . '`).submit();">' .  $row["name"] . '</a> - ' . $points[$counter-1] .'</h4>';
              echo '</form>';
          }
        }
        
        ?>

      </div>
      <div class="col-sm-6" id='hello'>
        <h3 style="text-align:center"><u>Lowest Performing Students</u></h3>
        <?php
          $sql = "SELECT * FROM teacher_student WHERE teacher_id = :teacher_id ORDER BY points ASC LIMIT 5";
          $teachid = $_SESSION['teacher_id'];
          $stmt = $pdo->prepare($sql);
          $stmt->execute([
            'teacher_id' => $teachid
          ]);

          $stmt->setFetchMode(PDO::FETCH_ASSOC);

          $ids = array();
          $points = array();
          
          while($row = $stmt->fetch()){
            array_push($ids, intval($row['student_id']));
            array_push($points, $row['points']);
          }
          $sql = "SELECT * FROM student WHERE student_id = :student_id"; //Order by???
          $counter = 0;

          foreach ($ids as $id) {
            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                'student_id' => $id
            ]);  
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            while($row = $stmt->fetch()){
              $counter++;
              echo '<form id="lowest'. $counter . '" method="post" action="progress.php">';
              echo '<input type="hidden" name="sid" value="'. $id .'">';
              echo '<h4 style="text-align:center">' . $counter . ') ' . '<a href="javascript:{}" onclick="document.getElementById(`lowest'. $counter . '`).submit();">' .  $row["name"] . '</a> - ' . $points[$counter-1] .'</h4>';
              echo '</form>';
    
          }
        }
        $pdo = null;
        ?>
</div>
      </div>
  </div>
</body>

</html>