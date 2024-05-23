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
<h1 style="text-align:left;">Leaderboard</h1>
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
    if(!isset($_SESSION['POST']['difficulty']) || !isset($_SESSION['POST']['added'])){
      echo '<script>location.href = "../student/student_homepage.php";</script>';
    }
    include '../connection.php';

    // Get the school specific leaderboard
    $sql = "SELECT *, LENGTH(JSON_EXTRACT(math_leaderboard.operators, '$.key')) as json_length FROM math_leaderboard INNER JOIN student ON student.student_id = math_leaderboard.student_id WHERE difficulty = :difficulty AND school_id = :school_id ORDER BY points DESC, json_length DESC, questions_right DESC, questions_wrong ASC";
    $stmt = $pdo->prepare($sql);

    $stmt->execute([
    'difficulty' => $_SESSION['POST']['difficulty'],
    'school_id' => $_SESSION['school_id']
    ]);
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $counter = 0;

    // create title and check if they were added to the leaderboard or not
    echo '
    <h1>' . strtoupper($_SESSION['POST']['difficulty']) .' Leaderboard</h1>';
    if(isset($_SESSION['POST']['added'])){
      if($_SESSION['POST']['added'] == 'false'){
        echo '<p style="color:red; font-size: 20px;">Result not added as you have not beaten your previous highscore</p>';
      } elseif($_SESSION['POST']['added'] == 'true') {
        echo '<p style="color:green; font-size: 20px;">Result has been added! Well done!</p>';
      }
    }
    // create the table
    echo '
    <table class="table table-bordered">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Name</th>
        <th scope="col">Correct Answers</th>
        <th scope="col">Incorrect Answers</th>
        <th scope="col">Operators used</th>
        <th scope="col">Total Questions Attempted</th>
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
      $operators = explode("_", $row['operators']);
      $count = count($operators);
      for ($i=0; $i < count($operators); $i++) {
        if($operators[$i] == 'mult'){
          $operators[$i] = 'Multiplication';
        } else if($operators[$i] == 'add'){
          $operators[$i] = 'Addition';
        } else if($operators[$i] == 'minus'){
          $operators[$i] = 'Subtraction';
        } else if($operators[$i] == 'div'){
          $operators[$i] = 'Division';
        }
      }
      $operators_used = join(", ", $operators);
    if($_SESSION['POST']['id'] == $row['leaderboard_id']){
      echo '
      <tr class="newest">
        <td>' . $counter . '</td>
        <td>' . '<img src="../student/profile_pictures/' . $row['profile_picture'] . '" style="width:40px; height:40px;" alt="Profile Picture">' . ' ' . $row['name'] . '</td>
        <td>' . $row['questions_right'] . '</td>
        <td>' . $row['questions_wrong'] . '</td>
        <td>' . $count . ' : ' . $operators_used .'</td>
        <td>' . $row['questions_right'] + $row['questions_wrong'] . '</td>
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
      <td>' . '<img src="../student/profile_pictures/' . $row['profile_picture'] . '" style="width:40px; height:40px;" alt="Profile Picture">' . ' ' . $row['name'] . '</td>
      <td>' . $row['questions_right'] . '</td>
      <td>' . $row['questions_wrong'] . '</td>
      <td>' . $count . ' : ' . $operators_used .'</td>
      <td>' . $row['questions_right'] + $row['questions_wrong'] . '</td>
      <td>' . $row['points'] . '</td>
      </tr>';
    }
    }
    echo '</tbody>
    </table>
    ';
    $pdo = null;
    
  ?>
  </div>

  </div>
  </body>
  </html>