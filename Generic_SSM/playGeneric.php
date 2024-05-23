<?php
session_start();
if (!isset($_SESSION['user']) || !isset($_SESSION['school_id']) || !isset($_SESSION['student_id'])) {
  echo '<script>location.href = "../login.php";</script>';
}
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>Play SSM</title>
  <!-- Add in bootstrap and my stylesheets -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="../style.css" />
  <link rel="stylesheet" href="style.css" />


</head>

<body>
  <?php
  // These are created for validaiton checking the get variables
  $subjects = array('history', 'chemistry', 'biology', 'geography', 'physics', 'general_knowledge');
  $topics = array(
    "The_cold_war", "World_war_one", "World_war_two", "Atoms_elements_and_compounds", "The_periodic_table",
    "Chemical_formulas_and_reactions", "The_cell_and_its_structure", "The_human_body", "Microorganisms",
    "Photosynthesis", "Effect_of_environment_on_organisms", "Evolution", "Physical_geography",
    "Human_geography", "Natural_resources", "Energy_and_energy_resources", "Forces_and_motion",
    "Waves_and_wave_properties", "Light", "Astrophysics_and_cosmology", "General_knowledge"
  );
  $difficulties = array("easy", "medium", "hard");

  // Some validation checks for the get variables
  if (count($_GET) != 3) {
    echo '<script>location.href = "../student/student_homepage.php";</script>';
  } elseif (!isset($_GET['difficulty']) || !isset($_GET['topic']) || !isset($_GET['subject'])) {
    echo '<script>location.href = "../student/student_homepage.php";</script>';
  } elseif (!in_array($_GET['difficulty'], $difficulties)) {
    echo '<script>location.href = "../student/student_homepage.php";</script>';
  } elseif (!in_array($_GET['topic'], $topics)) {
    echo '<script>location.href = "../student/student_homepage.php";</script>';
  } elseif (!in_array($_GET['subject'], $subjects)) {
    echo '<script>location.href = "../student/student_homepage.php";</script>';
  }
  ?>
  <!-- Create the with the action to upload the user to the leaderboard -->
  <form action="upload_to_leaderboard.php" method="post">
    <div class="container" style="text-align:center;">
      <u><h1 id="title">SUBJECT SAVVY MILLIONAIRE: HISTORY QUIZ </h1></u>
      <br>
      <!-- Here is the question numbers -->
      <div class="row">
        <div class="col-md-9">
          <h1>Question <span class="questNum"></span> of <span class="totQuest"></span></h1>
        </div>
      </div>
      
      <!-- The loading symbol is created here -->
      <div style="display: none" id="loading-overlay">
        <div id="loading_symbol"></div>
        <p id="loading-text">Please wait around 40 seconds while the questions are generated...</p>
      </div>
