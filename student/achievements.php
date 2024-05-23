<?php 
    session_start();
    if(!isset($_SESSION['user']) || !isset($_SESSION['school_id']) || !isset($_SESSION['student_id'])){
        echo '<script>location.href = "../login.php";</script>';
    }
    include '../connection.php';
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Achievements</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        <style type="text/css">

        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            padding-top: 100px;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: blue;
            background-color: rgba(0, 0, 0, 0.7);
        }

    .image3 {
      display: block;
      margin-left: auto;
      margin-right: auto;
      width: 100px;
      height: 100px;
      position: relative;
      top: 0;
      left: 0;
    }

    .image_greyed{
        filter: brightness(20%);
        display: block;
      margin-left: auto;
      margin-right: auto;
      width: 100px;
      height: 100px;
      position: relative;
      top: 0;
      left: 0;

    }

    a:hover {
      cursor: default;
    }

    @keyframes zoomy {
            0% {
                transform: scale(0.5, 0.5);
            }

            100% {
                transform: scale(1, 1);
            }
        } 

        .modal-content-test {
            top: 30%;
            position: relative;
            background-color: #fefefe;
            margin: auto;
            padding: 0;
            border: 5px solid black;
            width: 80%;
            animation-name: zoomy;
            animation-duration: 0.5s
        }

        .close {
            color: white;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: #000;
            text-decoration: none;
            cursor: pointer;
        }

        .modal-header {
            padding: 2px 16px;
            background-color: blue;
            color: white;
        }

        .modal-body {
            padding: 2px 16px;
        }

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

        .col-sm-3 {
            border: 5px solid black;
        }

    </style>
        <link rel="stylesheet" href="../style.css" />

</head>
  <body>

<div class="container">
<h1>Achievements</h1>
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
                        <button type="button" class="button" onclick="location.href='student_assignments.php'">Assignments</button><br><br>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <br>
                <div class="card">
                    <div class="card-body">
                        <button type="button" class="button"  id="activated" onclick="location.href='student_settings.php'">Account</button><br><br>
                    </div>
                </div>
            </div>
    </div>
    <?php    
        echo '<div class="row">';
        echo '<h2>Math Badges</h2><br>';
