<?php
session_start();
if(!isset($_SESSION['user']) || !isset($_SESSION['school_id']) || !isset($_SESSION['teacher_id'])){
    echo '<script>location.href = "../login.php";</script>';
}
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
	<title>Assign Assignment</title>
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
                        <button type="button" class="button" onclick="location.href='set_assignment.php'" id="activated">Set Assignments</button><br><br>
                    </div>
                </div>
            </div>

        </div>
<h2>Assignment Options:</h2>
<?php
if(!isset($_POST['group_ID'])){
    header('location: set_assignment.php');
}
?>
  <div class="row">
    <div>
        <div class="row">
        <div class="col-md-3">
        <br>
        <div class="item">
            <?php
            $_SESSION['POST'] = $_POST;
            echo '<a href="create_ssm_assignment.php">';
            echo '<img src="SSM.png" alt="upload image" class="img-thumbnail img-fluid" style="width: 200px; height: 200px">';
            echo '<span class="caption">Assign Subject Savvy Millionaire Game</span>';
            echo '</a>';
            ?>
        </div>
        </div>
        <div class="col-md-3">
        <br>

        <div class="item">
            <?php
            $_SESSION['POST'] = $_POST;
            echo '<a href="create_ff_assignment.php">';
            echo '<img src="mathgame.png" alt="upload image" class="img-thumbnail img-fluid" style="width: 200px; height: 200px">';
            echo '<span class="caption">Assign Formula Frenzy game</span>';
            echo '</a>';
            ?>
        </div>
        </div>
        <div class="col-md-3">
        <br>

        <div class="item">
            <?php
            $_SESSION['POST'] = $_POST;
            echo '<a href="create_custom_assignment.php">';
            echo '<img src="custom_assignment.png" alt="upload image" class="img-thumbnail img-fluid" style="width: 200px; height: 200px">';
            echo '<span class="caption">Assign a Custom Assignment</span>';
            echo '</a>';
            ?>
        </div>

        </div>
        </div>
    </div>
</div>
</div>
</body>
</html>