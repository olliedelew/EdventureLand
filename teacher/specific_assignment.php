<?php
session_start();
if(!isset($_SESSION['user']) || !isset($_SESSION['school_id']) || !isset($_SESSION['teacher_id'])){
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
    <title>Specific Assignment</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
</head>
<style>
    textarea {
        resize: none;
    }
    .form-control{
        width: 50%;
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
        border: 5px solid black;

    }
    @keyframes zoomy {
            0% {
                transform: scale(0.5, 0.5);
            }

            100% {
                transform: scale(1, 1);
            }
        } 

        /* Modal Content */
        .modal-content-test {
            top: 30%;
            position: relative;
            background-color: #fefefe;
            margin: auto;
            padding: 0;
            border: 5px solid black;
            width: 30%;
            animation-name: zoomy;
            animation-duration: 0.5s
        }

    #sub{
        background-color: green;
    }
    #del{
        background-color: red;
    }
    .boxy{
        border: 5px solid black;
        background-color: white;
    }
    #preview{
        background-color: lightgreen;
    }
</style>
<link rel="stylesheet" href="../style.css" />

<body>
    <div class="container" style="text-align:center;">
    <h1 style="text-align:left;">View Specific Assignment</h1>
    <div class="row" style="background-color:white;">
          <div class="col-sm-3">
              <br>
              <div class="card">
                  <div class="card-body">

                      <button type="button" class="button" onclick="location.href='teacher_profile.php'">Homepage</button><br><br>
                  </div>
              </div>
          </div>
          <div class="col-sm-3">
              <br>
              <div class="card">
                  <div class="card-body">
                      <button type="button" class="button" onclick="location.href='groups.php'">Student Groups</button><br><br> <!-- Student groupmates -->
                  </div>
              </div>
          </div>
          <div class="col-sm-3">
              <br>
              <div class="card">
                  <div class="card-body">
                      <button type="button" class="button" id="activated" onclick="location.href='assset.php'">Assignments Set</button><br><br>
                  </div>
              </div>
          </div>
          <div class="col-sm-3">
              <br>
              <div class="card">
                  <div class="card-body">
                      <button type="button" class="button" onclick="location.href='set_assignment.php'">Set Assignments</button><br><br>
                  </div>
              </div>
          </div>

      </div>

