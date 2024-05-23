<?php
  session_start();
  if(!isset($_SESSION['user']) || !isset($_SESSION['school_id']) || (!isset($_SESSION['student_id']) && !isset($_SESSION['teacher_id']))){
    echo '<script>location.href = "../login.php";</script>';
}
include '../connection.php';

?>
<!DOCTYPE html>
<html>

<head>
<meta charset="utf-8">
    <title>Play SSM</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link href="https://netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">

    <!-- Bootstrap Date-Picker Plugin -->
    <link rel="stylesheet" href="../style.css" />
    <link rel="stylesheet" href="style.css" />

</head>

<body>
    <form action="upload_to_submissions.php" method="post">
    <?php
    if (isset($_POST['quizID']) && isset($_POST['assignment_test_id'])) {
        $quizID = htmlspecialchars($_POST['quizID']);
    } else {
      if(isset($_SESSION['teacher_id'])){
            header("location: ../teacher/specific_assignment.php");
      } else {
        header('location: ../student/student_homepage.php');
      }
    }

    $sql = "SELECT * FROM quiz_assignment INNER JOIN questions_and_answers ON quiz_assignment.quiz_assignment_id = questions_and_answers.quiz_assignment_id WHERE quiz_assignment.quiz_assignment_id = $quizID";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $ques = array();
    $ans1 = array();
    $ans2 = array();
    $ans3 = array();
    $ans4 = array();
    $correctans = array();
    $hints = array();
    $times = array();
    while ($row = $stmt->fetch()) {
        
        $title = $row['title'];
        $ques = json_decode($row['question']);
        $ans1 = json_decode($row['answer1']);
        $ans2 = json_decode($row['answer2']);
        $ans3 = json_decode($row['answer3']);
        $ans4 = json_decode($row['answer4']);
        $correctans = json_decode($row['correctanswer']);
        $hints = json_decode($row['hint']);
        $times = json_decode($row['time_per_question']);
        $shuffle_or_not = $row['shuffle'];
        $lifelines = json_decode($row['lifelines']);
        $points = $row['points'];
        $teacher_id = $row['teacher_id'];
        $pass_percentage = $row['pass_percentage'];
    }
    if($shuffle_or_not){
        $count = count($ques);
        $order = range(1, $count);
        shuffle($order);
        array_multisort($order, $ques, $ans1, $ans2, $ans3, $ans4, $correctans, $hints, $times);
    }
    for ($i=0; $i < count($correctans); $i++) { 
        if($correctans[$i] == 'one'){
            $correctans[$i] = $ans1[$i]; 
        } elseif ($correctans[$i] == 'two') {
            $correctans[$i] = $ans2[$i]; 
        } elseif ($correctans[$i] == 'three') {
            $correctans[$i] = $ans3[$i]; 
        } elseif ($correctans[$i] == 'four') {
            $correctans[$i] = $ans4[$i]; 
        }
    }
    echo '<div class="container" style="text-align:center;"> <u><h1>SUBJECT SAVVY MILLIONAIRE: ' . $title . '</h1></u>';
    $assignment_id = $_POST['assignment_test_id'];
    if(isset($_GET['type']) && $_SESSION['isStaff'] == 'yes'){
        if($_GET['type'] == 'preview'){
            echo '<h1>PREVIEW</h1>';
        }
    }
    echo '  
    <input type="hidden" id="points_on_completion" name = "points_on_completion" value = "' . $points . '">
    <input type="hidden" id="hiddenpasspercentage" name = "hiddenpasspercentage" value = "' . $pass_percentage . '">
    <input type="hidden" id="teacher_id" name = "teacher_id" value = "' . $teacher_id . '">
    <input type="hidden" id="hiddenquestion" name = "hiddenquestion" value = "' . implode('|', $ques) . '">
    <input type="hidden" id="hiddenanswer1" name = "hiddenanswer1" value = "' . implode('|', $ans1) . '">
            <input type="hidden" id="hiddenanswer2" name = "hiddenanswer2" value = "' . implode('|', $ans2) . '">
            <input type="hidden" id="hiddenanswer3" name = "hiddenanswer3" value = "' . implode('|', $ans3) . '">
            <input type="hidden" id="hiddenanswer4" name = "hiddenanswer4" value = "' . implode('|', $ans4) . '">
            <input type="hidden" id="hiddencorrectanswer" name = "hiddencorrectanswer" value = "' . implode('|', $correctans) . '">
            <input type="hidden" id="hiddenhint" name = "hiddenhint" value = "' . implode('|', $hints) . '">
            <input type="hidden" id="hiddentime" name = "hiddentime" value = "' . implode('|', $times) . '">
            <input type="hidden" id="assignment_id" name = "assignment_id" value = "' . $assignment_id . '">
            <input type="hidden" id="hiddenlifelines" name = "hiddenlifelines" value = "' . implode('|', $lifelines) . '">';
    $pdo = null;
    ?>
        <input type="hidden" id="assignmentid" name="assignmentid" value="<?php echo $_POST['assignment_test_id'] ?>">
        <input type="hidden" id="lifelines_used" name="lifelines_used" value="">
        <input type="hidden" id="questions_right" name="questions_right" value="">
        <input type="hidden" id="total_questions" name="total_questions" value="">

