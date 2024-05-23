<?php
session_start();
if(!isset($_SESSION['user']) || !isset($_SESSION['school_id']) || !isset($_SESSION['teacher_id'])){
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
	<title>Set Assignment</title>
      <link rel="stylesheet" href="../style.css" />

</head>
<body>
<div class="container" style="text-align:left;">


<h1>Set Assignments</h1>
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
                        <button type="button" class="button" onclick="location.href='assset.php'">Assignments Set</button><br><br>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <br>
                <div class="card">
                    <div class="card-body">
                        <button type="button" class="button" id="activated">Set Assignments</button><br><br>
                    </div>
                </div>
            </div>

        </div>
  <div class="row">
  <div class="card">
      <div class="card-body">
        <?php
            $sql = "SELECT student_group_id FROM teacher_student_group WHERE teacher_id = :teacher_id";
            $teachID = $_SESSION['teacher_id'];
    
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'teacher_id' => $teachID
            ]);  

            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $ids = array();
            $county = 0;
            while($row = $stmt->fetch()){
                $county++;
                array_push($ids, $row['student_group_id']);
            }
            if(count($ids) == 0){
                echo '<h3>Create student groups to access this feature</h3>';
                echo '  <div class="col-md-3">
                </div>
                <div class="col-sm-6">';
            } else {
                echo '<h1>Which student group would you like to create an assignment for?</h1><br>';
                echo '  <div class="col-md-3">
                </div>
                <div class="col-sm-6">';
                $sql = "SELECT * FROM student_group WHERE student_group_id = :student_group_id";

                $stmt = $pdo->prepare($sql);
                foreach ($ids as $id) {
                    $stmt->execute([
                        'student_group_id' => $id
                    ]);  
        
                    $stmt->setFetchMode(PDO::FETCH_ASSOC);
                    while($row = $stmt->fetch()){
                            echo '<form action="assign_game.php" method="post">';
                            echo '<input type="submit" name="' .  $row["name"]. '" id="otherbtn" class="button" value="'. $row["name"].'"></a><br><br>';
                            echo '<input type="hidden" name="group_ID" value="' . $row['student_group_id']. '">';
                            echo '</form>';
                    }
        
                  }    
            }
              $pdo = null;
              

        ?>
    </div>
    </div>
  </div>
  <div class="col-md-3">
            </div>
  </div>
</div></body>
</html>