<?php
session_start();
if (!isset($_SESSION['user']) || !isset($_SESSION['school_id']) || !isset($_SESSION['student_id'])) {
  echo '<script>location.href = "../login.php";</script>';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Formula Frenzy</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="style.css" />
</head>

<body>
  <?php
  $operators = array('minus', 'mult', 'add', 'div');
  $difficulties = array("easy", "medium", "hard");

  if (count($_GET) != 2) {
    echo '<script>location.href = "../student/student_homepage.php";</script>';
  } elseif (!isset($_GET['difficulty']) || !isset($_GET['check_list'])) {
    echo '<script>location.href = "../student/student_homepage.php";</script>';
  } elseif (!in_array($_GET['difficulty'], $difficulties)) {
    echo '<script>location.href = "../student/student_homepage.php";</script>';
  } elseif (!is_array($_GET['check_list'])) {
    echo '<script>location.href = "../student/student_homepage.php";</script>';
  } elseif (count($_GET['check_list']) > 4 || count($_GET['check_list']) < 1) {
    echo '<script>location.href = "../student/student_homepage.php";</script>';
  }

  for ($i = 0; $i < count($_GET['check_list']); $i++) {
    if (!in_array($_GET['check_list'][$i], $operators)) {
      echo '<script>location.href = "../student/student_homepage.php";</script>';
    }
  }

  $difficulty = $_GET['difficulty'];
  $op = $_GET['check_list'];
  // echo var_dump($op);
  $newop = join(", ", $op); //done
  echo '<input type="hidden" id = "operators" name = "operators" value = "' . $newop . '">';
  echo '<input type="hidden" id = "difficulty" name = "difficulty" value = "' . $difficulty . '">';

  ?>
  <div class="row">
    <h1 style="text-align:center;"><b><u>Formula Frenzy</u></b></h1>
  </div>
  <div class="col-md-3">
    <div class="row">
      <div class="box">
        <div id="points-words"><b>Points</b></div>
        <div id="points-test">0</div>
      </div>
    </div>
    <div class="row">
      <div class="box">
        <div id="points-words"><b>Hints</b></div>
        <div id="hints-number">5</div>
      </div>
    </div>
  </div>
  <div class="col-md-8">
    <div class="boxy">
      <h3 id="round"></h3>
      <div class="row">
        <div class="col-md-4">
        </div>
        <div class="col-md-3">
          <h3 id="timer">15</h3>
        </div>
        <div class="col-md-5">
          <button type="button" id="hints">Get A Hint</button>
        </div>
      </div>
      <div id="equation"></div>
      <button type="button" id="submit">Check Answer</button>
      <p id="error">Error</p>
    </div>
  </div>

  <div class="results-box">
    <form action="upload_to_leaderboard.php" method="post">
      <!-- Want to upload: score, name, correct, incorrect, questions per minute, total questions? percentage? -->
      <input type="hidden" id="score_hidden" name="score_hidden" />
      <input type="hidden" id="correct_ones" name="correct_ones" />
      <input type="hidden" id="incorrect_ones" name="incorrect_ones" />
      <input type="hidden" id="difficulty_sent_off" name="difficulty_sent_off" value="<?php echo $_GET['difficulty']; ?>" />
      <input type="hidden" id="url" name="url" value="<?php echo $_SERVER['QUERY_STRING']; ?>" />
      <input type="hidden" id="opps" name="opps" />
      <h1 id="score">SCORE:<span class="score_value"></span></h1>
      <br>
      <br>
      <br>
      <div class="row">
        <div class="col-md-6">
          <button id="upload" type="submit" class="center">Upload to Leaderboard</button>
        </div>
        <div class="col-md-6">
          <button id="again" type="button" onClick="window.location.reload(true)" class="center">Play Again</button>
        </div>
      </div>
    </form>
    <br>
    <br>
    <div id="reveal_results">
      <div class="col-md-4">
      </div>
      <div class="col-md-6">
        <div id="answers_presented" style="text-align:left;">
        </div>
      </div>
      <div class="col-md-2">
      </div>
    </div>
  </div>
  <script src="script.js"></script>
</body>

</html>