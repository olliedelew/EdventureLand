<?php
session_start();
if(!isset($_SESSION['user']) || !isset($_SESSION['school_id']) || !isset($_SESSION['student_id'])){
    echo '<script>location.href = "../login.php";</script>';
}
include '../connection.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assignment Information</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="../style.css" />
    <style>
        * {
            text-align: center;
            font-size: 20px;
        }
        button{
            width: 50%;
            background-color: green;
        }
    </style>
</head>
<body>
<div class="container">

<h1 style="text-align: left;">Math Assignment</h1>
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
                        <button type="button" class="button" id="activated" onclick="location.href='student_assignments.php'">Assignments</button><br><br>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <br>
                <div class="card">
                    <div class="card-body">
                        <button type="button" class="button" onclick="location.href='student_settings.php'">Account</button><br><br>
                    </div>
                </div>
            </div>

        </div>

<?php

// Here we show the prior reading that the teacher has set
if(!isset($_POST['assignment_id']) || !isset($_POST['assignment_testid'])){
    if(!isset($_SESSION['POST']['assignment_id']) || !isset($_SESSION['POST']['assignment_testid'])){
        $pdo = null;
        header('location: ../login.php');
    } else {
        $assignmentID = $_SESSION['POST']['assignment_id'];
        $actual_aid = $_SESSION['POST']['assignment_testid'];
    }
} else {
    $assignmentID = $_POST['assignment_id'];
    $actual_aid = $_POST['assignment_testid'];
    $_SESSION['POST'] = $_POST;
}
$sql = "SELECT * FROM math_assignment WHERE math_assignment_id = :math_assignment_id";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    'math_assignment_id' => $assignmentID
]);  

$stmt->setFetchMode(PDO::FETCH_ASSOC);

while($row = $stmt->fetch()){
    $operators = json_decode($row['operators']);
    for ($i=0; $i < count($operators); $i++) { 
        if($operators[$i] == 'minus'){
          $operators[$i] = 'Subtraction';
        } else if($operators[$i] == 'div'){
          $operators[$i] = 'Division';
        } else if($operators[$i] == 'mult'){
          $operators[$i] = 'Multiplication';
        } else if($operators[$i] == 'add'){
            $operators[$i] = 'Addition';
        } else {

        }
      }
      $operators = join(", ", $operators);
    $datetime = explode(" ", $row['test_datetime']);
    $date = date("d-m-Y", strtotime($datetime[0]));
    $date = date('l jS F Y', strtotime($datetime[0]));
    $time = date('h:i:s a', strtotime($datetime[1]));
    echo "<br>";
    echo '<div class="col-md-12" style="background-color:white; border: 5px solid black;">';
    echo '<h1> Title: ' . $row['title'] . '</h1>';
    echo '<hr>';
    echo '<h3>Maximum Points On Completion</h3>';
    echo '<p>'. $row['points'].'</p>'; //'<p>'. .'</p>'
    echo '<hr>';
    echo '<h3>Due Date</h3>';
    echo '<p>'. $date . ' @ ' . $time.'</p>'; //'<p>'. .'</p>'
    echo '<hr>';
    echo '<h3>Description</h3>';
    echo '<p style="text-align:left;">'. $row['description'].'</p>'; //'<p>'. .'</p>'
    echo '<hr>';
    echo '<h3>Operators</h3>';
    echo '<p>'. $operators.'</p>'; //'<p>'. .'</p>'
    echo '<hr>';
    echo '<h3>Difficulty</h3>';
    echo '<p>'. $row['difficulty'].'</p><hr>'; //'<p>'. .'</p>'
    echo '<form action="../Math_Game_Assignment/math_assignment_game.php" method="post">';
    echo '<input type="hidden" name="assignmentID" value="'. $assignmentID .'">';
    echo '<input type="hidden" name="assignment_test_id" value="'. $actual_aid .'">';
    echo '<button class="submit">Lets Play!</button>';
    echo '</form>';
    echo '<br>';

    echo '</div>';
}
$pdo = null;
?>
</div>
</body>
</html>
