<?php
session_start();
if(!isset($_SESSION['user']) || !isset($_SESSION['school_id']) || !isset($_SESSION['teacher_id'])){
    echo '<script>location.href = "../login.php";</script>';
}

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <title>Assign Formula Frenzy</title>

    <!-- Bootstrap Date-Picker Plugin -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css" />
    <link rel="stylesheet" href="../style.css" />
    <style>
          *{
    font-size: 20px;
  }
      input {
      padding: 15px;
      border: solid black;
    }

    textarea {
    resize: none;
  }
form{
  text-align: left;
}
.center {
  display: block;
  margin: 0 auto;
  text-align: center;
}

.texty {
  width: 30%;
  padding: 12px 20px;
  margin: 0px 0;
  display: inline-block;
  border: 1px solid black;
  box-sizing: border-box;
}

#test{
  font-size: 20px;
  color: #23234c;
  text-align: center;
  padding: 0 4px;
  border: 3px solid black;
  margin: 0 5px;
}

    </style>
</head>

<body>
    <div class="container">
        <h1>Create a Formula Frenzy Assignment</h1>

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
        <form id="myForm" class="form" action="assignment_set.php" method="post">
            <div class="container">

                <?php
                if(isset($_SESSION['POST']['group_ID'])){
                    $gid = intval($_SESSION['POST']['group_ID']);
                } else {
                    header('location: set_assignment.php');
                }
                echo '<input type="hidden" name="type" value="math" >';

                echo '<input type="hidden" name="group_ID" value="'. $gid.'">';
                ?>
                <br>
                <p style="text-align:left;">Formula Frenzy is an engaging educational game designed to help students improve their math skills. This game challenges students to fill in the empty input in various formulas involving addition, multiplication, division, and subtraction (depending on what you choose). The game provides helpful hints to guide students as they get more questions right.</p>

                <p style="text-align:left;">The game is points-based, and there are three difficulty levels to choose from. Each difficulty level provides longer formulas, so you can test students and customise their gameplay according to their skill level.</p>

                <p style="text-align:left;">As a teacher, you can use Formula Frenzy as a fun and engaging way to supplement your math curriculum. The game provides an opportunity for students to practice their math skills in a way that feels like play, making learning more enjoyable and effective. You can also use the game to track your students' progress and identify areas where they may need additional support.</p>

                <p style="text-align:left;">Overall, Formula Frenzy is an excellent addition to any math curriculum, providing a fun and engaging way for students to practice and improve their math skills.</p>


                <label for="title">Title</label><br>
                <input type="text" class="texty" name="title" id="title" placeholder="Enter the title of the assignment" required /><br>
                <br><label for="msg">Description</label><br>
                <textarea class="form-control" style="font-size:20px;" placeholder="Description of assignment" name="desc" id="desc" rows="7" cols="60" maxlength="2000" required></textarea>
                <div id="charCount"></div>

                <label for="points">Maximum Points On Completion</label>
                <input type="number" class="login-input" name="points" value="10" max="500" min="10" required /><br>

                <?php
      date_default_timezone_set('Europe/London');
      if(date("H") >= 18){
        $date = date("d-m-Y", strtotime('tomorrow'));
      } else {
        $date = date("d-m-Y");
      }
      ?>
      <br><div id="date-picker-example" class="md-form md-outline input-with-post-icon datepicker" inline="true">
        <label class="control-label" for="date">Due Date</label>
        <input class="login-input" id="date" name="date" placeholder="MM-DD-YYYY" type="text" value=<?php echo $date?> required/>
      </div>
      <br><label for="time">Time</label>
                <input type="time" id="time" name="time" value="18:00" required><br>
                <!-- Make sure checklists cannot be empty!!!!!! -->
                <br><label for="check_list">Which operator(s) do you want the students to be assessed with?</label><br>
                <input type="checkbox" name="check_list[]" value="add"> Addition + <br>
                <input type="checkbox" name="check_list[]" value="minus"> Subtraction - <br>
                <input type="checkbox" name="check_list[]" value="div"> Division / <br>
                <input type="checkbox" name="check_list[]" value="mult"> Multiplication * <br>
                <br><label for="difficulty">Pick the difficulty</label>
                <select id="difficulty" name="difficulty" onChange="showBoxes(this);">
                    <option value="easy">Easy (2 terms)</option>
                    <option value="medium">Medium (3 terms)</option>
                    <option value="hard">Hard (4 terms)</option>
                </select><br>
                <div id="easy-box" style="display:block;">
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
                </div>
                <div id="medium-box" style="display:none;">

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
                </div>
                <div id="hard-box" style="display:none;">

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

                <br>
                <br>
                <label for="timer">How much time do you want to give them?</label><br>
                <input type="number" name="timer" max=10 min=1 value=5 required>Minutes<br>

                <br><label for="mincorrect">Minimum number of questions answered correctly to pass (so you can't pass by getting 1/1 (100%))</label><br>
                <input type="number" name="mincorrect" max=150 min=1 value=5 required>Questions<br>

                <br><label for="pass">Decide the pass percentage (what percentage they need to reach to pass the assignment) (they will not be told this)</label><br>
                <input type="number" name="pass" max=100 min=0 value=60 required>%<br><br>

                <button type="submit" class="login-button">Submit Assignment</button>
            </div>
        </form>

    </div>
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
        });

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

            if(document.getElementById('desc').value.length < 4) {
            // Title too short!
            alert('description too short');
            return false;
            }
            if(document.getElementById('desc').value.length > 2000) {
            // Title too long!
            alert('description too long');
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
                // alert(date);
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