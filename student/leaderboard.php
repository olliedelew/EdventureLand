
  <?php
  session_start();
  if(!isset($_SESSION['user']) || !isset($_SESSION['school_id']) || !isset($_SESSION['student_id'])){
    echo '<script>location.href = "../login.php";</script>';
  }
  include '../connection.php';
  ?>
  <!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <title>SSM Leaderboard</title>
    <style>
      * {
        text-align: center;
      }
    .newest {
      border: 3px solid green;
    }

    .table th {
        background-color: grey;
        color: white;
        text-align: center;
    }
    .table td {
        background-color: white;
        text-align: center;
    }  
    .table {
        border: 1px solid black;

    }

      </style>
            <link rel="stylesheet" href="../style.css" />

</head>
<body>

<div class="container">
<h1 style="text-align:left;">Subject Savvy Millionaire Leaderboard</h1>
        <div class="row" style="background-color: white;">
            <div class="col-sm-3">
                <br>
                <div class="card">
                    <div class="card-body">

                        <button type="button" class="button" id="activated" onclick="location.href='../student/student_homepage.php'">Games</button><br><br>
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
                        <button type="button" class="button" onclick="location.href='../student/student_settings.php'">Account</button><br><br>
                    </div>
                </div>
            </div>

        </div>
<?php
    $sql = "SELECT *, LENGTH(JSON_EXTRACT(leaderboard.lifelines_used, '$.key')) as json_length FROM leaderboard INNER JOIN student ON student.student_id = leaderboard.student_id WHERE difficulty = :difficulty AND school_id = :school_id ORDER BY points DESC, json_length ASC, name ASC";
    $stmt = $pdo->prepare($sql);

    $stmt->execute([
    'difficulty' => $_SESSION['POST']['difficulty'],
    'school_id' => $_SESSION['school_id']
    ]);
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $counter = 0;
    echo '
    <h1>' . strtoupper($_SESSION['POST']['difficulty']) .' Leaderboard</h1>';
    if(isset($_SESSION['POST']['added'])){
      if($_SESSION['POST']['added'] == 'false'){
        echo '<p style="color:red; font-size: 20px;">Result not added as you have not beaten your previous highscore</p>';
      } elseif($_SESSION['POST']['added'] == 'true') {
        echo '<p style="color:green; font-size: 20px;">Result has been added! Well done!</p>';
      }

    }
    echo '
    <table class="table table-bordered">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Name</th>
        <th scope="col">Number of questions right</th>
        <th scope="col">Number of lifelines used</th>
        <th scope="col">Topic</th>
        <th scope="col">Points</th>
      </tr>
    </thead>
    <tbody>';
    $counter = 0;
    $first_relevant_sid = false;
    if(isset($_SESSION['POST']['id'])){
      $first_relevant_sid = true;
    }
    while($row = $stmt->fetch()){
      $counter++;
      if(json_decode($row['lifelines_used'])[0] == 'none'){
        $count = 0;
        $lifelines = json_decode($row['lifelines_used']);
        $lifelines_used = 'None used';
      } else {
        $lifelines = json_decode($row['lifelines_used']);
        $count = count($lifelines);
        for ($i=0; $i < count($lifelines); $i++) { 
          if($lifelines[$i] == 'fiftyfifty'){
            $lifelines[$i] = '50/50';
          } else if($lifelines[$i] == 'check'){
            $lifelines[$i] = 'Check Answer';
          } else if($lifelines[$i] == 'hint'){
            $lifelines[$i] = 'Hint';
          }
        }
        $lifelines_used = join(", ", $lifelines);
      }
    if($_SESSION['POST']['id'] == $row['leaderboard_id']){
      echo '
      <tr class="newest">
        <td>' . $counter . '</td>
        <td>' . '<img src="profile_pictures/' . $row['profile_picture'] . '" style="width:40px; height:40px;" alt="Profile Picture">' . ' ' . $row['name'] . '</td>
        <td>' . $row['questions_right'] . ' / 12' .'</td>
        <td>' . $count . ' : ' . $lifelines_used .'</td>
        <td>' . $row['topic'] . '</td>
        <td>' . $row['points'] . '</td>
        </tr>';
    } else {
      if($row['student_id'] == $_SESSION['student_id'] && $first_relevant_sid == false){
        echo '
        <tr class="newest">';
        $first_relevant_sid = true;
      } else {
        echo '
        <tr>';  
      }
    echo '
    <td>' . $counter . '</td>
    <td>' . '<img src="profile_pictures/' . $row['profile_picture'] . '" style="width:40px; height:40px;" alt="Profile Picture">' . ' ' . $row['name'] . '</td>
    <td>' . $row['questions_right'] . ' / 12' .'</td>
    <td>' . $count . ' : ' . $lifelines_used .'</td>
    <td>' . $row['topic'] . '</td>
    <td>' . $row['points'] . '</td>
    </tr>';
    }
  }

  $pdo = null;
  ?>
  </div>
  </body>
  </html>