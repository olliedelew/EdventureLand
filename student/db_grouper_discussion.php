<?php
    session_start();
    if(!isset($_SESSION['user']) || !isset($_SESSION['school_id']) || (!isset($_SESSION['student_id']) && !isset($_SESSION['teacher_id']))){
        echo '<script>location.href = "../login.php";</script>';
    }
    include '../connection.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

    <title>Discussion Board</title>
    <style>
        
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

        .title{
            font-size: 30px;
        }
        .body {
            font-size: 20px;
            padding-bottom: 20px;
            padding-top: 20px;

        }
    textarea {
        resize: none;
    }
    .row{
        display: flex;
    }

    .boxy{
        padding: 12px 20px;
    }
    .reply, .edit, .delete{
        padding: 10px;
        border: 5px solid black;
    }
    .reply{
        background-color: green;
    }
    .delete{
        background-color: red;
    }
    .reply:hover{
        background-color: grey;
    }

    .delete:hover{
        background-color: grey;
    }

</style>
    <link rel="stylesheet" href="../style.css" />

</head>
<body>
    <?php
    if(!isset($_POST['group_id'])){
        if(isset($_SESSION['POST']['group_id'])){
            $_POST['group_id'] = $_SESSION['POST']['group_id'];
        } else {
            $pdo = null;
            header('location: db_grouper.php');
        }
    }
    if(!isset($_POST['discussion_board_id'])){
        if(isset($_SESSION['POST']['discussion_board_id'])){
            $_POST['discussion_board_id'] = $_SESSION['POST']['discussion_board_id'];
        } else {
            $pdo = null;
            header('location: db_grouper.php');
        }
    }
    if(!isset($_POST['discussion_board'])){
        if(isset($_SESSION['POST']['discussion_board'])){
            $_POST['discussion_board'] = $_SESSION['POST']['discussion_board'];
        } else {
            $pdo = null;
            header('location: db_grouper.php');
        }
    }

    function db_replies($row, $pdo, $end = 'not'){


        echo '<div class="boxy">';
        echo '<p class="title" style="text-align: center;border-bottom: 3px solid black;"><b>' . $row['title'] . '</b></p>';

        echo '<p class="body" style="text-align: left;border-bottom: 3px solid black;">' . $row['body'] . '</p>';
        echo '<div class="row">';
        echo '<div class="col-md-6">';
        echo '<p class="body">';
        if($row['anonymous'] == 0){
            if(!empty($row['student_id'])){
                        $sqlid = "SELECT name, profile_picture FROM student WHERE student_id = :student_id";
                
                        $stmtid = $pdo->prepare($sqlid);
                        $stmtid->execute([
                            'student_id' => $row['student_id']
                        ]);  
                        $stmtid->setFetchMode(PDO::FETCH_ASSOC);
                        while($idrow = $stmtid->fetch()){
                            echo '<img src="profile_pictures/' . $idrow['profile_picture'] . '" style="width:60px; height:60px;" alt="Profile Picture"> ' . $idrow['name'];
                        }   
                    } else {
                        $sqlid = "SELECT name, profile_picture FROM teacher WHERE teacher_id = :teacher_id";
                
                        $stmtid = $pdo->prepare($sqlid);
                        $stmtid->execute([
                            'teacher_id' => $row['teacher_id']
                        ]);  
                        $stmtid->setFetchMode(PDO::FETCH_ASSOC);
                        while($idrow = $stmtid->fetch()){
                            echo '<img src="profile_pictures/' . $idrow['profile_picture'] . '" style="width:60px; height:60px;" alt="Profile Picture"> ' . $idrow['name'] . ' (Teacher)';
                        }            
                    }
        } else {
            echo '<img src="profile_pictures/emptyIcon.png" style="width:60px; height:60px;" alt="Profile Picture"> Anonymous';
        }
        echo '</p></div>';
        echo '<div class="col-md-6">';
        echo '<p class="body" style="margin-top:18px;">Post date: ';
        $originalDate = $row['datetime'];
        $newDate = date('jS F Y h:i a', strtotime($originalDate)); //Thursday 9th February 2023
echo $newDate;
        echo '</p></div>';
        echo '</div>';

        if(($end == 'last') && (!empty($row['student_id']) && $_SESSION['isStaff'] == 'no' && $row['student_id'] == $_SESSION['student_id'] || (!empty($row['teacher_id']) && $_SESSION['isStaff'] == 'yes' && $row['teacher_id'] == $_SESSION['teacher_id']))){
            echo '<div class="row">';
            echo '<div class="col-md-4">';
            echo '</div>';
            echo '<div class="col-md-4">';
            echo '<form action="db_grouper_discussion_edit.php" method="post">
            <button type="submit"  class="edit">Edit</button>';
            echo '<input type="hidden" name="group_id" value="' . $_POST['group_id'] . '"/>';
            echo '<input type="hidden" name="discussion_board" value="' . $_POST['discussion_board'] . '"/>';
            echo '<input type="hidden" name="discussion_board_id" value="' . $row['discussion_board_id'] . '"/>';
            echo '</form>';    
            echo '</div>';
            echo '<div class="col-md-4">';
            if($_SESSION['isStaff'] == 'yes'){
                echo '<form action="delete_db_grouper.php" method="post">';
                echo '<input type="hidden" name="group_id" value="' . $_POST['group_id'] . '"/>';
                echo '<input type="hidden" name="discussion_board" value="' . $_POST['discussion_board'] . '"/>';
                echo '<input type="hidden" name="discussion_board_id" value="' . $row['discussion_board_id'] . '"/>';
                echo '
                <a href="#" class="myBtn">
                <button type="button" class="delete" name="delete" value="delete">Delete</button><br><br>
                </a>
                <div id="myModal" class="modal">
                    <div class="modal-content-test">
                        <div class="modal-header">
                            <span class="close">&times;</span>
                            <h2>Are you sure you want to delete this discussion?</h2>
                        </div>
                        <div class="modal-body">
                        <button type="submit" class="delete">Delete</button>
                        </div>
                    </div>
                </div>';
                echo '</form>';    
            }
            echo '</div>';
            echo '</div>';
        } elseif ($end != 'last' && ((!empty($row['student_id']) && $_SESSION['isStaff'] == 'no' && $row['student_id'] == $_SESSION['student_id']) || (!empty($row['teacher_id']) && $_SESSION['isStaff'] == 'yes' && $row['teacher_id'] == $_SESSION['teacher_id']))){
            if($_SESSION['isStaff'] == 'no'){
                echo '<div class="row">';
                echo '<div class="col-md-6">';
                echo '<form action="db_grouper_discussion_new.php" method="post">
                <button type="submit" class="reply">Reply</button>';
                echo '<input type="hidden" name="group_id" value="' . $_POST['group_id'] . '"/>';
                echo '<input type="hidden" name="discussion_board" value="' . $_POST['discussion_board'] . '"/>';
                echo '<input type="hidden" name="discussion_board_id" value="' . $row['discussion_board_id'] . '"/>';
                echo '</form>';
                echo '</div>';
                echo '<div class="col-md-6">';
                echo '<form action="db_grouper_discussion_edit.php" method="post">
                <button type="submit"  class="edit">Edit</button>';
                echo '<input type="hidden" name="group_id" value="' . $_POST['group_id'] . '"/>';
                echo '<input type="hidden" name="discussion_board" value="' . $_POST['discussion_board'] . '"/>';
                echo '<input type="hidden" name="discussion_board_id" value="' . $row['discussion_board_id'] . '"/>';
                echo '</form>';    
                echo '</div>';
                echo '</div>';    
            } else {
                echo '<div class="row">';
                echo '<div class="col-md-4">';
                echo '<form action="db_grouper_discussion_new.php" method="post">
                <button type="submit" class="reply">Reply</button>';
                echo '<input type="hidden" name="group_id" value="' . $_POST['group_id'] . '"/>';
                echo '<input type="hidden" name="discussion_board" value="' . $_POST['discussion_board'] . '"/>';
                echo '<input type="hidden" name="discussion_board_id" value="' . $row['discussion_board_id'] . '"/>';
                echo '</form>';
                echo '</div>';
                echo '<div class="col-md-4">';
                echo '<form action="db_grouper_discussion_edit.php" method="post">
                <button type="submit"  class="edit">Edit</button>';
                echo '<input type="hidden" name="group_id" value="' . $_POST['group_id'] . '"/>';
                echo '<input type="hidden" name="discussion_board" value="' . $_POST['discussion_board'] . '"/>';
                echo '<input type="hidden" name="discussion_board_id" value="' . $row['discussion_board_id'] . '"/>';
                echo '</form>';    
                echo '</div>';
                echo '<div class="col-md-4">';
                echo '<form action="delete_db_grouper.php" method="post">';
                echo '<input type="hidden" name="group_id" value="' . $_POST['group_id'] . '"/>';
                echo '<input type="hidden" name="discussion_board" value="' . $_POST['discussion_board'] . '"/>';
                echo '<input type="hidden" name="discussion_board_id" value="' . $row['discussion_board_id'] . '"/>';
                echo '
                <a href="#" class="myBtn">
                <button type="button" class="delete" name="delete" value="delete">Delete</button><br><br>
                </a>
                <div id="myModal" class="modal">
                    <div class="modal-content-test">
                        <div class="modal-header">
                            <span class="close">&times;</span>
                            <h2>Are you sure you want to delete this discussion?</h2>
                        </div>
                        <div class="modal-body">
                        <button type="submit" class="delete">Delete</button>
                        </div>
                    </div>
                </div>';
                echo '</form>';    
                echo '</div>';
                echo '</div>';    
            }
        } else {
            echo '<div class="row">';
            echo '<div class="col-md-4">';
            echo '</div>';
            echo '<div class="col-md-4">';
            echo '<form action="db_grouper_discussion_new.php" method="post">
            <button type="submit" class="reply">Reply</button>';
            echo '<input type="hidden" name="group_id" value="' . $_POST['group_id'] . '"/>';
            echo '<input type="hidden" name="discussion_board" value="' . $_POST['discussion_board'] . '"/>';
            echo '<input type="hidden" name="discussion_board_id" value="' . $row['discussion_board_id'] . '"/>';
            echo '</form>';
            echo '</div>';
            echo '<div class="col-md-4">';
            if($_SESSION['isStaff'] == 'yes'){
                echo '<form action="delete_db_grouper.php" method="post">';
                echo '<input type="hidden" name="group_id" value="' . $_POST['group_id'] . '"/>';
                echo '<input type="hidden" name="discussion_board" value="' . $_POST['discussion_board'] . '"/>';
                echo '<input type="hidden" name="discussion_board_id" value="' . $row['discussion_board_id'] . '"/>';
                echo '
                <a href="#" class="myBtn">
                <button type="button" class="delete" name="delete" value="delete">Delete</button><br><br>
                </a>
                <div id="myModal" class="modal">
                    <div class="modal-content-test">
                        <div class="modal-header">
                            <span class="close">&times;</span>
                            <h2>Are you sure you want to delete this discussion?</h2>
                        </div>
                        <div class="modal-body">
                        <button type="submit" class="delete">Delete</button>
                        </div>
                    </div>
                </div>';
                echo '</form>';    
            }
            echo '</div>';
            echo '</div>';
        }
        echo '</div>';
        echo '</div>';
        return $row['discussion_board_id'];
    }
    ?>
    <div class="container">
    <h1>Discussion</h1>
        <?php
        if($_SESSION['isStaff'] == 'no'){
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
                        <button type="button" class="button" id="activated" onclick="location.href='db_grouper.php'">Discussion Boards</button><br><br> <!-- Student groupmates -->
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
        } else if($_SESSION['isStaff'] == 'yes'){
        ?>
        <div class="row" style="background-color:white;">
            <div class="col-sm-3">
                <br>
                <div class="card">
                    <div class="card-body">
                        <button type="button" class="button" id="activated" onclick="location.href='../teacher/teacher_profile.php'">Homepage</button><br><br>
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


        <?php
            $sql2 = "SELECT * FROM discussion_board WHERE discussion_board_id = :discussion_board_id";
            $stmt2 = $pdo->prepare($sql2);
            $stmt2->execute([
                'discussion_board_id' => $_POST['discussion_board']
            ]);  
            $stmt2->setFetchMode(PDO::FETCH_ASSOC);
            while($row = $stmt2->fetch()){
                echo '<br>';
                echo '<div class="col-md-12" style="border: 5px solid black; background-color: white;">';
                echo '<br>';
                db_replies($row, $pdo);
            }
            $sql2 = "SELECT * FROM discussion_board WHERE reply_id = :reply_id ORDER BY datetime ASC";
    
            $stmt2 = $pdo->prepare($sql2);
            $stmt2->execute([
                'reply_id' => $_POST['discussion_board']
            ]);  
            $stmt2->setFetchMode(PDO::FETCH_ASSOC);
            $replies_counter = 0;
            $replies_to_reply_counter = 0;
            echo '<br>';
            echo '<h1>REPLIES</h1>';
            while($row = $stmt2->fetch()){
                echo '<div class="row">';
                echo '<br>';
                echo '<div class="col-md-1">';
                echo '</div>';
                if($replies_counter == 0){
                    echo '<div class="col-md-11" style="border: 5px solid black; background-color: white">';
                } else {
                    echo '<div class="col-md-11" style="margin-top: 20px;border: 5px solid black; background-color: white;">';
                }
                $replies_counter += 1;
                echo '<br>';
                $discussion_board_id = db_replies($row, $pdo);
                echo '</div>';
                $sql3 = "SELECT * FROM discussion_board WHERE reply_id = :reply_id ORDER BY datetime ASC";
                $stmt3 = $pdo->prepare($sql3);
                $stmt3->execute([
                    'reply_id' => $discussion_board_id
                ]);  
                $stmt3->setFetchMode(PDO::FETCH_ASSOC);
                while($row2 = $stmt3->fetch()){
                    echo '<div class="row">';
                    echo '<br>';    
                    echo '<div class="col-md-1">';
                    echo '</div>';
                    echo '<div class="col-md-1" style="border-left: 2px dashed black;">';
                    echo '</div>';
                    echo '<div class="col-md-10" style="margin-top: 20px;border: 5px solid black; background-color: white;">';    
                    echo '<br>';
                    $discussion_board_id = db_replies($row2, $pdo);
                    echo '</div>';
                    
                    $sql4 = "SELECT * FROM discussion_board WHERE reply_id = :reply_id ORDER BY datetime ASC";
            
                    $stmt4 = $pdo->prepare($sql4);
                    $stmt4->execute([
                        'reply_id' => $discussion_board_id
                    ]);  
                    $stmt4->setFetchMode(PDO::FETCH_ASSOC);
                    while($row3 = $stmt4->fetch()){
                        echo '<div class="row">';
                        echo '<br>';    
                        echo '<div class="col-md-1">';
                        echo '</div>';
                        echo '<div class="col-md-1" style="border-left: 2px dashed black;">';
                        echo '</div>';
                        echo '<div class="col-md-1" style="border-left: 2px dashed black;">';
                        echo '</div>';
                        echo '<div class="col-md-9" style="margin-top: 20px;border: 5px solid black; background-color: white;">';    
    
                        echo '<br>';
                        $discussion_board_id = db_replies($row3, $pdo);
                        echo '</div>';
                        $sql5 = "SELECT * FROM discussion_board WHERE reply_id = :reply_id ORDER BY datetime ASC";
                        $stmt5 = $pdo->prepare($sql5);
                        $stmt5->execute([
                            'reply_id' => $discussion_board_id
                        ]);  
                        $stmt5->setFetchMode(PDO::FETCH_ASSOC);
                        while($row4 = $stmt5->fetch()){
                            echo '<div class="row">';
                            echo '<br>';    
                            echo '<div class="col-md-1">';
                            echo '</div>';
                            echo '<div class="col-md-1" style="border-left: 2px dashed black;">';
                            echo '</div>';
                            echo '<div class="col-md-1" style="border-left: 2px dashed black;">';
                            echo '</div>';
                            echo '<div class="col-md-1" style="border-left: 2px dashed black;">';
                            echo '</div>';
                            echo '<div class="col-md-8" style="margin-top: 20px;border: 5px solid black; background-color: white;">';    
   
                            echo '<br>';
                            $discussion_board_id = db_replies($row4, $pdo, 'last');
                            echo '</div>';
                        }
                    }
                    
                }

            }
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
        // alert(event.target);
        for (let index = 0; index < modal.length; index++) {
            if (event.target == modal[index]) {
                modal[index].style.display = "none";
            }
        }
    }
</script>
</html>