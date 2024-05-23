<?php
session_start();
if(!isset($_SESSION['user']) || !isset($_SESSION['school_id']) || !isset($_SESSION['teacher_id'])){
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <title>Update Assignment</title>
    <!-- Bootstrap Date-Picker Plugin -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css" />
    <style type="text/css">
        *{
            font-size: 20px;
        }
        textarea {
            resize: none;
        }
        input {
        padding: 15px;
        border: solid black;
        }
        .center {
        display: block;
        margin: 0 auto;
        text-align: center;
        }

    #test{
  font-size: 20px;
  color: #23234c;
  text-align: center;
  padding: 0 4px;
  border: 3px solid black;
  margin: 0 5px;

}

.texty {
  width: 30%;
  padding: 12px 20px;
  margin: 0px 0;
  display: inline-block;
  border: 1px solid black;
  box-sizing: border-box;
}
#sub{
  background-color: green;
}
    </style>
    <link rel="stylesheet" href="../style.css" />
</head>
<body>
<div class="container" style="text-align:left;">
    <h1 style="text-align:left;">Update Assignment</h1>
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
                      <button type="button" class="button" id="activated" onclick="location.href='assset.php'">Assignments Set</button><br><br>
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
if(!isset($_POST['math_id'])){
    $pdo = null;
    header('location: assset.php');
}
$id = $_POST['math_id'];
$sql = "SELECT * FROM math_assignment WHERE math_assignment_id = :math_assignment_id";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    'math_assignment_id' => $id
]);