<?php
        if(!isset($_POST['assignment_id']) || !isset($_POST['group_id']) || !isset($_POST['assignment_type']) || !isset($_POST['actual_assignment_id'])){
            if(!isset($_SESSION['POST']['assignment_id']) || !isset($_SESSION['POST']['group_id']) || !isset($_SESSION['POST']['assignment_type']) || !isset($_SESSION['POST']['actual_assignment_id'])){
                $pdo = null;
                header('location: assset.php');
            } else {
                $aid = $_SESSION['POST']['assignment_id'];
                $actual_aid = $_SESSION['POST']['actual_assignment_id'];
                $group_id = $_SESSION['POST']['group_id'];            
        
                $type = $_SESSION['POST']['assignment_type'];  
            }
        } else {
            $aid = $_POST['assignment_id'];
            $actual_aid = $_POST['actual_assignment_id'];
            $group_id = $_POST['group_id'];            
    
            $type = $_POST['assignment_type'];  
            
            $_SESSION['POST'] = $_POST;
        }
        
        if($type == 'manual'){
            $sql1 = "SELECT student_id FROM manual_submission WHERE assignment_id = :aid AND assignment_done = 1";
            $sql2 = "SELECT student_id FROM manual_submission WHERE assignment_id = :aid AND assignment_done = 0";
        } else if($type == 'math'){
            $sql1 = "SELECT student_id FROM math_submission WHERE assignment_id = :aid AND assignment_done = 1";
            $sql2 = "SELECT student_id FROM math_submission WHERE assignment_id = :aid AND assignment_done = 0";
        } else if($type == 'quiz'){
            $sql1 = "SELECT student_id FROM quiz_submission WHERE assignment_id = :aid AND assignment_done = 1";
            $sql2 = "SELECT student_id FROM quiz_submission WHERE assignment_id = :aid AND assignment_done = 0";
        }
        $stmt = $pdo->prepare($sql1);
        $stmt->execute([
            'aid' => $aid
        ]);

        $done_ids = [];
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        while($row = $stmt->fetch()){
            array_push($done_ids, $row['student_id']);
        }

        $sql = "SELECT * FROM student WHERE student_id = :student_id"; //Order by???

        $stmt = $pdo->prepare($sql);
        echo '<div class=col-md-6>';
        echo '<h1><u>Completed</u></h1>';
        if(count($done_ids) == 0){
            echo '<div>';
            echo '<h2>No students have completed the task</h2>';
        } else {
            echo '<div class="boxy">';
        }
        foreach ($done_ids as $id) {
            $stmt->execute([
                'student_id' => $id
            ]);  

            $stmt->setFetchMode(PDO::FETCH_ASSOC);

            while($row = $stmt->fetch()){
                $name = str_replace(' ', '_', $row['name']);
                echo '<a href="specific_assignment.php?sid=' . $id . '&name=' . $name . '">' . $row['name'] . '</a><br>';
            }

        }
        echo '</div>';
        echo '<br>';
        echo '</div>';
        $stmt = $pdo->prepare($sql2);
        $stmt->execute([
            'aid' => $aid
        ]);

        $not_done_ids = [];
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        while($row = $stmt->fetch()){
            array_push($not_done_ids, $row['student_id']);
        }

        $sql = "SELECT * FROM student WHERE student_id = :student_id"; //Order by???
        $stmt = $pdo->prepare($sql);
        echo '<div class=col-md-6>';
        echo '<h1><u>Incomplete</u></h1>';
        if(count($not_done_ids) == 0){
            echo '<div>';
            echo '<h2>All students have completed the task</h2>';
        } else {
        echo '<div class="boxy">';
        }
        foreach ($not_done_ids as $id) {
            $stmt->execute([
                'student_id' => $id
            ]);  

            $stmt->setFetchMode(PDO::FETCH_ASSOC);

            while($row = $stmt->fetch()){
                $name = str_replace(' ', '_', $row['name']);
                echo '<a href=specific_assignment.php?sid=' . $id . '&name=' . $name . '>' . $row['name'] . '</a><br>';
            }

        }
        echo '</div>';
        echo '<br>';
        echo '</div>';
          
        echo '<table class="table table-bordered">
        <thead>
          <tr>
            <th scope="col"></th>
            <th scope="col">Assigned</th>
            <th scope="col">Completed</th>
            <th scope="col">Incomplete</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <th scope="row">#</th>
            <td>' . count($done_ids) + count($not_done_ids) . '</td>
            <td>' . count($done_ids) . '</td>
            <td>' . count($not_done_ids) . '</td>
          </tr>
        </tbody>
      </table>
      ';
        echo '<hr>';
        if($type == 'manual'){
            $sql3 = "SELECT * FROM manual_assignment WHERE manual_assignment_id = :aid"; // INNER JOIN WITH THE SPECFIC ASSIGNMENT THINGY
        } elseif($type == 'quiz'){
            $sql3 = "SELECT * FROM quiz_assignment WHERE quiz_assignment_id = :aid"; // INNER JOIN WITH THE SPECFIC ASSIGNMENT THINGY
        } elseif($type == 'math'){
            $sql3 = "SELECT * FROM math_assignment WHERE math_assignment_id = :aid"; // INNER JOIN WITH THE SPECFIC ASSIGNMENT THINGY
        }
        $stmt = $pdo->prepare($sql3);
        $stmt->execute([
            'aid' => $actual_aid
        ]);

        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        echo '<div class="col-md-6 boxy">';
        while($row = $stmt->fetch()){
            if($type == 'manual'){
                $datetime = explode(" ", $row['test_datetime']);
                $date = date("d-m-Y", strtotime($datetime[0]) );
                $time = date("H:i", strtotime($datetime[1]) );
            
                    echo '<h1><u>Information On Assignment</u></h1>';
                    echo '<h2> Title: ' . $row['title'] . '</h2>';
                    echo '<hr>';
                    echo '<h3>Description</h3>';
                    echo '<p style="text-align:left;">'. $row['description'] .'</p>';
                    echo '<hr>';
                    echo '<h3>Maximum Points On Completion</h3>';
                    $points = $row['points'];
                    echo $row['points'];
                    echo '<hr>';
                    echo '<h3>Due Date</h3>';
                    echo $date;
                    echo '<hr>';
                    echo '<h3>Time</h3>';
                    echo $time;
                    echo '<hr>';
                    echo '<h3>Prior Reading</h3>';
                
                
                    if ($row['prior_reading'] == 'text_box'){
                        echo '<p style="text-align:left;">'. $row['text_box'] .'</p>';
                    } else if ($row['prior_reading'] == 'ytlink'){
                        $ytlink = explode("v=", $row['ytlink']);
                        echo '<iframe width="420" height="345" src="https://www.youtube.com/embed/' . $ytlink[1] . '"></iframe>';
                    } else if ($row['prior_reading'] == 'link'){
                        echo '<a href="' . $row['link'] . '">' . $row['link'] . '</a>';
                    } else if ($row['prior_reading'] == 'none'){
                        echo 'No Prior Reading';
                    } else if($row['prior_reading'] == 'pdf'){
                            echo '<form action="show_prior_reading.php" method="post">
                            <input type="hidden" name="id" value="'. $row['manual_assignment_id'] .'">
                            <input type="hidden" name="type" value="manual">
                            <button type="submit">Click here to download PDF</button>    
                            </form>';
                    }
                    echo '<hr><h3>Submission type</h3>';
                    if($row['submission_type'] == 'pdf'){
                        echo 'Submission is a PDF';
                    } elseif($row['submission_type'] == 'text'){
                        echo 'Submission is a piece of text';
                    } elseif($row['submission_type'] == 'none'){
                        echo 'No submission required';
                    }
                    echo '<hr>';
                    echo '<br>';
                    echo '<div class="row">';
                    echo '<div class="col-md-6">';
                    echo '<form action="update_manual.php" method="post">';
                    echo '<input type="hidden" name="manual_id" value="' . $actual_aid . '">';
                    echo '<input type="hidden" name="url" value="' . $_SERVER['REQUEST_URI'] . '">';
                    echo '<button class="submit" id="sub">Update</button>';
                    echo '</form>';
                    echo '</div>';
                    echo '<div class="col-md-6">';
            
                    echo '<form action="deleteManualSpecific.php" method="post">';
                    echo '<input type="hidden" name="actual_assignment_id" value="' . $actual_aid . '">';
                    echo '<input type="hidden" name="assignment_id" value="' . $aid . '">';
                    echo '<a href="#" class="myBtn">
                    <button type="button" id="del">Delete</button>
                    </a>
                    <div id="myModal" class="modal">
                        <div class="modal-content-test">
                            <div class="modal-header">
                                <span class="close">&times;</span>
                                <h2>Are you sure you want to delete this assignment?</h2>
                            </div>
                            <div class="modal-body">
                            <button class="submit" id="del">Delete</button>
                            </div>
                        </div>
                    </div>';
                echo '</form>';
                    echo '</div>';
                    echo '</div>';
                    echo '<br>';
    
            } elseif($type == 'quiz'){
                $datetime = explode(" ", $row['test_datetime']);
                $date = date("d-m-Y", strtotime($datetime[0]) );
                $time = date("H:i", strtotime($datetime[1]) );
                    echo '<h1><u>SSM Game Information</u></h1>';
                    echo '<h2> Title: ' . $row['title'] . '</h2>';
                    echo '<hr>';
                    if(!empty($row['description'])){
                        echo '<h3>Description</h3>';
                        echo '<p style="text-align:left;">'. $row['description'].'</p>'; //'<p>'. .'</p>'
                        echo '<hr>';
                    }                
                    echo '<h3>Maximum Points On Completion</h3>';
                    echo $row['points'];
                    echo '<hr>';
                    echo '<h3>Due Date</h3>';
                    echo $date;
                    echo '<hr>';
                    echo '<h3>Time</h3>';
                    echo $time;
                    echo '<hr>';
                    echo '<h3>Prior Reading</h3>';
                    if ($row['prior_reading'] == 'text_box'){
                        echo '<p style="text-align:left;">'. $row['text_box'] .'</p>';
                    } else if ($row['prior_reading'] == 'ytlink'){
                        $ytlink = explode("v=", $row['ytlink']);
                        echo '<iframe width="420" height="345" src="https://www.youtube.com/embed/' . $ytlink[1] . '"></iframe>';
                    } else if ($row['prior_reading'] == 'link'){
                        echo '<h3><a href="' . $row['link'] . '">' . $row['link'] . '</a></h3>';
                    } else if ($row['prior_reading'] == 'none'){
                        echo 'No prior reading';
                    } else if($row['prior_reading'] == 'pdf'){
                            echo '<form action="show_prior_reading.php" method="post">
                            <input type="hidden" name="id" value="'. $row['quiz_assignment_id'] .'">
                            <input type="hidden" name="type" value="quiz">
                            <button type="submit">Click here to download PDF</button>        
                            </form>';
                    }
                    echo '<hr>';
                    echo '<div class="row">';
                    echo '<div class="col-md-6">';
                    echo '<form action="updateSpecific.php" method="post">';
                    echo '<input type="hidden" name="quizID" value="' . $actual_aid . '">';
                    echo '<input type="hidden" name="url" value="' . $_SERVER['REQUEST_URI'] . '">';
                    echo '<button class="submit" id="sub">Update</button>';
                    echo '</form>';
                    echo '</div>';
                    echo '<div class="col-md-6">';
                    echo '<form action="deleteSpecificQuiz.php" method="post">';
                    echo '<input type="hidden" name="actual_assignment_id" value="' . $actual_aid . '">';
                    echo '<input type="hidden" name="assignment_id" value="' . $aid . '">';
                echo '<a href="#" class="myBtn">
                    <button type="button" id="del">Delete</button>
                    </a>
                    <div id="myModal" class="modal">
                        <div class="modal-content-test">
                            <div class="modal-header">
                                <span class="close">&times;</span>
                                <h2>Are you sure you want to delete this assignment?</h2>
                            </div>
                            <div class="modal-body">
                            <button class="submit" id="del">Delete</button>
                            </div>
                        </div>
                    </div>';
                echo '</form>';
                    echo '</div>';
                    echo '</div>';
                    echo '<br>';
                    echo '<form action="../Specific_SSM/playSpecific.php?type=preview" method="post">';
                    echo '<input type="hidden" name="url" value="' . $_SERVER['REQUEST_URI'] . '">';
                    echo '<input type="hidden" name="quizID" value="' . $actual_aid . '">';
                    echo '<input type="hidden" name="assignment_test_id" value="' . $aid . '">';
                    echo '<button class="submit" id="preview">Preview Game</button>';
                    echo '</form>';
                    echo '<br>';
    
                } elseif($type == 'math'){
                    $datetime = explode(" ", $row['test_datetime']);
                    $date = date("d-m-Y", strtotime($datetime[0]) );
                    $time = date("H:i", strtotime($datetime[1]) );
                        echo '<h1><u>Information On Assignment</u></h1>';
                        echo '<h2> Title: ' . $row['title'] . '</h2>';
                        echo '<hr>';
                        echo '<h3>Description</h3>';
                        echo '<p style="text-align:left;">'. $row['description'] .'</p>';
                        echo '<hr>';
                        echo '<h3>Maximum Points On Completion</h3>';
                        echo $row['points'];
                        echo '<hr>';
                        echo '<h3>Due Date</h3>';
                        echo $date;
                        echo '<hr>';
                        echo '<h3>Time</h3>';
                        echo $time;
                        echo '<hr>';
                        echo '<h3>Difficulty</h3>';
                        echo $row['difficulty'];
                        echo '<hr>';
                        echo '<h3>Duration</h3>';
                        echo $row['duration'] . ' minutes';
                        echo '<hr>';
                        echo '<h3>Pass mark</h3>';
                        echo $row['pass_percentage'] . '%';
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
                        echo '<hr>';
                        echo '<div class="row">';
                        echo '<div class="col-md-6">';
                        echo '<form action="update_math.php" method="post">';
                        echo '<input type="hidden" name="math_id" value="' . $actual_aid . '">';
                        echo '<input type="hidden" name="url" value="' . $_SERVER['REQUEST_URI'] . '">';
                        echo '<button class="submit" id="sub">Update</button>';
                        echo '</form>';
                        echo '</div>';
                        echo '<div class="col-md-6">';
                
                        echo '<form action="deleteMathSpecific.php" method="post">';
                        echo '<input type="hidden" name="actual_assignment_id" value="' . $actual_aid . '">';
                        echo '<input type="hidden" name="assignment_id" value="' . $aid . '">';
                        echo '<a href="#" class="myBtn">
                        <button type="button" id="del">Delete</button>
                        </a>
                        <div id="myModal" class="modal">
                            <div class="modal-content-test">
                                <div class="modal-header">
                                    <span class="close">&times;</span>
                                    <h2>Are you sure you want to delete this assignment?</h2>
                                </div>
                                <div class="modal-body">
                                <button class="submit" id="del">Delete</button>
                                </div>
                            </div>
                        </div>';
                            echo '</form>';
                        echo '</div>';
                        echo '</div>';
                        echo '<br>';
                        echo '<form action="../Math_Game_Assignment/math_assignment_game.php?type=preview" method="post">';
                        echo '<input type="hidden" name="url" value="' . $_SERVER['REQUEST_URI'] . '">';
                        echo '<input type="hidden" name="assignmentID" value="' . $actual_aid . '">';
                        echo '<input type="hidden" name="assignment_test_id" value="' . $aid . '">';
                        echo '<button class="submit" id="preview">Preview Game</button>';
                        echo '</form>';
                        echo '<br>';
                    }
        }
        echo '</div>';
        echo '<div class="col-md-1">';
