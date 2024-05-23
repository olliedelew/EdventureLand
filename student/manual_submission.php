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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <title>Submit Custom Assignment</title>
    <style>
        * {
            text-align: center;
            font-size: 20px;
        }
        textarea {
            resize: none;
        }
        .center {
  display: block;
  margin: 0 auto;
  text-align: center;
}
        button{
            width: 50%;
            background-color: green;
        }
        .download{
            background-color: orange;
        }

    </style>
    <link rel="stylesheet" href="../style.css" />
</head>

<body>
    <div class="container">
<h1 style="text-align: left;">Custom Assignment Submission</h1>
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

        if(!isset($_POST['assignment_id'])){
            if(!isset($_SESSION['POST']['assignment_id'])){
                $pdo = null;
                header('location: ../login.php');
            } else {
                $aid = $_SESSION['POST']['assignment_id'];
            }
        } else {
            $aid = $_POST['assignment_id'];
            
            $_SESSION['POST'] = $_POST;
        }

        $sql = "SELECT * FROM manual_assignment WHERE manual_assignment_id = :aid";
        $assignmentID = $_POST['assignment_id'];
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'aid' => $assignmentID
        ]);

        $stmt->setFetchMode(PDO::FETCH_ASSOC);


        while ($row = $stmt->fetch()) {
            $datetime = explode(" ", $row['test_datetime']);
            $date = date("d-m-Y", strtotime($datetime[0]));
            $date = date('l jS F Y', strtotime($datetime[0]));
            $time = date('h:i:s a', strtotime($datetime[1]));
            echo "<br>";
            echo '<div class="col-md-12" style="background-color:white; border: 5px solid black;">';
            echo '<h1> Title: ' . $row['title'] . '</h1>';
            echo '<hr>';
            echo '<h3>Description</h3>';
            echo '<p style="text-align:left;">'. $row['description'].'</p>'; //'<p>'. .'</p>'
            echo '<hr>';
            echo '<h3>Maximum Points On Completion</h3>';
            echo '<p>'. $row['points'].'</p>'; //'<p>'. .'</p>'
            echo '<hr>';
            echo '<h3>Due Date</h3>';
            echo '<p>'. $date . ' @ ' . $time.'</p>'; //'<p>'. .'</p>'
            echo '<hr><h3>Prior reading</h3>';
            if($row['prior_reading'] == 'pdf'){
                echo '<form action="show_prior_reading.php" method="post">
                <input type="hidden" name="id" value="'. $assignmentID .'">
                <input type="hidden" name="type" value="manual">
                <button type="submit" style="background-color:orange; width:50%;">Click here to download PDF</button>        
                </form>';
            } else if ($row['prior_reading'] == 'text_box'){
                echo '<p style="text-align:left;">'. $row['text_box'].'</p>'; //'<p>'. .'</p>'
            } else if ($row['prior_reading'] == 'ytlink'){
                $ytlink = explode("v=", $row['ytlink']);       
                echo '<iframe width="700" height="345" src="https://www.youtube.com/embed/' . $ytlink[1] . '"></iframe>';
            } else if ($row['prior_reading'] == 'link'){
                echo '<a href="' . $row['link'] . '">' . $row['link'] . '</a>';
            } else {
                echo '<h4>No Prior Reading Set</h4>';
            }
            echo '<form action="submit_manual_validation.php" enctype="multipart/form-data" method="POST">';
            echo '<input type="hidden" name="assignment_type" value="' . $row['submission_type'] . '" />';
            echo '<input type="hidden" name="group_ID" value="' . $row['student_group_id'] . '" />';
            echo '<input type="hidden" name="assignment_id" value="' . $_POST['assignment_testid'] . '" />';
            if ($row['submission_type'] == 'pdf') {
                echo '<hr>';
                echo '<h3 >Upload PDF here:</h3><br>';
                echo '<input type="file" onchange="validatePDF(this)" name="pdf_file" accept=".pdf" class="center" required/>';
            } else if ($row['submission_type'] == 'text') {
                echo '<hr>';
                echo '<textarea class="form-control" placeholder="Whatever is written here will be your submission" rows="10" name="text_box" id="text_box" maxlength="5000" style="text-align:left; font-size: 20px;" required></textarea>';
                echo '<div id="charCount"></div>';
            } else if ($row['submission_type'] == 'none') {
                echo '<hr><h3>Nothing to submit</h3>';
            } else {
            }
            echo '<hr><button type="submit" style="background-color:green; width:50%;">Submit</button>';
            echo '<br>';
            echo '<br>';
            echo '</form>';
            echo '</div>';
            
        }
        $pdo = null;
        ?>
    </div>
</body>
<script>
    function validatePDF(file) {
        const fileSize = file.files[0].size / 1024 / 1024; // in MiB
        if(fileSize > 16){
            alert('File too big, must be less than 16 MB');
            $(file).val(''); //for clearing with Jquery
        }
    }


const textarea = document.getElementById("text_box");
const charCount = document.getElementById("charCount");
const maxChars = 5000;

textarea.addEventListener("input", function() {
  const currentChars = textarea.value.length;
  charCount.textContent = `${currentChars}/${maxChars}`;
});

</script>
</html>