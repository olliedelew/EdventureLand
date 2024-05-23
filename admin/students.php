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

    <title>Students</title>
    <!-- <link rel="stylesheet" href="../style.css" /> -->
    <style>
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

        .del {
            background-color: red;
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
        <h1>Students</h1>
        <div class="row" style="background-color: white;">
            <div class="col-sm-3">
                <br>
                <button type="button" class="button" onclick="location.href='admin_homepage.php'">Homepage</button><br><br>
            </div>
            <div class="col-sm-3">
                <br>
                <button type="button" class="button" onclick="location.href='teachers.php'">Teachers</button><br><br> <!-- Student groupmates -->
            </div>
            <div class="col-sm-3">
                <br>
                <button type="button" class="button" id="activated">Students</button><br><br>
            </div>
            <div class="col-sm-3">
                <br>
                <button type="button" class="button" onclick="location.href='logout.php'">Sign Out</button><br><br>
            </div>
        </div>

        <br>
        <div class="col-md-12" style="text-align:center;">
            <h1><u>Registered Students</u></h1>
            <?php
            // Here we show up all the students in the school showing their full name,
            // year group and a big X to delete them from the school
            $sql = "SELECT name, student_year, student_id FROM student WHERE school_id = :school_id ORDER BY student_year ASC, name ASC"; //WHERE teacher_id = $_SESSION[isStaff] then get student_id where it is the same as group id
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'school_id' => $_SESSION['school_id']
            ]);

            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            while ($row = $stmt->fetch()) {
                echo '<hr>';
                echo '<div class="row">';
                echo '<div class="col-md-4">';
                echo '</div>';
                echo '<div class="col-md-5">';
                echo '<div class="col-md-6">';
                echo '<h3>' . $row['name'] . '</h3>';
                echo 'Year ' . $row['student_year'];
                echo '</div>';
                echo '<div class="col-md-3">';
                echo '<br>';
                echo '<form action="delete.php" method="post">';
                echo '<a href="#" class="myBtn">';
                echo '<input type="hidden" name="student_id" value="' . $row['student_id'] . '">';
                echo '<button class="button-reject" type="button"><i class="fa-solid fa-x"></i></button>';
                //  <button type="button" class="delete" name="delete" value="delete">Delete</button><br><br>
                echo '</a>
                <div id="myModal" class="modal">
                    <div class="modal-content-test">
                        <div class="modal-header">
                            <span class="close">&times;</span>
                            <h2>Are you sure you want to delete this student?</h2>
                        </div>
                        <div class="modal-body">
                        <button type="submit" class="button del">Delete</button>
                        </div>
                    </div>
                </div>';
                echo '</form>';
                echo '<div class="col-md-3">';
                echo '</div>';
                echo '</div>';
                echo '</div>';
                echo '<div class="col-md-3">';
                echo '</div>';
                echo '</div>';
                echo '<hr>';
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