<div class="col-md-12">
            <div class="question-number">
                <h1>Quiz Question <span class="questNum"></span> of <span class="totQuest"></span></h1>
            </div>
            <p><b id="timer" style="font-size: 2em;"></b></p>

            <div class="row">
            <div class="col-md-4">

            <button type="button" style="display: none;" id="fiddyfiddy" onclick="fiftyfifty();">
              <img src="fiftyfifty.png" alt="fiftyfifty" style="width:100px; height:100px;"/>
            </button>
            </div>

            <div class="col-md-4">

            <button type="button" style="display: none;" id="hints" onclick="show_hint();">
              <img src="hint.png" alt="hint" style="width:100px; height:100px;"/>
            </button>
            </div>

            <div class="col-md-4">

            <button type="button" style="display: none;" id="check">
              <img src="life.png" alt="life" style="width:100px; height:100px;"/>
            </button>
            </div>
            </div>

            <br>
            <!-- This is where the generated question is put in -->
            <div class="question" style="background-color: white; padding: 20px; border: 5px solid black; font-weight: bold;">
            </div>
            <br>
            <div class="row">
            <div class="col-md-6">
                    <button type="button" id="a" onclick="next(this.id)" class="button_test">A)</button><br>
                </div>
                <div class="col-md-6">
                    <button type="button" id="b" onclick="next(this.id)" class="button_test">B)</button><br>
                </div>
            </div>
            <div class="row">
            <div class="col-md-6">
                <button type="button" id="c" onclick="next(this.id)" class="button_test">C)</button><br>
                </div>
                <div class="col-md-6">
                    <button type="button" id="d" onclick="next(this.id)" class="button_test">D)</button><br>
                </div>
            </div>
            <input type="text" id="hint_id" class="option5" value="" style="text-align:center; padding: 15px; font-size: 15px; display: none; margin-bottom: 15px;" readonly />
            <div><br></div>
            <div class="row">
            <div class="col-md-1">
            <button type="button" id="1" onclick="goBack(this.id)" class="hide">1</button>
            </div>
            <div class="col-md-1">
            <button type="button" id="2" onclick="goBack(this.id)" class="hide">2</button>
            </div>
            <div class="col-md-1">
            <button type="button" id="3" onclick="goBack(this.id)" class="hide">3</button>
            </div>
            <div class="col-md-1">
            <button type="button" id="4" onclick="goBack(this.id)" class="hide">4</button>
            </div>
            <div class="col-md-1">
            <button type="button" id="5" onclick="goBack(this.id)" class="hide">5</button>
            </div>
            <div class="col-md-1">
            <button type="button" id="6" onclick="goBack(this.id)" class="hide">6</button>
            </div>
            <div class="col-md-1">
            <button type="button" id="7" onclick="goBack(this.id)" class="hide">7</button>
            </div>
            <div class="col-md-1">
            <button type="button" id="8" onclick="goBack(this.id)" class="hide">8</button>
            </div>
            <div class="col-md-1">
            <button type="button" id="9" onclick="goBack(this.id)" class="hide">9</button>
            </div>
            <div class="col-md-1">
            <button type="button" id="10" onclick="goBack(this.id)" class="hide">10</button>
            </div>
            <div class="col-md-1">
            <button type="button" id="11" onclick="goBack(this.id)" class="hide">11</button>
            </div>
            <div class="col-md-1">
            <button type="button" id="12" onclick="goBack(this.id)" class="hide">12</button>
            </div>
            </div>
            <br>
            <div class="row">
            <br>
            <br>

            <div class="centerbutton">

              <div class="center">
              <button type="button" id="current" onclick="goBack(this.id)" class="hide">Current Question</button>
              <br>
              <br>
              </div>
            </div>
            </div>
            <br>
            <div id="result" style="display: none">
                <h1>You got <span id="result_score"></span> Correct</h1>
                <h2 id="pointer"></h2>  
                <br>
                <?php
                    if(isset($_GET['type']) && $_SESSION['isStaff'] == 'yes'){
                        if($_GET['type'] == 'preview'){
                            $explodedURL = explode('?', $_POST['url']);
                            echo '<a href= ../teacher/specific_assignment.php?'. $explodedURL[1] .'><input type="button" name="backbtn" id="button6activated" class="button" value="Go Back"></a><br><br>';
                        }
                    } else {
                        echo '<div class="row">
                        <div class="col-md-12">
                        <button type="submit" class="btn">Submit assignment</button></a>
                        </div>
                        </div>';
                    }  
                          
                ?>
        </div>
            </div>
        </div>
        </form>
        <script src="script.js"></script>
</body>

</html>