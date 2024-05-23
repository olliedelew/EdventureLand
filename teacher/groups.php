<?php
    session_start();
    // var_dump($_SESSION);
    if(!isset($_SESSION['user']) || !isset($_SESSION['school_id']) || !isset($_SESSION['teacher_id'])){
      echo '<script>location.href = "../login.php";</script>';
    }
    include '../connection.php';

    ?>

<!DOCTYPE html>
<html>

<head>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <title>Index</title>
 <style type="text/css">
  #addbtn{
    background-color: green;
  }
  #delbtn{
    background-color: red;
  }
  #editbtn{
    background-color: green;
  }
  #editbtn:hover, #addbtn:hover{
    background-color: darkgreen;
  }
  #delbtn:hover{
    background-color: darkred;
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
            width: 30%;
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

        .button-progress {
            background-color: orange;
            color: white;
            font-size: 18px;
            padding: 14px 30px;
            width: 100%;
        }

        .button-progress:hover {
            background-color: darkorange;
        }
        .button-delete {
            background-color: red;
            color: white;
            font-size: 18px;
            padding: 14px 30px;
            width: 100%;
        }

        .button-delete:hover {
            background-color: darkred;
        }

</style>
  <link rel="stylesheet" href="../style.css" />
</head>

<body>
  <div class="container" style="text-align:left;">
  <h1>Student Groups</h1>
  <div class="row" style="background-color: white;">
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
                        <button type="button" class="button" id="activated">Student Groups</button><br><br> <!-- Student groupmates -->
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
                        <button type="button" class="button" onclick="location.href='set_assignment.php'">Set Assignments</button><br><br>
                    </div>
                </div>
            </div>
