
<?php
session_start();
if(!isset($_SESSION['user']) || !isset($_SESSION['school_id']) || !isset($_SESSION['student_id'])){
    echo '<script>location.href = "../login.php";</script>';
}
include '../connection.php';

?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
	<title>Assignments</title>

      <link rel="stylesheet" href="../style.css" />

</head>
<body>
<div class="container" style="text-align:left;">
<?php 
date_default_timezone_set('Europe/London');
$tn = false;
function genAss($type, $subject_assignments, $subject_counter, $tn, $pdo){
    $gid = $_GET['group_ID'];
    $math_count = 0;
    $quiz_count = 0;
    $custom_count = 0;
    $pdo2 = $pdo;
    foreach ($subject_assignments as $assignments) {

    if($type == 'math'){
        $sql3 = "SELECT * FROM math_assignment INNER JOIN teacher ON math_assignment.teacher_id = teacher.Teacher_id WHERE math_assignment.math_assignment_id = :math_assignment_id AND test_datetime >= NOW()";
        $stmt3 = $pdo2->prepare($sql3);
        $stmt3->execute([
            'math_assignment_id' => $assignments[1]
        ]);  
    } elseif ($type == 'quiz') {
        $sql3 = "SELECT * FROM quiz_assignment INNER JOIN teacher ON quiz_assignment.teacher_id = teacher.Teacher_id WHERE quiz_assignment.quiz_assignment_id = :quiz_assignment_id AND test_datetime >= NOW()";
        $stmt3 = $pdo2->prepare($sql3);
        $stmt3->execute([
            'quiz_assignment_id' => $assignments[1]
        ]);  
    } elseif ($type == 'custom'){
        $sql3 = "SELECT * FROM manual_assignment INNER JOIN teacher ON manual_assignment.teacher_id = teacher.Teacher_id WHERE manual_assignment.manual_assignment_id = :manual_assignment_id AND test_datetime >= NOW()";
        $stmt3 = $pdo2->prepare($sql3);
        $stmt3->execute([
            'manual_assignment_id' => $assignments[1]
        ]);  
    }
    $stmt3->setFetchMode(PDO::FETCH_ASSOC);
    while($row3 = $stmt3->fetch()){
        $datetime = explode(" ", $row3['test_datetime']);
        $date = date("d-m-Y", strtotime($datetime[0]));
        $date_long = date('l jS F Y', strtotime($datetime[0])); //Thursday 9th February 2023
        $time = date('H:i', strtotime($datetime[1])); //06:00:00 pm
        $time = $time . ':00';
        $due_date_full = $date . ' ' . $time;
            if($subject_counter == 0){
                if($tn == false){
                    echo '<h2 style="text-align: center;"><u>Teacher: ' . $row3['name'] .'</u></h2>';
                }
                if($type == 'math'){
                    echo '<h2 style="text-align: center;"><u>Formula Frenzy Assignments</u></h2><br>';
                } elseif($type == 'quiz'){
                    echo '<h2 style="text-align: center;"><u>Subject Savvy Millionaire Assignments</u></h2><br>';
                } elseif($type == 'custom'){
                    echo '<h2 style="text-align: center;"><u>Custom Assignments</u></h2><br>';
                }
                $subject_counter += 1;

            }
        if($type == 'custom'){
            echo '<form action="manual_submission.php" method="post">';

          echo '<div class="row">';
          echo '<div class="col-md-6">';                 
          echo '<input type="hidden" name="assignment_id" value="'. $row3['manual_assignment_id'] .'">';
          echo '<input type="hidden" name="assignment_testid" value="'. $subject_assignments[$custom_count][0] .'">';

          echo '<button class="submit man-btn">' . $row3['title'] . '</button>';
          $custom_count += 1;

        } elseif($type == 'math'){
            echo '<form action="math_prior.php" method="post">';
          echo '<div class="row">';
          echo '<div class="col-md-6">';              
          echo '<input type="hidden" name="assignment_id" value="'. $row3['math_assignment_id'] .'">';
          echo '<input type="hidden" name="assignment_testid" value="'. $subject_assignments[$math_count][0] .'">';
          echo '<button class="submit math-btn">' . $row3['title'] . '</button>';
          $math_count += 1;

        } elseif($type == 'quiz'){
            echo '<form action="prior_reading.php" method="post">';
          echo '<div class="row">';
          echo '<div class="col-md-6">';      
            echo '<input type="hidden" name="assignment_id" value="'. $row3['quiz_assignment_id'] .'">';
          echo '<input type="hidden" name="assignment_testid" value="'. $subject_assignments[$quiz_count][0] .'">';
          echo '<button class="submit ssm-btn">' . $row3['title'] . '</button>';    
          $quiz_count += 1;
        }
        echo '</form>';
        echo '</div>';
        echo '<div class="col-md-6">';   
        echo '<h4>Due: ' . $date_long . '</h4>';       
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
        echo '</div>';
        echo '</div>';
        echo '<br>';
    }
}
return $subject_counter;

}
    ?>
        <h1>Assignments Due</h1>
        <div class="row" style="background-color:white;">
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
                        <button type="button" class="button"  id="activated">Assignments</button><br><br>
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

    
      <br>
    <div class="row">
        <div class="col-sm-6">
          <div class="card">
            <div class="card-body">
              <?php
        $sql = "SELECT * FROM Student_student_group WHERE student_id = :student_id"; //WHERE teacher_id = $_SESSION[isStaff] then get student_id where it is the same as group id
        $student_id = $_SESSION['student_id'];

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'student_id' => $student_id
        ]);  

        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $ids = array();
        $county = 0;

        while($row = $stmt->fetch()){
            $county++;
            array_push($ids, $row['student_group_id']);
        }
        
        $sql = "SELECT * FROM student_group WHERE student_group_id = :student_group_id"; //Order by???
        $stmt = $pdo->prepare($sql);
        if(count($ids) != 0){
            // echo '<h1 style="text-align: center;">Create student groups and set assignments to access this feature</h1>';
            echo '<h1 style="text-align:center;"><u>Student Groups</u></h1><br>';  
            foreach ($ids as $id) {
                $stmt->execute([
                    'student_group_id' => $id
                ]);  

                $stmt->setFetchMode(PDO::FETCH_ASSOC);
                $groupID = $_GET['group_ID'];
                while($row = $stmt->fetch()){
                    if ($groupID == $id) {
                        echo '<a href= student_assignments.php?group_ID=' . $id . '><input type="button" name="' .  $row["name"] . '" id="button6activated" class="button" value="' . $row["name"] . '"></a><br><br>';
                    } else {
                        echo '<a href= student_assignments.php?group_ID=' . $id . '><input type="button" name="' .  $row["name"] . '" id="otherbtn" class="button" value="' . $row["name"] . '"></a><br><br>';
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
                if(isset($_GET['group_ID'])){
                    if(!in_array($_GET['group_ID'], $ids)){
                        $pdo = null;
                        header('location: student_assignments.php');
                    }
                $gid = $_GET['group_ID'];
                $sql2 = "SELECT * FROM assignment WHERE student_group_id = :student_group_id";
        
                $stmt2 = $pdo->prepare($sql2);
                $stmt2->execute([
                    'student_group_id' => $gid
                ]);  
                $counter = 0;
                $math_counter = 0;
                $quiz_counter = 0;
                $manual_counter = 0;
                $math_assignments = array();
                $quiz_assignments = array();
                $manual_assignments = array();
                $stmt2->setFetchMode(PDO::FETCH_ASSOC);
                while($row2 = $stmt2->fetch()){
                    if(isset($row2['math_id'])){
                        $counter += 1;
                        $sql4 = "SELECT * FROM math_submission  WHERE assignment_id = :assignment_id AND student_id = :student_id";
                        $student_id = $_SESSION['student_id'];
                        $stmt3 = $pdo->prepare($sql4);
                        $stmt3->execute([
                            'assignment_id' => $row2['assignment_id'],
                            'student_id'=> $student_id
                        ]);  
                        $stmt3->setFetchMode(PDO::FETCH_ASSOC);
                        while($row3 = $stmt3->fetch()){
                            if($row3['assignment_done'] == 0){
                                $math_id = $row2['math_id'];
                                array_push($math_assignments, [$row2['assignment_id'], $math_id]);        
                            }
                        }
                    }
                    else if(isset($row2['quiz_id'])){
                        $counter += 1;
                        $sql4 = "SELECT * FROM quiz_submission WHERE assignment_id = :assignment_id AND student_id = :student_id";
                        $student_id = $_SESSION['student_id'];

                        $stmt3 = $pdo->prepare($sql4);
                        $stmt3->execute([
                            'assignment_id' => $row2['assignment_id'],
                            'student_id'=> $student_id
                        ]);  
                        $stmt3->setFetchMode(PDO::FETCH_ASSOC);
                        while($row3 = $stmt3->fetch()){
                            if($row3['assignment_done'] == 0){
                                $quiz_assignment_id = $row2['quiz_id'];
                                array_push($quiz_assignments, [$row2['assignment_id'], $quiz_assignment_id]);
                            }
                        }
                    }
                    else if(isset($row2['manual_id'])){
                        $counter += 1;

                        $sql4 = "SELECT * FROM manual_submission WHERE assignment_id = :assignment_id AND student_id = :student_id";
                        $student_id = $_SESSION['student_id'];

                        $stmt3 = $pdo->prepare($sql4);
                        $stmt3->execute([
                            'assignment_id' => $row2['assignment_id'],
                            'student_id'=> $student_id
                        ]);  
                        $stmt3->setFetchMode(PDO::FETCH_ASSOC);
                        while($row3 = $stmt3->fetch()){
                            if($row3['assignment_done'] == 0){
                                $manual_id = $row2['manual_id'];
                                array_push($manual_assignments, [$row2['assignment_id'], $manual_id]);
                            }
                        }
                    }
            }
            if(!empty($math_assignments)){
                $math_counter = genAss('math', $math_assignments, $math_counte, $tn, $pdo);
                $tn = true;
            }
            if(!empty($quiz_assignments)){
                $quiz_counter = genAss('quiz', $quiz_assignments, $quiz_counter, $tn, $pdo);
                $tn = true;
            }
            if(!empty($manual_assignments)){
                $manual_counter = genAss('custom', $manual_assignments, $manual_counter, $tn, $pdo);
                $tn = true;
            }
        }

            if(!isset($_GET['group_ID'])){
                if($county == 0){
                    echo '<h1 style="text-align: center;">Access to this feature when you are in a student group</h1>';
                } else {
                    echo '<h1 style="text-align: center;">Please click a group to see your assignments</h1>';

                }
            } elseif($counter == 0 || ($math_counter == 0 && $quiz_counter == 0 && $manual_counter == 0)){
                echo '<h1 style="text-align: center;">No assignments to show</h1>';
            }

            $pdo = null;


                ?>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
    </div>
  </div>
  </div>
</div>
</body>
</html>