$sql = "SELECT * FROM student_badge INNER JOIN badge ON student_badge.badge_id = badge.badge_id WHERE student_badge.student_id = :student_id AND badge.badge_type = :badge_type";
$teachid = $_SESSION['student_id'];
$stmt = $pdo->prepare($sql);
$stmt->execute([
    'student_id' => $teachid,
    'badge_type' => "math"
]);
$saved_ids = array();
$stmt->setFetchMode(PDO::FETCH_ASSOC);
while ($row = $stmt->fetch()) {
    echo '<div class="col-md-3">';
    echo '<a href="#" class="myBtn">
    <img src="badges/Math/' . $row['picture'] . '" class="image3">
    </a>
    <div id="myModal" class="modal">
        <div class="modal-content-test">
            <div class="modal-header">
                <span class="close">&times;</span>
                <h2>You are a Formula Frenzy Fanatic!</h2>
            </div>
            <div class="modal-body">
                <h2>' . $row['badge_info'] . '</h2>
            </div>
        </div>
        </div>
        </div>';

    array_push($saved_ids, $row['badge_id']);
}
$sql = "SELECT * FROM badge WHERE badge_type = :badge_type";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    'badge_type' => "math"
]);
$stmt->setFetchMode(PDO::FETCH_ASSOC);
while ($row = $stmt->fetch()) {

    if (!in_array($row['badge_id'], $saved_ids)) {
        echo '<div class="col-md-3">';
        echo '<img src="badges/Math/' . $row['picture'] . '" class="image_greyed">';
        echo '</div>';
    }
}
        echo '</div>
        <div class="row">
        <h2>Subject Savvy Millionaire Badges</h2>
        <br>';
        $sql = "SELECT * FROM student_badge INNER JOIN badge ON student_badge.badge_id = badge.badge_id WHERE student_badge.student_id = :student_id AND badge.badge_type = :badge_type";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'student_id' => $teachid,
            'badge_type' => "ssm"
        ]);
        $saved_ids = array();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        while ($row = $stmt->fetch()) {
            echo '<div class="col-md-3">';
            echo '<a href="#" class="myBtn">
            <img src="badges/SSM/' . $row['picture'] . '" class="image3">
            </a>
            <div id="myModal" class="modal">
                <div class="modal-content-test">
                    <div class="modal-header">
                        <span class="close">&times;</span>
                        <h2>You are a Subject Savvy Millionaire Genius!</h2>
                    </div>
                    <div class="modal-body">
                        <h2>' . $row['badge_info'] . '</h2>
                    </div>
                </div>
                </div>
                </div>';
                array_push($saved_ids, $row['badge_id']);
        }
        $sql = "SELECT * FROM badge WHERE badge_type = :badge_type";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'badge_type' => "ssm"
        ]);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        while ($row = $stmt->fetch()) {
            if (!in_array($row['badge_id'], $saved_ids)) {
                echo '<div class="col-md-3">';
                echo '<img src="badges/SSM/' . $row['picture'] . '" class="image_greyed">';
                echo '</div>';
            }
        }
        echo '</div>
        <div class="row">';

        echo'<h2>Login Badges</h2>';
        echo '<br>';
        $sql = "SELECT * FROM student_badge INNER JOIN badge ON student_badge.badge_id = badge.badge_id WHERE student_badge.student_id = :student_id AND badge.badge_type = :badge_type";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'student_id' => $teachid,
            'badge_type' => "login"
        ]);
        $saved_ids = array();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        while ($row = $stmt->fetch()) {
            echo '<div class="col-md-3">';
            echo '<a href="#" class="myBtn">
            <img src="badges/login/' . $row['picture'] . '" class="image3">
            </a>
            <div id="myModal" class="modal">
                <div class="modal-content-test">
                    <div class="modal-header">
                        <span class="close">&times;</span>
                        <h2>You are a Login Legend!</h2>
                    </div>
                    <div class="modal-body">
                        <h2>' . $row['badge_info'] . '</h2>
                    </div>
                </div>
                </div>
                </div>';

            array_push($saved_ids, $row['badge_id']);
        }
        $sql = "SELECT * FROM badge WHERE badge_type = :badge_type";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'badge_type' => "login"
        ]);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        while ($row = $stmt->fetch()) {
            if (!in_array($row['badge_id'], $saved_ids)) {
                echo '<div class="col-md-3">';
                echo '<img src="badges/login/' . $row['picture'] . '" class="image_greyed">';
                echo '</div>';
            }
        }
        echo '</div>';
        echo '<div class="row">
        <h2>Leaderboard Badges</h2>
        <br>';
        $sql = "SELECT * FROM student_badge INNER JOIN badge ON student_badge.badge_id = badge.badge_id WHERE student_badge.student_id = :student_id AND badge.badge_type = :badge_type";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'student_id' => $teachid,
            'badge_type' => "leader"
        ]);
        $saved_ids = array();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        while ($row = $stmt->fetch()) {
            echo '<div class="col-md-3">';
            echo '<a href="#" class="myBtn">
            <img src="badges/leader/' . $row['picture'] . '" class="image3">
            </a>
            <div id="myModal" class="modal">
                <div class="modal-content-test">
                    <div class="modal-header">
                        <span class="close">&times;</span>
                        <h2>You are a Master of The Board!</h2>
                    </div>
                    <div class="modal-body">
                        <h2>' . $row['badge_info'] . '</h2>
                    </div>
                </div>
                </div>
                </div>';
                array_push($saved_ids, $row['badge_id']);
        }
        $sql = "SELECT * FROM badge WHERE badge_type = :badge_type";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'badge_type' => "leader"
        ]);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        while ($row = $stmt->fetch()) {
        
            if (!in_array($row['badge_id'], $saved_ids)) {
                echo '<div class="col-md-3">';
                echo '<img src="badges/leader/' . $row['picture'] . '" class="image_greyed">';
                echo '</div>';
            }
        }
        echo '</div>
        <br>';
        echo '
        </div>

        </div>';
      
        echo '</div>';
        $pdo = null;
    ?>
    </div>
  </body>
  <script>
    // Get the modal
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
