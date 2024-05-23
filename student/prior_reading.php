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
    <title>SSM Assignment</title>
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
        .download{
            background-color: orange;
        }
    </style>
</head>
<body>
<div class="container">

<h1 style="text-align: left;">Subject Savvy Millionaire Assignment</h1>
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
        $assignment_test_id = $_SESSION['POST']['assignment_testid'];
    }
} else {
    $assignmentID = $_POST['assignment_id'];
    $assignment_test_id = $_POST['assignment_testid'];
    $_SESSION['POST'] = $_POST;
}
$sql = "SELECT * FROM quiz_assignment WHERE quiz_assignment_id = :aid";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    'aid' => $assignmentID
]);  

$stmt->setFetchMode(PDO::FETCH_ASSOC);

while($row = $stmt->fetch()){
    $quizID = $row['quiz_assignment_id'];
echo "<br>";
echo '<div class="col-md-12" style="background-color:white; border: 5px solid black;">';
$datetime = explode(" ", $row['test_datetime']);
$date = date("d-m-Y", strtotime($datetime[0]));
$date = date('l jS F Y', strtotime($datetime[0]));
$time = date('h:i:s a', strtotime($datetime[1]));
    echo '<h1> Title: ' . $row['title'] . '</h1>';
    echo '<hr>';
    echo '<h3>Maximum Points On Completion</h3>';
    echo '<p>'. $row['points'].'</p>'; //'<p>'. .'</p>'
    echo '<hr>';
    if(!empty($row['description'])){
        echo '<h3>Description</h3>';
        echo '<p style="text-align:left;">'. $row['description'].'</p>'; //'<p>'. .'</p>'
        echo '<hr>';
    }
    echo '<h3>Due Date</h3>';
    echo '<p>'. $date . ' @ ' . $time.'</p>'; //'<p>'. .'</p>'
    echo '<hr>';
    echo '<h3>Prior Reading</h3>';
    if ($row['prior_reading'] == 'text_box'){
        echo '<p style="text-align:left;">'. $row['text_box'] .'</p>'; //'<p>'. .'</p>'
    } else if ($row['prior_reading'] == 'ytlink'){
        $ytlink = explode("v=", $row['ytlink']);       
        echo '<iframe width="700" height="345" src="https://www.youtube.com/embed/' . $ytlink[1] . '"></iframe>';
    } else if ($row['prior_reading'] == 'link'){
        echo '<h3><a href="' . $row['link'] . '">' . $row['link'] . '</a></h3>';
    } else if ($row['prior_reading'] == 'none'){
        echo '<h4>No Prior Reading</h4>';
    } else if($row['prior_reading'] == 'pdf'){
            echo '<form action="show_prior_reading.php" method="post">
            <input type="hidden" name="id" value="'. $assignmentID .'">
            <input type="hidden" name="type" value="quiz">
            <button type="submit" class="download">Click here to download PDF</button>        
            </form>';
    }
    echo '<form action="../Specific_SSM/playSpecific.php" method="post">';
    echo '<input type="hidden" name="quizID" value="'. $quizID .'">';
    echo '<input type="hidden" name="assignment_test_id" value="'. $assignment_test_id .'">';
    echo '<hr><button class="submit">Answer Questions</button>';
    echo '</form>';
    echo '<br>';
}
$pdo = null;
?>
</div>
</body>
</html>
