<?php
session_start();
if (($_SESSION['isAdmin'] != 'yes') || !isset($_SESSION['user']) || !isset($_SESSION['school_id'])) { //check if user is a user and display buttons
    header('location: ../login.php');
}

?>
<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <title>Homepage</title>
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
    </style>
</head>

<body>
    <div class="container" style="text-align:left;">
        <h1>Homepage</h1>
        <div class="row" style="background-color: white;">
            <div class="col-sm-3">
                <br>
                <button type="button" class="button" id="activated">Homepage</button><br><br>
            </div>
            <div class="col-sm-3">
                <br>
                <button type="button" class="button" onclick="location.href='teachers.php'">Teachers</button><br><br> <!-- Student groupmates -->
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

        <div class="row" style="text-align: center;">
            <br>
            <!-- Show all the necessary things the admin needs to help teachers and students set up their accounts -->
            <?php
            $sql = "SELECT school_email, school_pin FROM school WHERE school_id = :school_id";
            include '../connection.php';

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'school_id' => $_SESSION['school_id']
            ]);
            while ($row = $stmt->fetch()) {
                echo '<h1>School Email Ending (For Teacher Registration):</h1>';
                echo '<h2>@' . $row['school_email'] . '</h2>';
                echo '<br>';
                echo '<h1>School Access Code (For Student Registration):</h1>';
                echo '<h2>' . $row['school_pin'] . '</h2>';
            }
            $pdo = null;
            ?>
        </div>

    </div>
    </div>
    </div>
</body>

</html>