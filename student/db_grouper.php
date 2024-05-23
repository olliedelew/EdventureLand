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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
	<title>Discussion Boards</title>
    <link rel="stylesheet" href="../style.css" />
</head>
<body>
<div class="container" style="text-align:left;">
<h1>Discussion Board</h1>
<?php 
if(($_SESSION['isStaff'] == 'no')) //check if user is a user and display buttons
    {
    ?>
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
                        <button type="button" class="button" id="activated">Discussion Boards</button><br><br> <!-- Student groupmates -->
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
                        <button type="button" class="button" onclick="location.href='student_settings.php'">Account</button><br><br>
                    </div>
                </div>
                </div>
                </div>
            <?php
    } else if(($_SESSION['isStaff'] == 'yes')) //check if user is a user and display buttons
            {
            ?>
        
        <div class="row" style="background-color:white;">
            <div class="col-sm-3">
                <br>
                <div class="card">
                    <div class="card-body">

                        <button type="button" class="button"  id="activated" onclick="location.href='../teacher/teacher_profile.php'">Homepage</button><br><br>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <br>
                <div class="card">
                    <div class="card-body">
                        <button type="button" class="button" onclick="location.href='../teacher/groups.php'">Student Groups</button><br><br> <!-- Student groupmates -->
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <br>
                <div class="card">
                    <div class="card-body">
                        <button type="button" class="button" onclick="location.href='../teacher/assset.php'">Assignments Set</button><br><br>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <br>
                <div class="card">
                    <div class="card-body">
                        <button type="button" class="button" onclick="location.href='../teacher/set_assignment.php'">Set Assignments</button><br><br>
                    </div>
                </div>
            </div>

        </div>
            <?php
             }
            ?>
      <br>
    <div class="row">
        <div class="col-sm-6">
          <div class="card">
            <div class="card-body">
              <!-- php here to output all the sg's -->
              <?php
              if($_SESSION['isStaff'] == 'no'){
        $sql = "SELECT student_group_id FROM Student_student_group WHERE student_id = :student_id"; //WHERE teacher_id = $_SESSION[isStaff] then get student_id where it is the same as group id
        $student_id = $_SESSION['student_id'];

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'student_id' => $student_id
        ]);  
    } else {
        $sql = "SELECT student_group_id FROM teacher_student_group WHERE teacher_id = :teacher_id"; //WHERE teacher_id = $_SESSION[isStaff] then get student_id where it is the same as group id
        $teacher = $_SESSION['teacher_id'];

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'teacher_id' => $teacher
        ]);  

    }
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $ids = array();
        $county = 0;
        while($row = $stmt->fetch()){
            $county++;
            array_push($ids, $row['student_group_id']);
        }
        
        $sql = "SELECT * FROM student_group WHERE student_group_id = :student_group_id"; //Order by???
        if(count($ids) == 0){
            if($_SESSION['isStaff'] == 'yes'){
                echo '<h1>Create a student group to access this feature</h1>';
            } else {
                echo '<h1>Wait until you are assigned a student group to access this feature</h1>';
            }
        } else {
            $stmt = $pdo->prepare($sql);
            echo '<h1 style="text-align:center;"><u>Student Groups</u></h1><br>';
            foreach ($ids as $id) {
                $stmt->execute([
                    'student_group_id' => $id
                ]);  
    
                $stmt->setFetchMode(PDO::FETCH_ASSOC);
                $groupID = $_GET['group_ID'];
                while($row = $stmt->fetch()){
                    $counter++;
                    if ($groupID == $id) {
                        echo '<a href= db_grouper.php?group_ID=' . $id . '><input type="button" name="' .  $row["name"] . '" id="button6activated" class="button" value="' . $row["name"] . '"></a><br><br>';
                    } else {
                        echo '<a href= db_grouper.php?group_ID=' . $id . '><input type="button" name="' .  $row["name"] . '" id="otherbtn" class="button" value="' . $row["name"] . '"></a><br><br>';
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
                if (isset($_GET['group_ID'])) {
                    if(!in_array($_GET['group_ID'], $ids)){
                        $pdo = null;
                        header('location: db_grouper.php');
                    }
                    $gid = $_GET['group_ID'];
                
                $sql2 = "SELECT * FROM discussion_board WHERE student_group_id = :student_group_id AND reply_id IS NULL ORDER BY datetime DESC";
        
                $stmt2 = $pdo->prepare($sql2);
                $stmt2->execute([
                    'student_group_id' => $gid
                ]);  
                $counter = 0;
                $stmt2->setFetchMode(PDO::FETCH_ASSOC);
                while($row = $stmt2->fetch()){
                    if($counter == 0){
                        echo '<h1 style="text-align:center;">Discussions</h1>';
                    }
                    $counter += 1;
                    echo '<form action="db_grouper_discussion.php" method="post">
                    <input type="hidden" name="group_id" value="'. $gid .'">
                    
                    <input type="hidden" name="discussion_board" value="'. $row['discussion_board_id'] .'">
                    <input type="hidden" name="discussion_board_id" value="'. $row['discussion_board_id'] .'">
                    <input type="hidden" name="discussion_board" value="'. $row['discussion_board_id'] .'">
                    <button type="submit">' . $row['title'] . '</button>
                    </form><br>';
                }
                if(!isset($_GET['group_ID'])){
                    if($county == 0){
                        echo '<h1 style="text-align: center;">Access to this feature when you are in a student group</h1>';
                    } else {
                    echo '<h1 style="text-align: center;">Please click a group to see the discussion going on</h1>';
                    }
                } elseif($counter == 0){
                    echo '<h1 style="text-align: center;">No discussions to show</h1><br>';
                    echo '<form action="db_grouper_discussion_new.php" method="post">
                    <input type="hidden" name="group_id" value="'. $gid .'">
                    <button type="submit" style="background-color:green;">Write a new discussion topic</button>
                    </form>';
                } else {
                echo '<form action="db_grouper_discussion_new.php" method="post">
                <input type="hidden" name="group_id" value="'. $gid .'">
                <button type="submit" style="background-color:green;">Write a new discussion topic</button>
                </form>';
                }
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