$stmt->setFetchMode(PDO::FETCH_ASSOC);
while ($row = $stmt->fetch()) {
    $datetime = explode(" ", $row['test_datetime']);
    $date = date("d-m-Y", strtotime($datetime[0]) );
    $time = date("H:i", strtotime($datetime[1]) );
    $operators = json_decode($row['operators']);
    echo '<form id = "myForm" method = "post" action="updateSql.php">';
    $explodedURL = explode('?', $_POST['url']);
    echo '<input type="hidden" name="url" value="' . $explodedURL[1] . '">';
        echo '<input type="hidden" name="math_id" id="math_id" value=' . $id . '>';
        echo '<input type="hidden" name="type" id="type" value="math">';
        echo '<br>
            <label for="title">Math title:</label><br>
			<input type="text" class="texty" id="title" name="title" placeholder="Enter the title of the assignment" value="' . $row['title'] . '" required /><br><br>';
            $description = str_replace("<br />", "", $row['description']);
    echo ' <label for="desc">Description</label><br>
            <textarea class="form-control" style="font-size:20px;" placeholder="Description of assignment" name="desc" id="desc" rows="7" cols="60" maxlength="2000" required>' . $description . '</textarea>
            <div id="charCount"></div>';
    echo '<label for="points">Maximum Points On Completion</label>
             <input type="number" class="login-input" name="points" value="' . $row['points'] . '"  max="500" min="10" required /><br>';

    echo '<br><div id="date-picker-example" class="md-form md-outline input-with-post-icon datepicker" inline="true">
                 <label class="control-label" for="date">Due Date</label>
                 <input class="login-input" id="date" name="date" placeholder="MM-DD-YYYY" type="text" value="' . $date . '" required/>
            </div>';
    echo '<br><label for="time">Time</label>
             <input type="time" id="time" name="time" value="' . $time . '" required><br>';

    echo '<br><label for="check_list">Which operator(s) do you want the students to be assessed with?</label><br>
             <input type="checkbox" name="check_list[]" value="add"';
              if (in_array('add', $operators)) { echo 'checked'; } 
            echo '> Addition +<br><input type="checkbox" name="check_list[]" value="minus"';
            if (in_array('minus', $operators)) { echo 'checked'; } 
             echo '> Subtraction -<br><input type="checkbox" name="check_list[]" value="div"';
             if (in_array('div', $operators)) { echo 'checked'; } 
             echo '> Division /<br><input type="checkbox" name="check_list[]" value="mult"';
             if (in_array('mult', $operators)) { echo 'checked'; }  
             echo '> Multiplication *<br>';
    echo '
             <br><label for="difficulty">Pick the difficulty:</label>
             <select name="difficulty" id="difficulty" onChange="showBoxes(this);">';
             echo '<option value="easy" ';
             if ($row['difficulty'] == 'easy') { echo 'selected'; }
           echo '>Easy (2 boxes)</option><option value="medium"';
           if ($row['difficulty'] == 'medium') { echo 'selected'; }
           echo '>Medium (3 boxes)</option><option value="hard"';
           if ($row['difficulty'] == 'hard') { echo 'selected'; }
           echo '>Hard (4 boxes)</option>';
           echo '</select><br>';

           echo '<div id="easy-box" style="';
           if ($row['difficulty'] == 'easy') { echo 'display:block;'; } else {
            echo 'display:none;';
           }
           echo '">
           <div class="col-md-5">
               <p>Example (operators depend on what you pick):</p>
               <div class="row">
               <div class="col-md-3">
               </div>

                   <div class="col-md-1">
               <p>2</p>
               </div>
               <div class="col-md-1">
               <p>+</p>
               </div>
               <div class="col-md-1">
               <span id="test">?</span>
               </div>
               <div class="col-md-1">

               <p>=</p>
               </div>

               <div class="col-md-1">

               <p>5</p>
               </div>
               <div class="col-md-4">
               </div>

               </div>
               </div>
           <div class="col-md-3">
           </div>
           <div class="col-md-4">
           </div>
           </div>';
           echo '<div id="medium-box" style="';
           if ($row['difficulty'] == 'medium') { echo 'display:block;'; } else {
            echo 'display:none;';
           }
           echo '">

           <div class="col-md-5">
           <p>Example (operators depend on what you pick):</p>

               <div class="row">
               <div class="col-md-2">
               </div>

                   <div class="col-md-1">
               <p>2</p>
               </div>
               <div class="col-md-1">
               <p>+</p>
               </div>
               <div class="col-md-1">
               <p>(<span id="test">?</span></p>
               </div>
               <div class="col-md-1">

               <p>*</p>
               </div>

               <div class="col-md-1">
               <p>5)</p>

               </div>
               <div class="col-md-1">
               <p>=</p>

               </div>
               <div class="col-md-1">
               <p>27</p>

               </div>

               <div class="col-md-3">
               </div>

               </div>
           </div>
           <div class="col-md-3">
           </div>
           <div class="col-md-4">
           </div>
           </div>';
           echo '<div id="hard-box" style="';
           if ($row['difficulty'] == 'hard') { echo 'display:block;'; } else {
            echo 'display:none;';
           }
           echo '">
           <div class="col-md-5">
           <p>Example (operators depend on what you pick):</p>

               <div class="row">
               <div class="col-md-1">
               </div>
                   <div class="col-md-1">
               <p>2</p>
               </div>
               <div class="col-md-1">
               <p>+</p>
               </div>
               <div class="col-md-1">
               <p>(<span id="test">?</span></p>
               </div>
               <div class="col-md-1">

               <p>*</p>
               </div>

               <div class="col-md-1">
               <p>5)</p>

               </div>
               <div class="col-md-1">
               <p>-</p>

               </div>
               <div class="col-md-1">
               <p>14</p>

               </div>
               <div class="col-md-1">
               <p>=</p>

               </div>
               <div class="col-md-1">
               <p>13</p>

               </div>
               <div class="col-md-2">
               </div>

               </div>
           </div>
           <div class="col-md-3">
           </div>
           <div class="col-md-4">
           </div>
           </div>

           <br><br>

           <br>';
    echo '<label for="timer">How much time do you want to give them?</label><br>
            <input type="number" name="timer" max=10 min=1 value="' . $row['duration'] . '" required>Minutes<br>';
            echo '

            <br><label for="mincorrect">Minimum number of questions answered correctly to pass (so you can\'t pass by getting 1/1 (100%))</label><br>
            <input type="number" name="mincorrect" max=150 min=1 value=' . $row['min_no_questions'] . ' required>Questions<br>';
    echo '<br><label for="pass">Decide the pass percentage (what percentage they need to reach to pass the assignment) (they will not be told this)</label><br>
            <input type="number" name="pass" max=100 min=0 value="' . $row['pass_percentage'] . '" required>%<br><br>';
    echo '<button type="submit" id="sub">Update</button>';
    echo '</form>';
}

$pdo = null;
?>

<script>
      const textarea = document.getElementById("desc");
const charCount = document.getElementById("charCount");
const maxChars = 2000;

textarea.addEventListener("input", function() {
  const currentChars = textarea.value.length;
  charCount.textContent = `${currentChars}/${maxChars}`;
});
            function showBoxes(selected) {
            if(selected.options[selected.selectedIndex].value == 'easy'){
                document.getElementById("easy-box").style.display = "block";
                document.getElementById("medium-box").style.display = "none";
                document.getElementById("hard-box").style.display = "none";
            } else if(selected.options[selected.selectedIndex].value == 'medium'){
                document.getElementById("medium-box").style.display = "block";
                document.getElementById("easy-box").style.display = "none";
                document.getElementById("hard-box").style.display = "none";
            } else if(selected.options[selected.selectedIndex].value == 'hard'){
                document.getElementById("hard-box").style.display = "block";
                document.getElementById("medium-box").style.display = "none";
                document.getElementById("easy-box").style.display = "none";
            }
            }           

        $(document).ready(function() {
            var date_input = $('input[name="date"]'); //our date input has the name "date"
            var container = $('.bootstrap-iso form').length > 0 ? $('.bootstrap-iso form').parent() : "body";
            var options = {
                format: 'dd-mm-yyyy',
                container: container,
                todayHighlight: true,
                autoclose: true,
                startDate: "today",
                clearBtn: false,
            };
            date_input.datepicker(options);
        })
        const Form = document.getElementById('myForm');
        Form.addEventListener('submit', e => {

            e.preventDefault();
            if(document.getElementById('title').value.length < 4) {
            // Title too short!
            alert('Title too short, must be greater than 4 characters');
            return false;
            }
            if(document.getElementById('title').value.length > 30) {
            // Title too long!
            alert('Title too long, must be less than 30 characters');
            return false;
            }
            if(document.getElementById('desc').value.length > 2000){
                alert('description too long');
                return false;
            } 
            if(document.getElementById('desc').value.length < 5){
                alert('description too short');
                return false;
            } 
            if(document.getElementById('date').value == ''){
                alert('Must input a date');
                return false;
            } else {
                const regex = /^\d{2}-\d{2}-\d{4}$/;
                if (!regex.test(document.getElementById('date').value)) {
                    return false; // Invalid format
                }
                const parts = document.getElementById('date').value.split("-");
                const year = parseInt(parts[2], 10);
                const month = parseInt(parts[1], 10); // Months are zero-indexed
                const day = parseInt(parts[0], 10);
                const date = new Date(year, month, day);
                const now = new Date();
                const inputtedDateStr = year + '-' + month + '-' + day + ' ' + document.getElementById('time').value; // '2023-03-15 12:00:00'; // example inputted date
                const inputtedDate = new Date(inputtedDateStr); // create a new Date object for the inputted date
                if (now.getTime() > inputtedDate.getTime()) {
                alert('Date is too early');
                    return false;
                } 
            }


            const checkboxes = document.querySelectorAll('input[type="checkbox"]');

            var counter = 0;
            // Loop through checkboxes
            for (let i = 0; i < checkboxes.length; i++) {
            const checkbox = checkboxes[i];

            // Check if checkbox is checked
            if (checkbox.checked) {
                counter += 1;
                // console.log(`Checkbox ${checkbox.value} is selected.`);
            }
            }
            if(counter == 0){
                alert('Please select some operators before submitting');
                return false;
            } else {
                Form.submit();
            }


            });

    </script>

</body>
</html>
