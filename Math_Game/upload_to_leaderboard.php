<?php
session_start();
if(!isset($_SESSION['user']) || !isset($_SESSION['school_id']) || !isset($_SESSION['student_id'])){
    echo '<script>location.href = "../login.php";</script>';
}
if(!isset($_POST['score_hidden']) || !isset($_POST['incorrect_ones']) || !isset($_POST['opps']) || !isset($_POST['correct_ones']) || !isset($_POST['difficulty_sent_off'])){
    header('../student/student_homepage.php');
}
include '../connection.php';

// get all post and session variables

$student_id = $_SESSION['student_id'];
$points = $_POST['score_hidden'];
$incorrect = $_POST['incorrect_ones'];
$operators = $_POST['opps'];
$correct = intval($_POST['correct_ones']);
$difficulty = $_POST['difficulty_sent_off'];

// sql statement to check what the user's highest score is
$sql = "SELECT points FROM math_leaderboard WHERE student_id = :student_id AND difficulty = :difficulty ORDER BY points DESC LIMIT 1";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    'student_id' => $student_id,
    'difficulty' => $difficulty
]);
$added = true;
$counter = 0;
while ($row = $stmt->fetch()) {
    $counter++;
    if($points <= $row['points']){
        // Dont add to leaderboard
        $added = false;
    }
}

// Get the math count from the user 
$sql = "SELECT math_count FROM student WHERE student_id = :student_id";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    'student_id' => $student_id
]);

$stmt->setFetchMode(PDO::FETCH_ASSOC);
$math_count = 0;
while ($row = $stmt->fetch()) {
    $math_count = intval($row['math_count']);
}

// add the correct number to the math_count for use in setting the badges for user
$sql2 = "UPDATE `student` SET `math_count`= `math_count` + $correct WHERE `student_id` = :student_id";

$stmt2 = $pdo->prepare($sql2);
$stmt2->execute([
    'student_id' => $student_id
]);

// here we set the badges if the number of total correct answers is a certain amount
if(($math_count + $correct) > 19){
    $sql3 = "INSERT IGNORE INTO `student_badge`(`student_id`, `badge_id`) VALUES (:student_id, :badge_id)";

    $stmt3 = $pdo->prepare($sql3);
    $stmt3->execute([
        'student_id' => $student_id,
        'badge_id' => 1
    ]);
}
if(($math_count + $correct) > 39){
    $sql3 = "INSERT IGNORE INTO `student_badge`(`student_id`, `badge_id`) VALUES (:student_id, :badge_id)";

    $stmt3 = $pdo->prepare($sql3);
    $stmt3->execute([
        'student_id' => $student_id,
        'badge_id' => 2
    ]);
}
if(($math_count + $correct) > 69){
    $sql3 = "INSERT IGNORE INTO `student_badge`(`student_id`, `badge_id`) VALUES (:student_id, :badge_id)";

    $stmt3 = $pdo->prepare($sql3);
    $stmt3->execute([
        'student_id' => $student_id,
        'badge_id' => 11
    ]);
}
if(($math_count + $correct) > 99){
    $sql3 = "INSERT IGNORE INTO `student_badge`(`student_id`, `badge_id`) VALUES (:student_id, :badge_id)";

    $stmt3 = $pdo->prepare($sql3);
    $stmt3->execute([
        'student_id' => $student_id,
        'badge_id' => 12
    ]);
}


// if the user isn't on the leaderboard already or has beaten their highscore
if($counter == 0 || $added == true){

    // Insert them into the leaderboard
    $sql = "INSERT INTO `math_leaderboard`(`points`, `questions_wrong`, `operators`, `questions_right`, `student_id`, `difficulty`) 
    VALUES ($points, $incorrect, '$operators', $correct, $student_id, '$difficulty')";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute();  
    
    $lastInsertId = $pdo->lastInsertId();

    // Check where they were placed on the leaderboard (check if they were in top 5)
    $sql = "SELECT *, LENGTH(JSON_EXTRACT(math_leaderboard.operators, '$.key')) as json_length FROM math_leaderboard INNER JOIN student ON student.student_id = math_leaderboard.student_id WHERE difficulty = :difficulty AND school_id = :school_id ORDER BY points DESC, json_length DESC, questions_right DESC, questions_wrong ASC LIMIT 5";
    $stmt = $pdo->prepare($sql);

    $stmt->execute([
    'difficulty' => $difficulty,
    'school_id' => $_SESSION['school_id']
    ]);
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $counter = 0;

    while ($row = $stmt->fetch()) {
        $counter++;
        // if they were in the top 5 then give them a badge
        if(intval($row['leaderboard_id']) == intval($lastInsertId)){
            $sql3 = "INSERT IGNORE INTO `student_badge`(`student_id`, `badge_id`) VALUES (:student_id, :badge_id)";
        
            $stmt3 = $pdo->prepare($sql3);
            $stmt3->execute([
                'student_id' => $student_id,
                'badge_id' => 16
            ]);
            // if they were top of the leaderboard then give them a different badge
            if($counter == 1){
                $sql3 = "INSERT IGNORE INTO `student_badge`(`student_id`, `badge_id`) VALUES (:student_id, :badge_id)";
            
                $stmt3 = $pdo->prepare($sql3);
                $stmt3->execute([
                    'student_id' => $student_id,
                    'badge_id' => 15
                ]);    
            }
        }
    }

    // set the post variables and send that through using session variable to the leaderboard page
    $_POST['added'] = 'true';
    $_POST['id'] = $lastInsertId;
    $_POST['difficulty'] = $difficulty;
    $_SESSION['POST'] = $_POST;

    $pdo = null;
    header("location: leaderboard.php");
} else {
    $_POST['added'] = 'false';
    $_POST['difficulty'] = $difficulty;
    $_SESSION['POST'] = $_POST;
    $pdo = null;
    header("location: leaderboard.php");
}




?>