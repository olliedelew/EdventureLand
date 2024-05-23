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
	<title>Group Leaderboards</title>
	<style type="text/css">
    #activated {
        background-color: #555;
    }
    .button {
        background-color: blue;
        color: white;
        font-size: 18px;
        padding: 14px 30px;
        width: 100%;
    }
    
    .button:hover {
        background-color: #555;
    }

    img {
        display: block;
        margin-left: 40px;
        margin-right: 40px;
        /* padding-left: 50px; */

    }
    img:hover{
      filter: brightness(50%);
    }

    .col-sm-3 {
        border: 5px solid black;
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
        border: 1px solid black;

    }

    .newest {
      border: 3px solid green;
    }
	</style>
    <link rel="stylesheet" href="../style.css" />

</head>
<body>
<div class="container" style="text-align:left;">
<br>
	<h1>Group Leaderboards</h1>
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
            <button type="button" class="button" onclick="location.href='db_grouper.php'">Discussion Boards</button><br><br>
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
            <button type="button" class="button" onclick="location.href='student_settings.php'" id="activated">Account</button><br><br>
        </div>
    </div>
  </div>
  </div>

  <br>

<?php
$sid = $_SESSION['student_id'];
$sql = "SELECT * FROM Student_student_group WHERE student_id = :student_id";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    'student_id' => $sid
]);
$stmt->setFetchMode(PDO::FETCH_ASSOC);
$ids = array();

while ($row = $stmt->fetch()) {
    array_push($ids, $row['student_group_id']);
}

$sql = "SELECT * FROM student_group WHERE student_group_id = :student_group_id"; //Order by???


$stmt = $pdo->prepare($sql);
echo '<div class="row">';
$counter = 0;
if(count($ids) == 0){
    echo '<h1>Please wait until you are assigned a student group to access this feature</h1>';
} else {
    foreach ($ids as $id) {
        $stmt->execute([
        'student_group_id' => $id
        ]);
    
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
    
        while ($row = $stmt->fetch()) {
        $counter += 1;
        if($counter % 5 == 0){
            echo '</div>';
            echo '<div class="row">';
        }
        echo '<div class="col-md-3">';
        if ($_GET['group_ID'] == $row['student_group_id']) {
            echo '<a href= group_leaderboards.php?group_ID=' . $id . '><input type="button" name="' .  $row["name"] . '" id="activated" class="button" value="' . $row["name"] . '"></a><br><br>';
        } else {
            echo '<a href= group_leaderboards.php?group_ID=' . $id . '><input type="button" name="' .  $row["name"] . '" class="button" value="' . $row["name"] . '"></a><br><br>';
        }
        echo '</div>';
        }
    }
    echo '</div>';  
}

if (isset($_GET['group_ID'])) {
    if(!in_array($_GET['group_ID'], $ids)){
        $pdo = null;
        header('location: group_leaderboards.php');
    }
    $group_id = $_GET['group_ID'];
    // get all student ids from that group, find their points in the teacher_student table
    $sql = "SELECT * FROM Student_student_group INNER JOIN teacher_student_group ON Student_student_group.student_group_id = teacher_student_group.student_group_id WHERE Student_student_group.student_group_id = :student_group_id"; //WHERE teacher_id = $_SESSION[isStaff] then get student_id where it is the same as group id
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'student_group_id' => $group_id
    ]);
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $student_ids = array();
    $teacher_id = NULL;
    
    while ($row = $stmt->fetch()) {
        array_push($student_ids, $row['student_id']);
        $teacher_id = $row['teacher_id'];
    }
    
    $sql = "SELECT * FROM teacher_student INNER JOIN student ON teacher_student.student_id = student.student_id WHERE teacher_student.student_id = :student_id AND teacher_student.teacher_id = :teacher_id"; //Order by???
    
    
    $stmt = $pdo->prepare($sql);
    $student_points = array();
    foreach ($student_ids as $id) {
        $stmt->execute([
        'student_id' => $id,
        'teacher_id' => $teacher_id
        ]);

    
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
    
        while ($row = $stmt->fetch()) {
            array_push($student_points, [$row['student_id'], $row['name'], $row['points']]);
        }
    }
    function sortByOrder($a, $b) {
        return $b['2'] - $a['2'];
    }
    usort($student_points, 'sortByOrder');
    $sql = "SELECT * FROM teacher WHERE teacher_id = :teacher_id"; //Order by???
    
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'teacher_id' => $teacher_id
    ]);
    $stmt->setFetchMode(PDO::FETCH_ASSOC);

    while ($row = $stmt->fetch()) {
        echo '<h2>Teacher: ' . $row['name'] . '</h2>';
    }
    echo '<table class="table table-bordered">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Points</th>
              </tr>
            </thead>
            <tbody>';
    $counter = 0;
    foreach ($student_points as $students) {
        $counter += 1;
        if($students[0] == $sid){
        echo '<tr class="newest">
        <td>' . $counter . '</td>
        <td>' . $students[1] . '</td>
        <td>' . $students[2] . '</td>';
        } else {
            echo '<tr>
            <td>' . $counter . '</td>
            <td>Anonymous</td>
            <td>' . $students[2] . '</td>';       
        }
        echo '</tr>';
    }
        echo '</tbody>
    </table>
    ';
}
$pdo = null;
?>

</div>
</body>
</html>