<?php
session_start();
if(!isset($_SESSION['user']) || !isset($_SESSION['school_id']) || (!isset($_SESSION['student_id']) && !isset($_SESSION['teacher_id']))){
  echo '<script>location.href = "../login.php";</script>';
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Formula Frenzy</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <!-- Stylesheet -->
    <link rel="stylesheet" href="style.css" />
  </head>
  <body>
    <?php
    if(!isset($_POST['assignmentID']) || !isset($_POST['assignment_test_id'])){
      if($_SESSION['isStaff'] == 'yes'){
        header('location: ../teacher/assset.php');
      } else {
        header('location: ../student/student_assignments.php');
      }
    } else {
      $id = $_POST['assignmentID'];
      $a_test_id = $_POST['assignment_test_id'];
    }
    include '../connection.php';  
    $sql = "SELECT * FROM math_assignment WHERE math_assignment_id = :math_assignment_id"; //WHERE teacher_id = $_SESSION[isStaff] then get student_id where it is the same as group id

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'math_assignment_id' => $id
    ]);

    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    while ($row = $stmt->fetch()) {
      $operators = json_decode($row['operators']);
      $newop = join(", ", $operators); //done
      $teacher_id = $row['teacher_id'];
      $student_group_id = $row['student_group_id'];
      $title = $row['title']; //done
      $description = $row['description']; //done
      $points = $row['points']; //done
      $difficulty = $row['difficulty']; //done
      $duration = $row['duration']; //done
      $min_no_questions = $row['min_no_questions']; //done
      $pass_percentage = $row['pass_percentage']; //done
      echo '<input type="hidden" id = "title" name = "title" value = "' . $title . '">';
      if($_GET['type'] == 'preview' && $_SESSION['isStaff'] == 'yes'){
        echo '<input type="hidden" id = "preview" name = "preview" value = "true">';
      } else {
        echo '<input type="hidden" id = "preview" name = "preview" value = "false">';
      }
      echo '<input type="hidden" id = "operators" name = "operators" value = "' . $newop . '">';
      echo '<input type="hidden" id = "description" name = "description" value = "' . $description . '">';
      echo '<input type="hidden" id = "points" name = "points" value = "' . $points . '">';
      echo '<input type="hidden" id = "difficulty" name = "difficulty" value = "' . $difficulty . '">';
      echo '<input type="hidden" id = "duration" name = "duration" value = "' . $duration . '">';
      echo '<input type="hidden" id = "min_no_questions" name = "min_no_questions" value = "' . $min_no_questions . '">';
      echo '<input type="hidden" id = "pass_percentage" name = "pass_percentage" value = "' . $pass_percentage . '">';
      echo '  <div class="row">
                <h1 style="text-align:center;"><b><u>' . $title . '</u></b></h1>
                </div>';
    }
    $pdo = null;
    ?>
      <div class="col-md-3">
      <div class="row">
          <div class="box">
            <div id="points-words"><b>Points</b></div>
            <div id="points-test">0</div>
          </div>      
        </div>
        <div class="row">
          <div class="box">
          <div id="points-words"><b>Hints</b></div>
            <div id="hints-number">5</div>
          </div>      
        </div>
      </div>
    <div class="col-md-8">

    <div class="boxy">
      <?php
          if(isset($_GET['type'])){
            if($_GET['type'] == 'preview'){
                echo '<h1>PREVIEW</h1>';
            }
          }
      ?>
      <div class="row">
        <div class="col-md-4">
        </div>
        <div class="col-md-3">
        <h3 id="timer"><?php echo $duration . ':' . '00' ?></h3>
        </div>
        <div class="col-md-5">
        <button type="button" id="hints">Get A Hint</button>
        </div>
      </div>
      <div id="equation"></div>
      <button type="button" id="submit">Check Answer</button>
      <p id="error">Error</p>
    </div>
    </div>

    <div class="results-box">
      <!-- <p id="result"></p> -->
      <form action="upload_to_submissions.php" method="post">
      <input type="hidden" id="score_hidden" name="score_hidden" />
      <input type="hidden" id="passed_hidden" name="passed_hidden" />
      <input type="hidden" id="points_hidden" name="points_hidden" />
      <input type="hidden" id="duration_hidden" name="duration_hidden" value="<?php echo $duration ?>" />
      <input type="hidden" id="correct_ones" name="correct_ones" />
      <input type="hidden" id="incorrect_ones" name="incorrect_ones" />
      <input type="hidden" id="student_group_id" name="student_group_id" value="<?php echo $student_group_id ?>" />
      <input type="hidden" id="assignment_id" name="assignment_id" value="<?php echo $a_test_id ?>" />
      <input type="hidden" id = "points_on_pass" name = "points_on_pass" value="<?php echo $points ?>">
      <input type="hidden" id = "teacher_id" name = "teacher_id" value="<?php echo $teacher_id ?>">
      <h1 id="score">SCORE:  <span class="score_value"></span></h1>
      <br>
      <br>
      <?php 
    if(isset($_GET['type']) && $_SESSION['isStaff'] == 'yes'){
      if($_GET['type'] == 'preview'){
            $explodedURL = explode('?', $_POST['url']);
            echo '<div class="row">';
            echo '<div class="col-md-4">';
            echo '</div>';
            echo '<div class="col-md-4">';
            echo '<a href= ../teacher/specific_assignment.php?'. $explodedURL[1] .'>
            <button type="button" id="upload" name="backbtn" class="center">Back</button>
            </a>';
            echo '</div>';
            echo '<div class="col-md-4">';
            echo '</div>';
            echo '</div>';
          }
        } else {
          echo '
          <br><div class="row">
            <div class="col-md-12">
            <button id="upload" type="submit" class="center">Submit Assignment</button>
            </div>
          </div>';
        }
    ?>
      </form>
      <br>
      <br>
      <div id="reveal_results">
      <div class="col-md-4">
      </div>
      <div class="col-md-6">
        <div id="answers_presented" style="text-align:left;">
        </div>
      </div>
      <div class="col-md-2">
      </div>
    </div>
    </div>
    <script src="script.js"></script>
  </body>
</html>
