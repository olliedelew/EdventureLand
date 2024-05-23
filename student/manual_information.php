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

<h1>Custom Assignment Information</h1>
        <!-- Profile pic -->
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
if(!isset($_POST['manual_id']) || !isset($_POST['manual_submission_id'])){
    $pdo = null;
    header('location: student_progress.php');
}
$manual_id = $_POST['manual_id'];
$submission_id = $_POST['manual_submission_id'];
$sql =  "SELECT * FROM manual_assignment WHERE manual_assignment_id = :manual_assignment_id";
$stmt = $pdo->prepare($sql);

$stmt->execute([
    'manual_assignment_id' => $manual_id
]);  

$stmt->setFetchMode(PDO::FETCH_ASSOC);
while($row = $stmt->fetch()){
    echo '<br>';

    echo '<div class="col-md-6" style="background-color:white; border: 5px solid black;">';
    $full_date = $row['test_datetime'];
    $datetime = explode(" ", $row['test_datetime']);
    $date = date("d-m-Y", strtotime($datetime[0]));
    $date = date('l jS F Y', strtotime($datetime[0]));
    $time = date('h:i:s a', strtotime($datetime[1]));
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
    echo '<h3>Prior Reading</h3>';


    if ($row['prior_reading'] == 'text_box'){
        echo $row['text_box'];
    } else if ($row['prior_reading'] == 'ytlink'){
        $ytlink = explode("v=", $row['ytlink']);       
        echo '<iframe width="520" height="345" src="https://www.youtube.com/embed/' . $ytlink[1] . '"></iframe>';
    } else if ($row['prior_reading'] == 'link'){
        echo '<a href="' . $row['link'] . '">' . $row['link'] . '</a>';
    } else if ($row['prior_reading'] == 'none'){
        echo 'No Prior Reading';
    } else if($row['prior_reading'] == 'pdf'){
            echo '<form action="show_prior_reading.php" method="post">
            <input type="hidden" name="id" value="'. $manual_id .'">
            <input type="hidden" name="type" value="manual">
            <button type="submit">Click here to download PDF</button>        
            </form>';
    }
    echo '<h3>Submission type</h3>';
    if($row['submission_type'] == 'pdf'){
        echo 'Submission is a PDF';
    } elseif($row['submission_type'] == 'text'){
        echo 'Submission is a piece of text';
    } elseif($row['submission_type'] == 'none'){
        echo 'No submission required';
    }
    echo '<br>';
    echo '</div>';   
}
echo '<div class="col-md-1">';
echo '</div>';

$sql =  "SELECT * FROM manual_submission WHERE manual_submission_id = :manual_submission_id";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    'manual_submission_id' => $submission_id
]);  
$stmt->setFetchMode(PDO::FETCH_ASSOC);
while($row = $stmt->fetch()){
    echo '<div class="col-md-5" style="background-color:white; border: 5px solid black;">';

    echo '<h1 style="text-align:center;"><u>Student Information</u></h1>';
    if($row['assignment_done'] == 1) {
        echo '<h3 style="text-align:center;">Assignment completed</h3>';
        if($row['result'] != NULL){
            echo '<h3>Result given: ' . $row['result'] . '%</h3>';
        } else {
            echo '<h3>No result given yet</h3>';
        }
        if($row['pdf_name'] != NULL){
            echo '<h3>Submission:</h3>';
            echo '<form action="show_prior_reading.php" method="post">
            <input type="hidden" name="id" value="'. $submission_id .'">
            <input type="hidden" name="type" value="submission">
            <button type="submit">Click here to download PDF Submission</button>        
            </form>';
            echo '<br>';
        } elseif($row['text_box'] != NULL){
            echo '<h3>Submission:</h3>';
            echo $row['text_box'];
        }
    } else {
        echo '<h3 style="text-align:center;">Assignment incomplete</h3>';
        if(date('Y-m-d H:i:s') < date($full_date)){
            echo '<form action="manual_submission.php" method="post">';
            echo '<div class="row">';
            echo '<input type="hidden" name="assignment_id" value="'. $manual_id .'">';
            echo '<input type="hidden" name="assignment_testid" value="'. $row['assignment_id'] .'">';
            echo '<button class="submit" style="margin-left: 15%;width:70%;">Click here to complete assignment</button>';
            echo '</div>';
            echo '</form>';    
        }
    }
    echo '</div>';
}
$pdo = null;
?>
</div>
</body>

</html>
