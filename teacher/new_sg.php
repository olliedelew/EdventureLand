<?php
session_start();
if(!isset($_SESSION['user']) || !isset($_SESSION['school_id']) || !isset($_SESSION['teacher_id'])){
    echo '<script>location.href = "../login.php";</script>';
}
include '../connection.php';

?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
	<title>Create Student Group</title>
	<style type="text/css">
        .button6 {
            display: inline-block;
            padding: 14px 30px;
            font-size: 18px;

            color: white;
            background-color: blue;
            text-align: center;
            cursor: pointer;
            width: 100%;
        }


        .button6:hover {
            background-color: #555;
        }

        .button7 {
            display: inline-block;
            padding: 14px 30px;
            font-size: 18px;

            color: white;
            background-color: blue;
            text-align: center;
            cursor: pointer;
            width: 100%;
        }


        .button7:hover {
            background-color: #555;
        }

        #subbtn{
            background-color: green;
        }

	</style>
    <link rel="stylesheet" href="../style.css" />
</head>
<body>
<div class="container">
<h1>Create Student Group</h1>
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
                      <button type="button" class="button" onclick="location.href='groups.php'" id="activated">Student Groups</button><br><br> <!-- Student groupmates -->
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

<?php 

    ?>


  <!-- Profile pic -->

  <div class="row">
  <div class="col-sm-6">
  <h1 style='text-align: center;'>Students Selected</h1>

  <div class="card">
      <div class="card-body">
      <form class="form" action="create_sg.php" method="post" id="create_group" name="create_group">
            <input type="text" class="form-control" id="sg_name" name="sg_name" placeholder="Student Group Name"><br>
            <p name = "ingList" id = "ingList"></p>
            <input type="hidden" id="ingHidden" name = "ingHidden" value = "">
            <button type="submit" class="button" id="subbtn" name="Create"  value="Create Group">Create Group</button><br><br>
      </form>
    </div>
    </div>
  </div>
  <div class="col-sm-6">
  <h1 style='text-align: center;'>Select From These Student</h1>

    <div class="card">
      <div class="card-body" id="hello">
        <?php
            // Do a search for all student groups relating to a specific teacher 

            // Sort students by alphabetial order
            $sql = "SELECT * FROM student WHERE school_id = :school_id ORDER BY student_year ASC, name ASC"; //WHERE teacher_id = $_SESSION[isStaff] then get student_id where it is the same as group id
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'school_id' => $_SESSION['school_id']
            ]);
        
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            while($row = $stmt->fetch()){
                if($row['student_year'] == 7){
                    echo '<button type="button" class="button6" style="background-color:#FFAF77;" name="'. $row["name"].'"  value="'. $row["name"].'" id="'.$row['student_id'].'">' . $row["name"] . ' - YEAR ' . $row['student_year'] . '</button>';
                } elseif($row['student_year'] == 8){
                    echo '<button type="button" class="button6" style="background-color:#FFA07A;" name="'. $row["name"].'"  value="'. $row["name"].'" id="'.$row['student_id'].'">' . $row["name"] . ' - YEAR ' . $row['student_year'] . '</button>';
                }  elseif($row['student_year'] == 9){
                    echo '<button type="button" class="button6" style="background-color:#FF7F50;" name="'. $row["name"].'"  value="'. $row["name"].'" id="'.$row['student_id'].'">' . $row["name"] . ' - YEAR ' . $row['student_year'] . '</button>';
                }  elseif($row['student_year'] == 10){
                    echo '<button type="button" class="button6" style="background-color:#FF6347;" name="'. $row["name"].'"  value="'. $row["name"].'" id="'.$row['student_id'].'">' . $row["name"] . ' - YEAR ' . $row['student_year'] . '</button>';
                }  elseif($row['student_year'] == 11){
                    echo '<button type="button" class="button6" style="background-color:#FF4500;" name="'. $row["name"].'"  value="'. $row["name"].'" id="'.$row['student_id'].'">' . $row["name"] . ' - YEAR ' . $row['student_year'] . '</button>';
                } 
            }
            $pdo = null;
            ?>
                  <p name = "MingList" id = "MingList"></p>

      </div>
    </div>
  </div>
</div>
</div>
<script>



const Form = document.getElementById('create_group');
  Form.addEventListener('submit', e => {
    e.preventDefault();
    if(document.getElementById('ingHidden').value.length < 1){
        alert('You must select students to be part of the group!');
        return false;
    }
    if(document.getElementById('sg_name').value == ''){
        alert('Must input a Student Group name');
        return false;
    }
    if(document.getElementById('sg_name').value.length > 30){
        alert('Student Group name too long');
        return false;
    }
    if(document.getElementById('sg_name').value.length < 4){
        alert('Student Group name too short');
        return false;
    }
    Form.submit();
  });

        var g_var = [];
        $(document).on('click', '.button6', function() {
            var node = document.createElement("button");
            node.setAttribute("class", "button7");
            node.setAttribute("value", $(this).attr("value"));
            node.setAttribute("id", $(this).attr("id"));
            node.setAttribute("style", "background-color: " + $(this).css("background-color") + ";");
            var year = $(this).text().split(' - ')[1];
            var textnode = document.createTextNode($(this).attr("value") + ' - ' + year);
            node.appendChild(textnode);
            document.getElementById("ingList").appendChild(node);
            g_var.push($(this).attr('id'));
            document.getElementById("ingHidden").value = g_var;
            $(this).remove();
        });
        
        $(document).on('click', '.button7', function() {
            var savedName = $(this).attr("id");
            var node = document.createElement("button");
            node.setAttribute("class", "button6");
            node.setAttribute("value", $(this).attr("value"));
            node.setAttribute("id", $(this).attr("id"));
            node.setAttribute("style", "background-color: " + $(this).css("background-color") + ";");
            var year = $(this).text().split(' - ')[1];
            var textnode = document.createTextNode($(this).attr("value") + ' - ' + year);
            node.appendChild(textnode);
            document.getElementById("MingList").appendChild(node);
            var list = document.getElementById("ingHidden");
            var index = g_var.indexOf($(this).attr("id"));
            g_var.splice(index, 1);
            document.getElementById("ingHidden").value = g_var;

            $(this).remove();
        });

    </script>

</body>
</html>