echo '</div>';
        if($type == 'manual'){
            $sql4 = "SELECT * FROM manual_submission WHERE assignment_id = :aid AND student_id = :student_id"; // INNER JOIN WITH THE SPECFIC ASSIGNMENT THINGY
        } elseif($type == 'quiz'){
            $sql4 = "SELECT * FROM quiz_submission WHERE assignment_id = :aid AND student_id = :student_id"; // INNER JOIN WITH THE SPECFIC ASSIGNMENT THINGY
        } elseif($type == 'math'){
            $sql4 = "SELECT * FROM math_submission WHERE assignment_id = :aid AND student_id = :student_id"; // INNER JOIN WITH THE SPECFIC ASSIGNMENT THINGY
        }
        $stmt = $pdo->prepare($sql4);
        $stmt->execute([
            'aid' => intval($aid),
            'student_id' => intval($_GET['sid'])
        ]);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        if(isset($_GET['sid'])){
            if(!(in_array($_GET['sid'], $done_ids) || in_array($_GET['sid'], $not_done_ids))){
                $pdo = null;
                echo '<script>location.href = "specific_assignment.php";</script>';
            }
        echo '<div class="col-md-5 boxy">';
        while($row = $stmt->fetch()){
            echo '<h1 style="text-align:center;"><u>Student Information</u></h1>';
            $name = str_replace('_', ' ', $_GET['name']);
            echo '<h2> Student: ' . $name . '</h2>';
            if($type == 'manual'){
                if($row['assignment_done'] == 1) {
                    echo '<h3>Assignment completed</h3>';
                    if($row['pdf_name'] != NULL){
                        echo '<h3>Submission:</h3>';
                        $submission_id = $row['manual_submission_id'];
                        echo '<form action="show_prior_reading.php" method="post">
                        <input type="hidden" name="id" value="'. $submission_id .'">
                        <input type="hidden" name="type" value="submission">
                        <button type="submit">Click here to download PDF</button>      
                        </form>';
                    } elseif($row['text_box'] != NULL){
                        echo '<h3>Submission:</h3>';
                        echo '<p style="text-align:left;">'. $row['text_box'] .'</p>';
                    }
                    if($row['result'] != NULL){
                        echo '<h3>Result given: ' . $row['result'] . '%</h3>';
                    } else {
                        echo '<h3>No result given yet <br> provide one in the box below:</h3>';
                        echo '<form action="upload_rating_for_manual.php" method="post">';
                        echo '<input type="hidden" name="submission_id" value="' . $row['manual_submission_id']  . '"/>';
                        echo '<input type="hidden" name="student_id" value="' . $_GET['sid'] . '"/>';
                        echo '<input type="hidden" name="points" value="' . $points . '"/>';
                        echo '<input type="number" name="rating" min=0 max=100 />%<br><br>';
                        $query = $_SERVER['QUERY_STRING'];
                        echo '<input type="hidden" name="currenturl" value="' . $query . '"/>';
                        echo '<button type="submit">Upload Rating</button>';
                        echo '<br><br>';
                        echo '</form>';
                    }
                } else {
                    echo '<h3>Assignment incomplete</h3>';
                }
            
            } elseif($type == 'quiz'){
                if($row['assignment_done'] == 1) {
                    echo '<h3>Assignment completed</h3>';
                } else {
                    echo '<h3>Assignment incomplete</h3>';
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
                echo round(($row['questions_right'] / $row['questions_total']) * 100) . '%';
                echo '<hr>';
                echo '<h3>Lifelines used</h3>';
                $lifelines = json_decode($row['lifelines_used']);
                for ($i=0; $i < count($lifelines); $i++) { 
                    if($lifelines[$i] == 'check'){
                        $lifelines[$i] = 'Check';
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
                echo '<br>';
            } elseif($type == 'math'){
                if($row['assignment_done'] == 1) {
                    echo '<h3>Assignment completed</h3>';
                } else {
                    echo '<h3>Assignment incomplete</h3>';
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
                if($row['result'] == 0){
                } else {
                    echo $row['result'];
                }
                echo '<hr>';
                echo '<h3>Points</h3>';
                echo $row['points'];            
            }
        }
        echo '</div>';
    }
    $pdo = null;
?>
</body> 
<script>
        var modal = document.getElementsByClassName('modal');
    // Get the button that opens the modal
    var btn = document.getElementsByClassName("myBtn");


    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close");

    // When the user clicks the button, open the modal 
    for (let index = 0; index < modal.length; index++) {
        const element = modal[index];
        btn[index].onclick = function() {
            modal[index].style.display = "block";
        }
        span[index].onclick = function() {
            modal[index].style.display = "none";
        }       
    }
    window.onclick = function(event) {
        for (let index = 0; index < modal.length; index++) {
            if (event.target == modal[index]) {
                modal[index].style.display = "none";
            }
        }
    }

</script>
</html>