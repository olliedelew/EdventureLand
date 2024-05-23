<?php
session_start();
if (($_SESSION['isAdmin'] != 'yes') || !isset($_SESSION['user']) || !isset($_SESSION['school_id'])) { //check if user is a user and display buttons
    header('location: ../login.php');
}
include '../connection.php';
?>
<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://kit.fontawesome.com/dd47d27144.js" crossorigin="anonymous"></script>
    <title>Teachers</title>
    <style type="text/css">
        #activated {
            background-color: grey;
        }

        .col-sm-3 {
            border: 5px solid black;
        }

        body {
            background-color: lightblue;
        }

        .button {
            background-color: orange;
            color: white;
            font-size: 18px;
            padding: 14px 30px;
            width: 100%;
        }

        .button:hover {
            background-color: #555;
        }

        .button-reject {
            background-color: red;
            color: white;
            font-size: 18px;
            width: 100%;
        }

        .button-reject:hover {
            background-color: darkred;
        }

        .button-approve {
            background-color: green;
            color: white;
            font-size: 18px;
            width: 100%;
        }

        .del {
            background-color: red;
        }

        .button-approve:hover {
            background-color: darkgreen;
        }

        .modal-header {
            padding: 2px 16px;
            background-color: blue;
            color: white;
        }

        .modal-body {
            padding: 2px 16px;
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
            width: 30%;
            animation-name: zoomy;
            animation-duration: 0.5s
        }

    </style>
</head>

<body>
    <div class="container" style="text-align:left;">
        <h1>Teachers</h1>
        <!-- Profile pic -->
        <div class="row" style="background-color: white;">
            <div class="col-sm-3">
                <br>
                <button type="button" class="button" onclick="location.href='admin_homepage.php'">Homepage</button><br><br>
            </div>
            <div class="col-sm-3">
                <br>
                <button type="button" class="button" id="activated">Teachers</button><br><br> <!-- Student groupmates -->
            </div>
            <div class="col-sm-3">
                <br>
                <button type="button" class="button" onclick="location.href='students.php'">Students</button><br><br>
            </div>
            <div class="col-sm-3">
                <br>
                <button type="button" class="button" onclick="location.href='logout.php'">Sign Out</button><br><br>
            </div>
        </div>
        <br>
        <div class="col-md-6" style="text-align:center;">
            <!-- Teachers that are waiting to be approved are under here -->
            <h1><u>Teachers Approval Requests</u></h1>
            <?php
            $sql = "SELECT name, institute_email, teacher_id FROM teacher WHERE school_id = :school_id AND approval = 0 ORDER BY name";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'school_id' => $_SESSION['school_id']
            ]);
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            while ($row = $stmt->fetch()) {
                echo '<div class="row">';
                echo '<div class="col-md-8">';
                echo '<h3>' . $row['name'] . '</h3>';
                echo $row['institute_email'];
                echo '</div>';
                echo '<div class="col-md-4">';
                echo '<div class="col-md-6">';
                echo '<br>';
                echo '<form action="approve.php" method="post">';
                echo '<button class="button-approve" type="submit"><i class="fa-solid fa-check"></i></button>';
                echo '<input type="hidden" name="teacher_id" value="' . $row['teacher_id'] . '">';
                echo '</form>';
                echo '</div>';
                echo '<div class="col-md-6">';
                echo '<br>';
                echo '<form action="delete.php" method="post">';
                echo '<a href="#" class="myBtn">';
                echo '<input type="hidden" name="student_id" value="' . $row['student_id'] . '">';
                echo '<button class="button-reject" type="button"><i class="fa-solid fa-ban"></i></button>';
                echo '</a>
                <div id="myModal" class="modal">
                    <div class="modal-content-test">
                        <div class="modal-header">
                            <span class="close">&times;</span>
                            <h2>Are you sure you want to reject this teacher?</h2>
                        </div>
                        <div class="modal-body">
                        <button type="submit" class="button del">Delete</button>
                        </div>
                    </div>
                </div>';
                // echo '<button class="button-reject" type="submit"><i class="fa-solid fa-ban"></i></button>';
                echo '<input type="hidden" name="teacher_id" value="' . $row['teacher_id'] . '">';
                echo '</form>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
                echo '<br>';
            }


            ?>
        </div>
        <div class="col-md-6" style="text-align:center;">
            <!-- Teachers that are approved are under here -->
            <h1><u>Approved Teachers</u></h1>
            <?php

            $sql = "SELECT name, institute_email, teacher_id FROM teacher WHERE school_id = :school_id AND approval = 1 ORDER BY name";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'school_id' => $_SESSION['school_id']
            ]);
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            while ($row = $stmt->fetch()) {
                echo '<div class="row">';
                echo '<div class="col-md-8">';
                echo '<h3>' . $row['name'] . '</h3>';
                echo $row['institute_email'];
                echo '</div>';
                echo '<div class="col-md-4">';
                echo '<br>';
                echo '<form action="reject.php" method="post">';
                echo '<button class="button-reject" type="submit"><i class="fa-solid fa-x"></i></button>';
                echo '<input type="hidden" name="teacher_id" value="' . $row['teacher_id'] . '">';
                echo '</form>';
                echo '</div>';
                echo '</div>';
                echo '<br>';
            }
            $pdo = null;
            ?>
        </div>
    </div>
    </div>
    </div>
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
        // alert(event.target);
        for (let index = 0; index < modal.length; index++) {
            if (event.target == modal[index]) {
                modal[index].style.display = "none";
            }
        }
    }
</script>
</html>