</div>
<br>

      <div class="row">
        <div class="col-sm-6">
          <div class="card">
            <div class="card-body">
              <!-- php here to output all the sg's -->
              <input type="hidden" id="ingHidden" name="ingHidden" value="">
              <input type="hidden" id="hidden_list" name="hidden_list" value="">
              <?php

              $sql = "SELECT student_group_id FROM teacher_student_group WHERE teacher_id = :teacher_id";
              $teachID = $_SESSION['teacher_id'];

              $stmt = $pdo->prepare($sql);
              $stmt->execute([
                'teacher_id' => $teachID
              ]);

              $stmt->setFetchMode(PDO::FETCH_ASSOC);
              $ids = array();

              while ($row = $stmt->fetch()) {
                array_push($ids, $row['student_group_id']);
              }
              $sql = "SELECT * FROM student_group WHERE student_group_id = :student_group_id";

              $stmt = $pdo->prepare($sql);
              if(count($ids) != 0){
                echo '<h1 style="text-align:center;"><u>Student Groups</u></h1><br>';
              foreach ($ids as $id) {
                $stmt->execute([
                  'student_group_id' => $id
                ]);

                $stmt->setFetchMode(PDO::FETCH_ASSOC);
                $groupID = $_GET['group_ID'];
                while ($row = $stmt->fetch()) {
                  if ($groupID == $row['student_group_id']) {
                    echo '<a href= groups.php?group_ID=' . $row['student_group_id'] . '><input type="button" name="' .  $row["name"] . '" id="button6activated" class="button" value="' . $row["name"] . '"></a><br><br>';
                  } else {
                    echo '<a href= groups.php?group_ID=' . $row['student_group_id'] . '><input type="button" name="' .  $row["name"] . '" id="otherbtn" class="button" value="' . $row["name"] . '"></a><br><br>';
                  }
                }
              }
            }


              ?>
              <hr>
              <button type="button" id="addbtn" onclick="location.href='new_sg.php'">Add new student group +</button><br><br>
            </div>
          </div>
        </div>
        <div class="col-sm-6">
          <div class="card">
            <div class="card-body">
              <div class="card6">
                <?php
                if(isset($_GET['group_ID'])){
                  if(!in_array($_GET['group_ID'], $ids)){
                    $pdo = null;
                    header('location: groups.php');
                  } else {
                  echo '<h1 style="text-align:center;">Students</h1>';
                  $gid = $_GET['group_ID'];
                // Do a search for all student groups relating to a specific teacher 
                $sql = "SELECT student_id FROM Student_student_group WHERE student_group_id = :group_id"; 

                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                  'group_id' => $gid
                ]);
                $stmt->setFetchMode(PDO::FETCH_ASSOC);
                $ids = array();

                while ($row = $stmt->fetch()) {
                  array_push($ids, $row['student_id']);
                }


                $sql = "SELECT * FROM student WHERE student_id = :student_id"; //Order by???

                $stmt = $pdo->prepare($sql);
                foreach ($ids as $id) {
                  $stmt->execute([
                    'student_id' => $id
                  ]);

                  $stmt->setFetchMode(PDO::FETCH_ASSOC);

                  while ($row = $stmt->fetch()) {
                    echo '<a href="#" class="myBtn">';
                    echo '<button type="button" style="display: flex; align-items: center;" id="button6" name="sid" value="'. $row["student_id"].'">';
                    $button_image = '<img src="profile_pictures/' . $row['profile_picture'] . '" alt="Button Image" style="margin-right: 10px; width: 50px; height: 50px;">';
                    $button_text = $row["name"];
                    echo $button_image . "<span style='text-align: center; width: 100%;'>" . $button_text . "</span>";
                    echo '</button>';
                    echo '</a>
                    <div id="myModal" class="modal">
                        <div class="modal-content-test">
                            <div class="modal-header">
                                <span class="close">&times;</span>
                                <h2 style="text-align:center;">'. $row['name'] .'</h2>
                            </div>
                            <div class="modal-body">
                            <br>
                            <form action="progress.php" method="post">
                            <input type="hidden" name="sid" value="' . $row['student_id'] . '">
                            <button type="submit" class="button-progress" name="progress" value="progress"">See students progress</button><br><br>
                            </form>
                            <form action="delete_from_group.php" method="post">
                            <input type="hidden" name="sid" value="' . $row['student_id'] . '">
                            <input type="hidden" name="group_id" value="' . $gid . '">
                            <a href="#" class="myBtn">
                            <button type="button" class="button-delete" name="delete" value="delete">Remove student from group</button><br><br>
                                        </a>
                                        <div id="myModal" class="modal">
                                            <div class="modal-content-test">
                                                <div class="modal-header">
                                                    <span class="close">&times;</span>
                                                    <h2>Are you sure you want to delete this student from the student group?</h2>
                                                </div>
                                                <div class="modal-body">
                                                <button type="submit" class="button-delete">Delete</button>
                                                </div>
                                            </div>
                                        </div>
                    
                            </form>
                    
                            </div>
                        </div>
                        </div>';
                
                    echo '<br>';

                    echo '<input type="hidden" name="name" value="' . $row["name"] . '" />';
                    echo '<input type="hidden" name="group_id" value="' . $_GET['group_ID'] . '" />';

                  }
                }
                  $ids = implode(',', $ids);
                  echo '<form action="add_to_group.php" method="post">
                  <input type="hidden" name="group_id" value="' . $_GET['group_ID'] . '">
                  <input type="hidden" name="student_ids" value="' . $ids . '">
                  <button type="submit" name="add" id="editbtn" class="button">Edit Group</button>
                  </form>';
                  echo '<br>';
                  echo '<form action="delete_group.php" method="post">';
                  echo '<input type="hidden" name="group_id" value="' . $_GET['group_ID'] . '">';
                  echo '<a href="#" class="myBtn">
                  <button type="button" name="delete" id="delbtn" value="delete">Delete Group</button><br><br>
                              </a>
                              <div id="myModal" class="modal">
                                  <div class="modal-content-test">
                                      <div class="modal-header">
                                          <span class="close">&times;</span>
                                          <h2>Are you sure you want to delete this student group?</h2>
                                      </div>
                                      <div class="modal-body">
                                      <button class="submit" id="delbtn">Delete Group</button>
                                      </div>
                                  </div>
                              </div>
          
                  </form>';  
                }

              }
              $pdo = null;
              
                ?>
              </div>
            </div>
          </div>
          <!-- </div> -->
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
        for (let index = 0; index < modal.length; index++) {
            if (event.target == modal[index]) {
                modal[index].style.display = "none";
            }
        }
    }
</script>

</html>