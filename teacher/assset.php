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
    <title>Assignments set</title>
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

    <!--  jQuery -->
    <script type="text/javascript" src="https://code.jquery.com/jquery-1.11.3.min.js"></script>

</head>
<link rel="stylesheet" href="../style.css" />
<body>
<div class="container" style="text-align:left;">
    <?php
    
  date_default_timezone_set('Europe/London');
    function genAss($time_period, $type, $pdo){
      if($type == 'custom'){
      if($time_period == 'future'){
        $sql = "SELECT * FROM assignment INNER JOIN  manual_assignment ON assignment.manual_id = manual_assignment.manual_assignment_id WHERE assignment.teacher_id = :teacher_id AND assignment.student_group_id = :student_group_id AND manual_assignment.test_datetime >= NOW() ORDER BY manual_assignment.test_datetime ASC";
        } else {
          $sql = "SELECT * FROM assignment INNER JOIN  manual_assignment ON assignment.manual_id = manual_assignment.manual_assignment_id WHERE assignment.teacher_id = :teacher_id AND assignment.student_group_id = :student_group_id AND manual_assignment.test_datetime < NOW() ORDER BY manual_assignment.test_datetime DESC";
        }
      } elseif($type == 'math'){
        if($time_period == 'future'){
          $sql = "SELECT * FROM assignment INNER JOIN  math_assignment ON assignment.math_id = math_assignment.math_assignment_id WHERE assignment.teacher_id = :teacher_id AND assignment.student_group_id = :student_group_id AND math_assignment.test_datetime >= NOW() ORDER BY math_assignment.test_datetime ASC";
        } else {
            $sql = "SELECT * FROM assignment INNER JOIN  math_assignment ON assignment.math_id = math_assignment.math_assignment_id WHERE assignment.teacher_id = :teacher_id AND assignment.student_group_id = :student_group_id AND math_assignment.test_datetime < NOW() ORDER BY math_assignment.test_datetime DESC";
          }
        } elseif($type == 'quiz'){
          if($time_period == 'future'){
            $sql = "SELECT * FROM assignment INNER JOIN  quiz_assignment ON assignment.quiz_id = quiz_assignment.quiz_assignment_id WHERE assignment.teacher_id = :teacher_id AND assignment.student_group_id = :student_group_id AND quiz_assignment.test_datetime >= NOW() ORDER BY quiz_assignment.test_datetime ASC";
        } else {
            $sql = "SELECT * FROM assignment INNER JOIN  quiz_assignment ON assignment.quiz_id = quiz_assignment.quiz_assignment_id WHERE assignment.teacher_id = :teacher_id AND assignment.student_group_id = :student_group_id AND quiz_assignment.test_datetime < NOW() ORDER BY quiz_assignment.test_datetime DESC";
        }
        }

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'teacher_id' => $_SESSION['teacher_id'],
            'student_group_id' => $_GET['group_ID']
        ]);

        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $counter = 0;
        while($row = $stmt->fetch()){
            if(!empty($row) and $counter == 0){
              if($type == 'custom'){
                echo '<h1 style="text-align: center;">Custom Assignments</h1>';
              } elseif($type == 'math'){
                echo '<h1 style="text-align: center;">Formula Frenzy Assignments</h1>';
              } elseif($type == 'quiz'){
                echo '<h1 style="text-align: center;">Subject Savvy Millionaire Assignments</h1>';
              }
            }
            $counter += 1;
            echo '<form action="specific_assignment.php" method="post">';
            echo '<input type="hidden" name="assignment_id" value="'. $row['assignment_id'] .'">';
            echo '<input type="hidden" name="group_id" value="' . $row['student_group_id'] .'">'; 

            if($type == 'custom'){
              echo '<div class="row">';
              echo '<div class="col-md-6">';                 
              echo '<input type="hidden" name="assignment_type" value="manual">';
              echo '<input type="hidden" name="actual_assignment_id" value="'. $row['manual_assignment_id'] .'">';
              echo '<button class="submit man-btn">' . $row['title'] . '</button>';
  
            } elseif($type == 'math'){
              echo '<div class="row">';
              echo '<div class="col-md-6">';              
              echo '<input type="hidden" name="assignment_type" value="math">';
              echo '<input type="hidden" name="actual_assignment_id" value="'. $row['math_assignment_id'] .'">';
              echo '<button class="submit math-btn">' . $row['title'] . '</button>';
  
            } elseif($type == 'quiz'){
              echo '<div class="row">';
              echo '<div class="col-md-6">';      
              echo '<input type="hidden" name="assignment_type" value="quiz">';
              echo '<input type="hidden" name="actual_assignment_id" value="'. $row['quiz_assignment_id'] .'">';
              echo '<button class="submit ssm-btn">' . $row['title'] . '</button>';  
            }
            
            echo '</div>';
            echo '<div class="col-md-6">';   
            $datetime = explode(" ", $row['test_datetime']);
            $date = date("d-m-Y", strtotime($datetime[0]));
            $date_long = date('l jS F Y', strtotime($datetime[0])); //Thursday 9th February 2023
            $time = date('H:i', strtotime($datetime[1])); //06:00:00 pm
            $time = $time . ':00';
            $due_date_full = $date . ' ' . $time;
            $due_date =strtotime($due_date_full);
            $current_date = strtotime(date("d-m-Y H:i:s"));
            if($time_period == 'past'){     
            echo '<h4>Was Due: <br>' . $date_long . '</h4>';   
            } else {
              echo '<h4>Due: ' . $date_long . '</h4>';   
            }
            if($time_period == 'future'){     
            $startDate = strtotime(date('d-m-Y', strtotime($date) ) );
            $currentDate = strtotime(date('d-m-Y'));
            $datediff = $startDate - $currentDate;
            $days_away = round($datediff / (60 * 60 * 24));
            $easy_to_read_time = date('h:i a', strtotime($time));
            if($easy_to_read_time[0] == '0'){
                $easy_to_read_time = substr($easy_to_read_time, 1);
            }
            if($days_away == 0){
                echo '<h4><b>DUE TODAY AT ' . $easy_to_read_time . '</b><br>';
                $due_time = strtotime($time);
                $current_time = strtotime(date('H:i:s'));
                $difference = $due_time - $current_time;
                $rounded_difference = round($difference / (60 * 60));
                if($rounded_difference > 1){
                    echo '(Around ' . $rounded_difference . ' Hours Away)</h4>';
                } elseif($rounded_difference == 1){
                    echo '(Around 1 Hour Away)</h4>';
                } elseif($rounded_difference == 0){
                    echo '(In Less Than 1 Hour)</h4>';
                }
            } elseif($days_away == 1) {
                echo '<h4>Due In ' . $days_away . ' Day At ' . $easy_to_read_time . '</h4>';
            } else {
                if($days_away > 31){
                  $months_away = round($days_away / 30);
                  if($months_away > 1){
                  echo '<h4>Due in ' . $months_away . ' Months At ' . $easy_to_read_time . '</h4>';
                  } else {
                    echo '<h4>Due in ' . $months_away . ' Month At ' . $easy_to_read_time . '</h4>';
                  }
                } else {
                  echo '<h4>Due in ' . $days_away . ' Days At ' . $easy_to_read_time . '</h4>';
                }
            }
          }
            echo '</div>';

            echo '</div>';
            echo '</form><br>';                    
        }
        return $counter;
    }
    ?>

    <h1>Assignments Set</h1>
    <div class="container" style="text-align:center;">
    <div class="row" style="background-color: white;">
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
                        <button type="button" class="button" id="activated">Assignments Set</button><br><br>
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
<br>
          <div class="row">
        <div class="col-sm-6">
          <div class="card">
            <div class="card-body">
              <input type="hidden" id="ingHidden" name="ingHidden" value="">
              <input type="hidden" id="hidden_list" name="hidden_list" value="">
              <?php
              $sql = "SELECT student_group_id FROM teacher_student_group WHERE teacher_id = :teacher_id";
              $teachID = $_SESSION['teacher_id'];

              $stmt = $pdo->prepare($sql);
              $stmt->execute([
                'teacher_id' => $teachID
              ]);

              $stmt->setFetchMode(PDO::FETCH_ASSOC);
              $ids = array();

              while ($row = $stmt->fetch()) {
                array_push($ids, $row['student_group_id']);
              }
              $sql = "SELECT * FROM student_group WHERE student_group_id = :student_group_id";

              $stmt = $pdo->prepare($sql);
              if(count($ids) == 0){
                echo '<h1 style="text-align: center;">Create student groups and set assignments to access this feature</h1>';
              } else {
                echo '<h1 style="text-align:center;"><u>Student Groups</u></h1><br>';  
                foreach ($ids as $id) {
                  $stmt->execute([
                    'student_group_id' => $id
                  ]);
  
                  $stmt->setFetchMode(PDO::FETCH_ASSOC);
                  $groupID = $_GET['group_ID'];
                  while ($row = $stmt->fetch()) {
                    if ($groupID == $row['student_group_id'] && in_array($_GET['group_ID'], $ids)) {
                      echo '<a href= assset.php?group_ID=' . $row['student_group_id'] . '&time_period=future><input type="button" name="' .  $row["name"] . '" id="button6activated" class="button" value="' . $row["name"] . '"></a><br><br>';
                    } else {
                      echo '<a href= assset.php?group_ID=' . $row['student_group_id'] . '&time_period=future><input type="button" name="' .  $row["name"] . '" id="otherbtn" class="button" value="' . $row["name"] . '"></a><br><br>';
                    }
                  }
                }
              }


              ?>
            </div>
          </div>
        </div>
        <div class="col-sm-6">
          <div class="card">
            <div class="card-body">
              <div class="card6">
              <?php
              $time_periods = array('past', 'future');

              if((isset($_GET['group_ID'])) && (isset($_GET['time_period']))){
                if(count($_GET) != 2){
                  $pdo = null;
                  echo '<script>location.href = "assset.php";</script>';
                }
                if(!in_array($_GET['group_ID'], $ids) || !in_array($_GET['time_period'], $time_periods)){
                  $pdo = null;
                  echo '<script>location.href = "assset.php";</script>';
                } else {      
                $time_period = $_GET['time_period'];
                $gid = $_GET['group_ID'];
                if($time_period != NULL){
                  echo '<div class="row">';
                  if ($time_period == 'past') {
                    echo '<div class="col-md-6">';                  
                    echo '<a href= assset.php?group_ID=' . $gid . '&time_period=past><input type="button" name="past" id="button6activated" class="button" value="Past Assignments"></a><br><br>';
                    echo '</div>';
                    echo '<div class="col-md-6">';                  
                    echo '<a href= assset.php?group_ID=' . $gid . '&time_period=future><input type="button" name="future" id="otherbtn" class="button" value="Upcoming Assignments"></a><br><br>';
                    echo '</div>';
                  } else {
                    echo '<div class="col-md-6">';                  
                    echo '<a href= assset.php?group_ID=' . $gid . '&time_period=past><input type="button" name="past" id="otherbtn" class="button" value="Past Assignments"></a><br><br>';
                    echo '</div>';
                    echo '<div class="col-md-6">';                  
                    echo '<a href= assset.php?group_ID=' . $gid . '&time_period=future><input type="button" name="future" id="button6activated" class="button" value="Upcoming Assignments"></a><br><br>';
                    echo '</div>';
                  }
                }
                echo '</div>';
                $main_counter = 0;

                $main_counter += genAss($time_period, 'custom', $pdo);           
                $main_counter += genAss($time_period, 'math', $pdo);           
                $main_counter += genAss($time_period, 'quiz', $pdo);           
                $pdo = null;
                if(!isset($_GET['group_ID'])){
                  if($main_counter == 0){
                    echo '<h1 style="text-align: center;">Create student groups and set assignments to access this feature</h1>';
                  } else {
                    echo '<h1 style="text-align: center;">Please click a group to view assignments you have set</h1>';
                  }
                } elseif ($main_counter == 0){
                  echo '<h1 style="text-align: center;">No Assignments Set</h1>';
                }
                }
        } elseif((isset($_GET['group_ID'])) && (!isset($_GET['time_period']))){
          $pdo = null;
          echo '<script>location.href = "assset.php";</script>';
        } elseif(!(isset($_GET['group_ID'])) && (isset($_GET['time_period']))){
          $pdo = null;
          echo '<script>location.href = "assset.php";</script>';
        }

                ?>
              </div>
            </div>
          </div>
          </div>
        </div>
      </div>

</div>
</body> 

</html>