<br>
      <!-- These are the hidden inputs that will eventually be sent to the leaderboard -->
      <input type="hidden" id="points" name="points" value="">
      <input type="hidden" id="lifelines_used" name="lifelines_used" value="">
      <input type="hidden" id="questions_right" name="questions_right" value="">
      <input type="hidden" id="topic" name="topic" value="">
      <input type="hidden" id="subject" name="subject" value="">
      <input type="hidden" id="difficulty" name="difficulty" value="">

      <input type="hidden" id="hiddenquestion" name="hiddenquestion" value="">
      <input type="hidden" id="hiddenanswer1" name="hiddenanswer1" value="">
      <input type="hidden" id="hiddenanswer2" name="hiddenanswer2" value="">
      <input type="hidden" id="hiddenanswer3" name="hiddenanswer3" value="">
      <input type="hidden" id="hiddenanswer4" name="hiddenanswer4" value="">
      <input type="hidden" id="hiddencorrectanswer" name="hiddencorrectanswer" value="">
      <input type="hidden" id="hiddenhint" name="hiddenhint" value="">
      <div class="col-md-9">
        
      <!-- This is the timer pre-set to 15-->
        <p><b id="timer" style="font-size: 2em;">15</b></p>

        <!-- Here are the 3 lifelines the student is given -->
        <div class="row">
          <div class="col-md-4">
            <!-- The 50/50 button -->
            <button type="button" id="fiddyfiddy" onclick="fiftyfifty();">
              <img src="fiftyfifty.png" alt="fiftyfifty" style="width:100px; height:100px;" />
            </button>
          </div>

          <div class="col-md-4">
            <!-- The Hint button -->
            <button type="button" id="hints" onclick="show_hint();">
              <img src="hint.png" alt="hint" style="width:100px; height:100px;" />
            </button>
          </div>

          <div class="col-md-4">
            <!-- The check answer button -->
            <button type="button" id="check">
              <img src="life.png" alt="life" style="width:100px; height:100px;" />
            </button>
          </div>
        </div>
        <br>
        <!-- This is where the generated question is put in -->
        <div class="question" style="background-color: white; padding: 20px; border: 5px solid black; font-weight: bold;">
        </div>
        <br>
        <!-- These rows and columns are where the different potential answers are set -->
        <div class="row">
          <div class="col-md-6">
            <button type="button" id="a" onclick="next(this.id)" class="button_test">A)</button><br>
          </div>
          <div class="col-md-6">
            <button type="button" id="b" onclick="next(this.id)" class="button_test">B)</button><br>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <button type="button" id="c" onclick="next(this.id)" class="button_test">C)</button><br>
          </div>
          <div class="col-md-6">
            <button type="button" id="d" onclick="next(this.id)" class="button_test">D)</button><br>
          </div>
        </div>
        <!-- Here is where the hint is input if clicked -->
        <input type="text" id="hint_id" class="option5" value="" style="text-align:center; padding: 15px; font-size: 15px; display: none; margin-bottom: 15px;" readonly />
        <!-- These are the buttons that allow the user to go back to previous answers -->
        <div class="row">
          <div class="col-md-1">
            <button type="button" id="1" onclick="goBack(this.id)" class="hide">1</button>
          </div>
          <div class="col-md-1">
            <button type="button" id="2" onclick="goBack(this.id)" class="hide">2</button>
          </div>
          <div class="col-md-1">
            <button type="button" id="3" onclick="goBack(this.id)" class="hide">3</button>
          </div>
          <div class="col-md-1">
            <button type="button" id="4" onclick="goBack(this.id)" class="hide">4</button>
          </div>
          <div class="col-md-1">
            <button type="button" id="5" onclick="goBack(this.id)" class="hide">5</button>
          </div>
          <div class="col-md-1">
            <button type="button" id="6" onclick="goBack(this.id)" class="hide">6</button>
          </div>
          <div class="col-md-1">
            <button type="button" id="7" onclick="goBack(this.id)" class="hide">7</button>
          </div>
          <div class="col-md-1">
            <button type="button" id="8" onclick="goBack(this.id)" class="hide">8</button>
          </div>
          <div class="col-md-1">
            <button type="button" id="9" onclick="goBack(this.id)" class="hide">9</button>
          </div>
          <div class="col-md-1">
            <button type="button" id="10" onclick="goBack(this.id)" class="hide">10</button>
          </div>
          <div class="col-md-1">
            <button type="button" id="11" onclick="goBack(this.id)" class="hide">11</button>
          </div>
          <div class="col-md-1">
            <button type="button" id="12" onclick="goBack(this.id)" class="hide">12</button>
          </div>
        </div>
        <br>
        <div class="row">
          <br>
          <br>
          
          <div class="centerbutton">

            <div class="center">
              <!-- This is the button to go back to the current question -->
              <button type="button" id="current" onclick="goBack(this.id)" class="hide">Current Question</button>
              <br>
              <br>
            </div>
          </div>
        </div>
        <!-- When the game is over, this is displayed -->
        <div id="result" style="display: none">
          <!-- Show the score and points -->
          <!-- <h1>You got: </h1> -->
          <h1>You got <span id="result_score"></span> Correct</h1>
          <h2 id="pointer">Points: </h2>
          <!-- Two choices, get put on the leaderboard or go back to the homepage -->
          <div class="row">
            <div class="col-md-6">
              <button type="submit" class="btn">Get put on the leaderboard</button></a>
            </div>
            <div class="col-md-6">
              <button type="button" onclick="location.href='../student/student_homepage.php'" class="btn">Home</button>
            </div>
          </div>
          <br>
        </div>
      </div>
      
      <!-- Here is where the 1,000,000 point tower is created  -->
      <div class="col-md-3">
        <div id="rectangles">
          <div class="rectangle rectangle-12">1,000,000</div>
          <div class="rectangle rectangle-11">500,000</div>
          <div class="rectangle rectangle-10">250,000</div>
          <div class="rectangle rectangle-9">125,000</div>
          <div class="rectangle rectangle-8">64,000</div>
          <div class="rectangle rectangle-7">32,000</div>
          <div class="rectangle rectangle-6">16,000</div>
          <div class="rectangle rectangle-5">8,000</div>
          <div class="rectangle rectangle-4">4,000</div>
          <div class="rectangle rectangle-3">2,000</div>
          <div class="rectangle rectangle-2">1,000</div>
          <div class="rectangle rectangle-1">500</div>
        </div>
      </div>

  </form>
  <!-- Here we call the config file for API keys and the script file for the game code -->
  <script src="../config.js"></script>
  <script src="script.js"></script>
</body>

</html>