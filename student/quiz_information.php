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
    <title>SSM Assignment Information</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="../style.css" />

</head>

<body>
<div class="container">

<h1>Subject Savvy Millionaire Assignment Information</h1>
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
if(!isset($_POST['quiz_id']) || !isset($_POST['quiz_submission_id'])){
    $pdo = null;
    header('location: student_progress.php');
}
$quiz_id = $_POST['quiz_id'];
$submission_id = $_POST['quiz_submission_id'];
$sql =  "SELECT * FROM quiz_assignment WHERE quiz_assignment_id = :quiz_assignment_id";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    'quiz_assignment_id' => $quiz_id
]);  

$stmt->setFetchMode(PDO::FETCH_ASSOC);
while($row = $stmt->fetch()){
    $assignment_id = $row['quiz_assignment_id'];
    $full_date = $row['test_datetime'];
    $datetime = explode(" ", $row['test_datetime']);
    $date = date("d-m-Y", strtotime($datetime[0]));
    $date = date('l jS F Y', strtotime($datetime[0]));
    $time = date('h:i:s a', strtotime($datetime[1]));
    echo '<br>';
    echo '<div class="col-md-6" style="background-color:white; border: 5px solid black;">';
    echo '<h1 style="text-align:center;"><u>Quiz Information</u></h1>';
    echo '<h2> Title: ' . $row['title'] . '</h2>';
    echo '<hr>';
    echo '<h3>Maximum Points On Completion</h3>';
    echo $row['points'];
    echo '<hr>';
    echo '<h3>Due Date</h3>';
    echo $date . ' @ ' . $time;
    echo '<hr>';
    echo '<h3>Prior Reading</h3>';
    if ($row['prior_reading'] == 'text_box'){
        echo $row['text_box'];
    } else if ($row['prior_reading'] == 'ytlink'){
        $ytlink = explode("v=", $row['ytlink']);       
        echo '<iframe width="520" height="345" src="https://www.youtube.com/embed/' . $ytlink[1] . '"></iframe>';
    } else if ($row['prior_reading'] == 'link'){
        echo '<h3><a href="' . $row['link'] . '">' . $row['link'] . '</a></h3>';
    } else if ($row['prior_reading'] == 'none'){
        echo 'No prior reading';
    } else if($row['prior_reading'] == 'pdf'){
            echo '<form action="show_prior_reading.php" method="post">
            <input type="hidden" name="id" value="'. $quiz_id .'">
            <input type="hidden" name="type" value="quiz">
            <button type="submit">Click here to download PDF</button>        
            </form>';
    }
    echo '<br>';
    echo '</div>';
}
echo '<div class="col-md-1">';
echo '</div>';
$sql =  "SELECT * FROM quiz_submission WHERE quiz_submission_id = :quiz_submission_id";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    'quiz_submission_id' => $submission_id
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
            echo '<form action="prior_reading.php" method="post">';
            echo '<div class="row">';
            echo '<input type="hidden" name="assignment_id" value="'. $assignment_id .'">';
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
    echo '<h3>Total Questions</h3>';
    echo $row['questions_total'];
    echo '<hr>';
    echo '<h3>Result</h3>';
    if(!empty($row['result'])){
        echo $row['result'] . '%';
    }
    echo '<hr>';
    echo '<h3>Lifelines used</h3>';
    if(!empty($lifelines)){
        $lifelines = json_decode($row['lifelines_used']);
        for ($i=0; $i < count($lifelines); $i++) { 
            if($lifelines[$i] == 'check'){
                $lifelines[$i] = 'Check their answer';
            } elseif($lifelines[$i] == 'hint'){
                $lifelines[$i] = 'Get hint';
            } elseif($lifelines[$i] == '50/50'){
                $lifelines[$i] = '50/50';
            } elseif($lifelines[$i] == 'none'){
                $lifelines[$i] = 'No lifelines used';
            }
        }
        echo implode(",", $lifelines);
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
