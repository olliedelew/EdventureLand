<?php
session_start();
if(!isset($_SESSION['user']) || !isset($_SESSION['school_id']) || !isset($_SESSION['student_id'])){
    echo '<script>location.href = "../login.php";</script>';
}

if(!isset($_POST['points']) || !isset($_POST['difficulty']) || !isset($_POST['lifelines_used']) || !isset($_POST['questions_right']) || !isset($_POST['topic']) || !isset($_POST['subject'])){
    header('location/: ../student/student_assignments.php');
}
include '../connection.php';

$student_id = $_SESSION['student_id'];
$points = $_POST['points'];
$difficulty = $_POST['difficulty'];
$lifelines_used = $_POST['lifelines_used'];
$questions_right = intval($_POST['questions_right']);
$topic = $_POST['topic'];
$subject = $_POST['subject'];


$sql = "SELECT points FROM leaderboard WHERE student_id = :student_id AND difficulty = :difficulty ORDER BY points DESC LIMIT 1";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    'student_id' => $student_id,
    'difficulty' => $difficulty
]);

$stmt->setFetchMode(PDO::FETCH_ASSOC);
$added = true;
$counter = 0;
while ($row = $stmt->fetch()) {
    $counter++;
    if($points <= $row['points']){
        $added = false;
    }
}


$sql = "SELECT * FROM student WHERE student_id = :student_id";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    'student_id' => $student_id
]);

$stmt->setFetchMode(PDO::FETCH_ASSOC);
$ssm_count = NULL;
while ($row = $stmt->fetch()) {
    $ssm_count = $row['ssm_count'];
}

$sql2 = "UPDATE `student` SET `ssm_count`= `ssm_count` + $questions_right WHERE `student_id` = :student_id";

$stmt2 = $pdo->prepare($sql2);
$stmt2->execute([
    'student_id' => $student_id
]);

if(($ssm_count + $questions_right) > 4){
    $sql3 = "INSERT IGNORE INTO `student_badge`(`student_id`, `badge_id`) VALUES (:student_id, :badge_id)";

    $stmt3 = $pdo->prepare($sql3);
    $stmt3->execute([
        'student_id' => $student_id,
        'badge_id' => 7
    ]);
}
if(($ssm_count + $questions_right) > 9){
    $sql3 = "INSERT IGNORE INTO `student_badge`(`student_id`, `badge_id`) VALUES (:student_id, :badge_id)";

    $stmt3 = $pdo->prepare($sql3);
    $stmt3->execute([
        'student_id' => $student_id,
        'badge_id' => 8
    ]);
}
if(($ssm_count + $questions_right) > 19){
    $sql3 = "INSERT IGNORE INTO `student_badge`(`student_id`, `badge_id`) VALUES (:student_id, :badge_id)";

    $stmt3 = $pdo->prepare($sql3);
    $stmt3->execute([
        'student_id' => $student_id,
        'badge_id' => 9
    ]);
}
if(($ssm_count + $questions_right) > 49){
    $sql3 = "INSERT IGNORE INTO `student_badge`(`student_id`, `badge_id`) VALUES (:student_id, :badge_id)";

    $stmt3 = $pdo->prepare($sql3);
    $stmt3->execute([
        'student_id' => $student_id,
        'badge_id' => 10
    ]);
}
if($counter == 0 || $added == true){

    $sql = "INSERT INTO `leaderboard`(`points`, `lifelines_used`, `questions_right`, `topic`, `subject`, `student_id`, `difficulty`) 
    VALUES ($points, '$lifelines_used', $questions_right,'$topic', '$subject', $student_id, '$difficulty')";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute();  
    
    $lastInsertId = $pdo->lastInsertId();
    $sql = "SELECT *, LENGTH(JSON_EXTRACT(leaderboard.lifelines_used, '$.key')) as json_length FROM leaderboard INNER JOIN student ON student.student_id = leaderboard.student_id WHERE difficulty = :difficulty AND school_id = :school_id ORDER BY points DESC, json_length ASC LIMIT 5";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'difficulty' => $difficulty,
        'school_id' => $_SESSION['school_id']
    ]);  
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $counter = 0;
    while ($row = $stmt->fetch()) {
        $counter++;
        if(intval($row['leaderboard_id']) == intval($lastInsertId)){
            $sql3 = "INSERT IGNORE INTO `student_badge`(`student_id`, `badge_id`) VALUES (:student_id, :badge_id)";
        
            $stmt3 = $pdo->prepare($sql3);
            $stmt3->execute([
                'student_id' => $student_id,
                'badge_id' => 14
            ]);
            echo $counter;
            if($counter == 1){
                $sql3 = "INSERT IGNORE INTO `student_badge`(`student_id`, `badge_id`) VALUES (:student_id, :badge_id)";
            
                $stmt3 = $pdo->prepare($sql3);
                $stmt3->execute([
                    'student_id' => $student_id,
                    'badge_id' => 13
                ]);    
            }
        }
    }
    $_POST['added'] = 'true';
    $_POST['id'] = $lastInsertId;
    $_POST['difficulty'] = $difficulty;
    $_SESSION['POST'] = $_POST;
    $pdo = null;
    header("location: ../student/leaderboard.php");

} else {
    $_POST['added'] = 'false';
    $_POST['difficulty'] = $difficulty;
    $_SESSION['POST'] = $_POST;
    $pdo = null;
    header("location: ../student/leaderboard.php");
}

?>