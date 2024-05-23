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
</head>

<body>
    <div class="container">
<h1>Assignment Information</h1>
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

<?php
if(!isset($_POST['math_id']) || !isset($_POST['math_submission_id'])){
    $pdo = null;
    header('location: student_progress.php');
}
$math_id = $_POST['math_id'];
$submission_id = $_POST['math_submission_id'];
$sql =  "SELECT * FROM math_assignment WHERE math_assignment_id = :math_assignment_id";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    'math_assignment_id' => $math_id
]);  

$stmt->setFetchMode(PDO::FETCH_ASSOC);
while($row = $stmt->fetch()){
    $full_date = $row['test_datetime'];
    $datetime = explode(" ", $row['test_datetime']);
    $date = date("d-m-Y", strtotime($datetime[0]));
    $date = date('l jS F Y', strtotime($datetime[0]));
    $time = date('h:i:s a', strtotime($datetime[1]));
    echo '<br>';
    echo '<div class="col-md-6" style="background-color:white; border: 5px solid black;">';
    echo '<h1><u>Information On Assignment</u></h1>';
    echo '<h2> Title: ' . $row['title'] . '</h2>';
    echo '<hr>';
    echo '<h3>Description</h3>';
    echo $row['description'];
    echo '<hr>';
    echo '<h3>Maximum Points On Completion</h3>';
    echo $row['points'];
    echo '<hr>';
    echo '<h3>Due Date</h3>';
    echo $date . ' @ ' . $time;
    echo '<hr>';
    echo '<h3>Difficulty</h3>';
    echo $row['difficulty'];
    echo '<hr>';
    echo '<h3>Duration</h3>';
    echo $row['duration'] . ' minutes';
    echo '<hr>';
    echo '<h3>Pass mark</h3>';
    echo $row['pass_percentage'];
    echo '<hr>';
    echo '<h3>Operators used</h3>';
    $operators = json_decode($row['operators']);
    for ($i=0; $i < count($operators); $i++) { 
        if($operators[$i] == 'add'){
            $operators[$i] = 'Addition';
        } elseif($operators[$i] == 'minus'){
            $operators[$i] = 'Subtraction';
        } elseif($operators[$i] == 'div'){
            $operators[$i] = 'Division';
        } elseif($operators[$i] == 'mult'){
            $operators[$i] = 'Multiplication';
        }
    }
    echo implode(", ", $operators);
    echo '<br>';
    echo '<br>';
    echo '</div>';
    
}
echo '<div class="col-md-1">';
echo '</div>';

$sql =  "SELECT * FROM math_submission WHERE math_submission_id = :math_submission_id";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    'math_submission_id' => $submission_id
]);  
$stmt->setFetchMode(PDO::FETCH_ASSOC);
while($row = $stmt->fetch()){
    echo '<div class="col-md-5" style="background-color:white; border: 5px solid black;">';
    echo '<h1 style="text-align:center;"><u>Student Information</u></h1>';
    if($row['assignment_done'] == 1) {
        echo '<h3 style="text-align:center;">Assignment completed</h3>';
    } else {
        echo '<h3 style="text-align:center;">Assignment incomplete</h3>';
        if(date('Y-m-d H:i:s') < date($full_date)){
            echo '<form action="math_prior.php" method="post">';
            echo '<div class="row">';
            echo '<input type="hidden" name="assignment_id" value="'. $math_id .'">';
            echo '<input type="hidden" name="assignment_testid" value="'. $row['assignment_id'] .'">';
            echo '<button class="submit" style="margin-left: 15%;width:70%;">Click here to complete assignment</button>';
            echo '</div>';
            echo '</form>';
        }
    }
    echo '<h3>Attempts</h3>';
    echo $row['attempts'];
    echo '<hr>';
    echo '<h3>Questions right</h3>';
    echo $row['questions_right'];
    echo '<hr>';
    echo '<h3>Questions wrong</h3>';
    echo $row['questions_wrong'];
    echo '<hr>';
    echo '<h3>Questions Per Minute</h3>';
    echo $row['QPM'];
    echo '<hr>';
    echo '<h3>Result</h3>';
    if(!empty($row['result'])){
        echo $row['result'] . '%';
    }
    echo '<hr>';
    echo '<h3>Points</h3>';
    if(!empty($row['points'])){
        echo $row['points'];
        echo '<br>';
    }
    echo '<br>';
    echo '</div>';
}
$pdo = null;
?>
</div>
</body>

</html>
