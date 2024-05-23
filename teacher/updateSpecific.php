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
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <title>Update Assignment</title>
    <!-- Bootstrap Date-Picker Plugin -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css" />
    <style>

      *{
        font-size: 20px;
      }
  textarea {
    resize: none;
  }

		.uploadbtn {
		    background-color: green;
		    color: white;
		    padding: 16px;
		    width: 100%;
		}
    .center {
  display: block;
  margin: 0 auto;
  text-align: center;
}
.col-md-4 {
  border: 5px solid black;
  }

    input {
      padding: 15px;
      border: solid black;
    }
.inp{
        width: 100%;
}
.texty {
  width: 50%;
  padding: 12px 20px;
  margin: 0px 0;
  display: inline-block;
  border: 1px solid black;
  box-sizing: border-box;
}
.sub{
  background-color: green;
}

.answer{
  width: 100%;
}
#loading-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(0, 0, 0, 0.5);
  z-index: 9999;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
}

#loading_symbol {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  border: 4px solid #f3f3f3;
  border-top: 4px solid #3498db;
  border-radius: 50%;
  width: 20px;
  height: 20px;
  animation: spin 2s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

#loading-text {
  position: absolute;
  top: 50%;
  left: 40%;
  text-align: center;
  color: white;
  font-weight: bold;
  margin-top: 20px;
}

	</style>
  <link rel="stylesheet" href="../style.css" />
</head>
<body>
<div class="container">
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

if(isset($_POST['quizID'])){
	$id=htmlspecialchars($_POST['quizID']);
} else {
  $pdo = null;
  header('location: assset.php');
}

$sql = "SELECT * FROM quiz_assignment WHERE quiz_assignment_id = :quiz_assignment_id";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    'quiz_assignment_id' => $id
]);

$stmt->setFetchMode(PDO::FETCH_ASSOC);
echo '<form name="myForm" id="myForm" action="updateSql.php" enctype="multipart/form-data" method="post">';
$explodedURL = explode('?', $_POST['url']);
echo '<input type="hidden" name="url" value="' . $explodedURL[1] . '">';
echo '<input type="hidden" name="quizID" id="quizID" value=' . $id . '>';
echo '<input type="hidden" name="type" id="type" value="quiz">';

while($row = $stmt->fetch()){
  $datetime = explode(" ", $row['test_datetime']);
  $date = date("d-m-Y", strtotime($datetime[0]) );
  $time = date("H:i", strtotime($datetime[1]) );
  $title = $row['title'];
	$prior_reading = $row['prior_reading'];
	$points = $row['points'];
  $text_box = str_replace("<br />", "", $row['text_box']);
	$pdf_doc = $row['pdf_doc'];
	$pdf_name = $row['pdf_name'];
	$ytlink = $row['ytlink'];
	$link = $row['link'];
  $description = str_replace("<br />", "", $row['description']);
  $lifelines = NULL;
  if($row['lifelines'] == 'null'){
    $lifelines = ['NULL'];
  } else {
    $lifelines = json_decode($row['lifelines']);
  }
	$shuffle = $row['shuffle'];
}
?>

      <br><p><b>Input title of assignment</b></p>
      <input type="text" class="texty" name="title" id="title" placeholder="Title" value = "<?php echo $title; ?>"  required />
      <br><br><p><b>(Optional) Description of assignment</b></p>
      <textarea class="form-control" style="font-size:20px;"  placeholder="Description of assignment" id="description" rows="10" cols="60" name="desc" maxlength="2000"><?php echo $description; ?></textarea>
      <div id="charCount2"></div><br>
      <p><b>Prior Reading Options:</b></p>
      <label for="none">
        <?php
        echo '<input type="radio" id="none" name="prior_reading" onclick="show_div()" value="none" ';
        if($prior_reading == 'none'){ echo "checked";}
        echo ' /> No Prior Reading';
        ?>
      </label><br>
      <label for="text_box">
      <?php
        echo '<input type="radio" id="hm_box" name="prior_reading" onclick="show_div()" value="text_box" ';
        if($prior_reading == 'text_box'){ echo "checked";}
        echo ' /> Text';
        ?>
      </label><br>
      <label for="pdf">
      <?php
        echo '<input type="radio" id="pdf" name="prior_reading" onclick="show_div()" value="pdf" ';
        if($prior_reading == 'pdf'){ echo "checked";}
        echo ' /> PDF';
        ?>
      </label><br>
      <label for="ytlink">
      <?php
        echo '<input type="radio" id="ytlink" name="prior_reading" onclick="show_div()" value="ytlink" ';
        if($prior_reading == 'ytlink'){ echo "checked";}
        echo ' /> YouTube Link';
        ?>
      </label><br>
      <label for="link">
      <?php
        echo '<input type="radio" id="link" name="prior_reading" onclick="show_div()" value="link" ';
        if($prior_reading == 'link'){ echo "checked";} 
        echo ' /> Other Link';
        ?>
      </label><br>
        <?php
        echo '<div id="show_pdf"';
        if($prior_reading == 'pdf'){ echo 'style="display: block">'; } else { echo 'style="display: none">'; }?>
        <h1>PDF upload:</h1>
        <?php if($prior_reading == 'pdf'){ 
            echo 'If you would like to change the currently uploaded then please input a new one below:';
        } 
        ?>


        <input type="file" onchange="validatePDF(this)" name="pdf_file" accept=".pdf" id="pdf_file"/> 
      </div>
        <div id="show_youtube" <?php if($prior_reading == 'ytlink'){ echo 'style="display: block"'; } else { echo 'style="display: none"'; }?> >
        <h1>Youtube Link:</h1>
        <input type="text" class="center texty" name="youtubeURL" id="youtubeURL" placeholder="Youtube Link e.g. www.youtube.com/watch?v=ABCDEFG" value=<?php if($prior_reading == 'ytlink'){ echo $ytlink;} ?>> </input>
      </div>
      <div id="show_link" <?php if($prior_reading == 'link'){ echo 'style="display: block"'; } else { echo 'style="display: none"'; } ?>>
        <h1>Link:</h1>
        <input type="text" class="texty" name="linkURL" id="linkURL" placeholder="Link e.g. https://google.com" value=<?php if($prior_reading == 'link'){ echo $link;} ?>></input>
              </div>
      <div id="show_text" <?php if($prior_reading == 'text_box'){ echo 'style="display: block"'; } else { echo 'style="display: none"'; } ?>>
        <textarea class="form-control" style="font-size:20px;" placeholder="Input the text you want the student to read" rows="10" id="text_box" name="text_box" maxlength="5000"><?php if($prior_reading == 'text_box'){ $text = str_replace("<br />\n<br />", "\n", $text_box); $text = str_replace("<br />", "", $text); echo $text;} ?></textarea>
        <div id="charCount"></div>
      </div>
      <div id="date-picker-example" class="md-form md-outline input-with-post-icon datepicker" inline="true">
        <br><label class="control-label" for="date">Due Date</label>
        <input class="login-input" id="date" name="date" placeholder="MM-DD-YYYY" type="text" value=<?php echo $date?> required/>
      </div>
      <br><label for="time">Time</label>
      <input type="time" id="time" name="time" value=<?php echo $time?> required><br>
      <br><label for="points">Maximum Points On Completion</label>
      <input type="number" class="login-input" name="points" value= <?php echo $points?> max="500" min="10" required /><br>
      <br><label for="shuffle"><b>Shuffle Questions?</b></label>
      <select name="shuffle" id="shuffle">
        <option value="yes" <?php if($shuffle == 1){ echo "selected"; } ?>>Yes</option>
        <option value="no" <?php if($shuffle == 0){ echo "selected"; } ?>>No</option>
      </select>
      <br><label for="check_list" id="checklabel">Which lifelines should be available to students (each can only be used once per quiz)</label>
      <div class="checkboxes">
        <input type="checkbox" id="checkinput" name="check_list[]" value="5050" <?php if (in_array('5050', $lifelines)) { echo 'checked'; }?>> 50/50 - 2 random wrong answers removed<br>
        <input type="checkbox" id="checkinput" name="check_list[]" value="ask" <?php if (in_array('ask', $lifelines)) { echo 'checked'; }?>> Ask the teacher - A hint is revealed<br>
        <input type="checkbox" id="checkinput" name="check_list[]" value="life" <?php if (in_array('life', $lifelines)) { echo 'checked'; }?>> Another life - The student can retry the question with that answer removed
      </div>
      <div style="display: none" id="loading-overlay">
        <div id="loading_symbol"></div>
        <p id="loading-text">Please wait around 40 seconds while the questions are generated...</p>
      </div>
      <h3>Enter the number of questions you want the test to have:</h3>
      <input type="number" name="numofquestions" id="numID" min="1" max="12" value=5><br> 
      <div id="show_button" style="display: none">
        <br><button type="button" onclick="createNewElement()">Auto Generate Answers From Input Text - May Take Some Time</button>
      </div>
      <br><button type="button" onclick="manual_input();">Click this to change how many questions you want</button>
      <hr>
      <div id="question-paper">

	<?php
	$sql = "SELECT * FROM quiz_assignment INNER JOIN questions_and_answers ON quiz_assignment.quiz_assignment_id = questions_and_answers.quiz_assignment_id WHERE quiz_assignment.quiz_assignment_id = $id";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $counter = 0;
    $numofAnswer = 1;
    $ques = array();
    $ans1 = array();
    $ans2 = array();
    $ans3 = array();
    $ans4 = array();
    $correctans = array();
    while($row = $stmt->fetch()){
      $qid = $row['questionID'];
      $ques = json_decode($row['question']);
      $ans1 = json_decode($row['answer1']);
      $ans2 = json_decode($row['answer2']);
      $ans3 = json_decode($row['answer3']);
      $ans4 = json_decode($row['answer4']);
      $correctans = json_decode($row['correctanswer']);
      $hints = json_decode($row['hint']);
      $times = json_decode($row['time_per_question']);
      $shuffle_or_not = $row['shuffle'];
      $lifelines = $row['lifelines'];
      echo '<h1>Questions</h1>';
      $numquestionsinrow = 0;
      for ($i=0; $i < count($ques); $i++) { 
        if($i % 3 == 0 && $i != 0){
          echo '</div>';
        }
        if($i % 3 == 0){
          echo '<div class="row">';
        }
        if($numquestionsinrow == 3){
          $numquestionsinrow = 0;
        }
        $numquestionsinrow += 1;
  
      	$counter += 1;
      echo '            <div class="col-md-4"> <label for="question' . $counter . '">
      Question ' . $counter . '
   </label>
   <br />
   <input type="text" class="inp" 
          id="question' . $counter . '"
          placeholder="Type Question ' . $counter . ' Here."
          value="' . $ques[$i] . '" required />

   <br /><br />';
echo '	<label for="answer' . $numofAnswer . '">
      Answer: 1
   </label>
   <br />
   <input type="text" class="inp"
         id="answer' . $numofAnswer . '" 
         placeholder="Type Answer 1 Here." 
          value="' . $ans1[$i] . '" required />
   <br /><br />';

echo '	<label for="answer' . $numofAnswer + 1 . '">
      Answer: 2
   </label>
   <br />
   <input type="text" class="inp"
         id="answer' . $numofAnswer + 1 . '" 
         placeholder="Type Answer 2 Here." 
         value="' . $ans2[$i] . '" required />
         <br /><br />';
echo '	<label for="answer' . $numofAnswer + 2 . '">
      Answer: 3
   </label>
   <br />
   <input type="text" class="inp"
         id="answer' . $numofAnswer + 2 . '" 
         placeholder="Type Answer 3 Here." 
         value="' . $ans3[$i] . '" required />
         <br /><br />';
echo '	<label for="answer' . $numofAnswer + 3 . '">
      Answer: 4
   </label>
   <br />
   <input type="text" class="inp"
         id="answer' . $numofAnswer + 3 . '" 
         placeholder="Type Answer 4 Here." 
         value="' . $ans4[$i] . '" required />
         <br /><br />';
echo '           <label for="hint' . $counter . '">
Hint ' . $counter . '
</label><br><input type="text" class="inp"
id="hint' . $counter . '" 
placeholder="Type hint for Question' . $counter . '" value="' . $hints[$i] .'" required />
<br /><br />';
echo '		<label for="correctanswer' . $counter . '">
   Correct Answer For Question' . $counter . '
 </label>
 <br />
 <select id="correctanswer' . $counter . '" name="answer">
  <option value="one"';
  if($correctans[$i] == 'one'){
   echo 'selected';
  }
  echo '>1</option>
  <option value="two"';
  if($correctans[$i] == 'two'){
   echo 'selected';
  }
  echo '>2</option>
  <option value="three"';
  if($correctans[$i] == 'three'){
   echo 'selected';
  }
  echo '>3</option>
  <option value="four"';
  if($correctans[$i] == 'four'){
   echo 'selected';
  }
  echo '>4</option>
 </select>
 <br /><br />';
 echo '
 <label for="time' . $counter . '">
     Select The Time To Answer Question ' . $counter . ' (In Seconds)
 </label>
 <input type="number" 
        id="time' . $counter . '" 
        placeholder="Time" min=1 max=100 value="' . $times[$i] . '" required/>
 <br /><br />';

 echo '</div>';

$numofAnswer += 4;
}

      }

    if($counter == 0){
      $pdo = null;
    	header("location: ../index.php");
    }
    $echoStringToBeUsed = '';
    for ($i=0; $i < $counter; $i++) { 
    	$echoStringToBeUsed .= '&quizQuestion' . $i+1 . '=' . $ques[$i];
    	$echoStringToBeUsed .= '&correctanswer' . $i+1 . '=' . $correctans[$i];
    }
    $answers = $counter;
    $var = 0;
    for ($j=0; $j < $counter; $j++) { 
    	for ($i=0; $i < ($answers*4)+1; $i++) { 
	    	$echoStringToBeUsed .= '&answer' . $var+1 .  '=' . $ans1[$j];
	    	$echoStringToBeUsed .= '&answer' . $var+2 .  '=' . $ans2[$j];
	    	$echoStringToBeUsed .= '&answer' . $var+3 .  '=' . $ans3[$j];
	    	$echoStringToBeUsed .= '&answer' . $var+4 .  '=' . $ans4[$j];
	    	$var = $var + 4;
	    	break;
    	}
    }
    $echoStringToBeUsed .= '&Qcount=' . $counter . '>';
	echo '<input type="hidden" id="quizID" name = "quizID" value = "' . $id . '">';
    echo '<input type="hidden" id="count" name = "count" value = "' . $counter . '">';
    echo '<input type="hidden" id="q" name = "q" value = "">';
    echo '<input type="hidden" id="qid" name = "qid" value = "' . $qid . '">';
    echo '<input type="hidden" id="a1" name = "a1" value = "">';
    echo '<input type="hidden" id="a2" name = "a2" value = "">';
    echo '<input type="hidden" id="a3" name = "a3" value = "">';
    echo '<input type="hidden" id="a4" name = "a4" value = "">';
    echo '<input type="hidden" id="c" name = "c" value = "">';
    echo '<input type="hidden" id="hints" name = "hints" value = "">';
    echo '<input type="hidden" id="times" name = "times" value = "">';
    $pdo = null;
?>
</div>
</div>
<br>
 <button type="submit" class="sub" value="submit">Update</button><br>
 </div>
<?php
    echo "</div>";
    echo '</form>';
?>

<script src="https://apis.google.com/js/api.js"></script>
<script src="../config.js"></script>
 <script type="text/javascript">
   function validatePDF(file) {

const fileSize = file.files[0].size / 1024 / 1024; // in MiB
if(fileSize > 16){
  alert('File too big, must be less than 16 MB');
  $(file).val(''); //for clearing with Jquery
}
}
const textarea = document.getElementById("text_box");
const charCount = document.getElementById("charCount");
const maxChars = 5000;

textarea.addEventListener("input", function() {
  const currentChars = textarea.value.length;
  charCount.textContent = `${currentChars}/${maxChars}`;
});
const textarea2 = document.getElementById("description");
const charCount2 = document.getElementById("charCount2");
const maxChars2 = 2000;
textarea2.addEventListener("input", function() {
  const currentChars2 = textarea2.value.length;
  charCount2.textContent = `${currentChars2}/${maxChars2}`;
});


const Form = document.getElementById('myForm');
  Form.addEventListener('submit', e => {
    e.preventDefault();
    numofquestions = document.myForm.numID.value;
if (numofquestions < 1) {
  alert("Must have at least one question");
  return false;
} else if (numofquestions > 12) {
  alert("Must have at least one question");
  return false;
}

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
if(document.getElementById('description').value.length > 2000) {
  // description too long!
  alert('Description too long');
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

    var count = document.getElementById('count').value;
		count = parseInt(count);
	 	var questions = [];
	 	var correctanswer = [];
	 	var hints = [];
	 	var times = [];
     for (var i = 0; i < count; i++) {
        if(document.getElementById(`question${i+1}`).value.includes("|") == false){
          if(document.getElementById(`question${i+1}`).value.length > 150){
              alert(`Question ${i+1} Too Long!`);
              return false;
            } else {
              questions.push(document.getElementById(`question${i+1}`).value);
             }  
      } else {
          alert('Invalid Character: "|" Found in Question ' +  (i+1));
          return false;
        }
        correctanswer.push(document.getElementById(`correctanswer${i+1}`).value);
        if(document.getElementById(`hint${i+1}`).value.includes("|") == false){
            if(document.getElementById(`hint${i+1}`).value.length > 150){
              alert(`Hint ${i+1} Too Long!`);
              return false;
            } else {
              hints.push(document.getElementById(`hint${i+1}`).value);
             }        
        } else {
          alert(`Invalid Character: "|" Found in Hint ${i+1}`);
          return false;
        }
        times.push(document.getElementById(`time${i+1}`).value);

    }

    
	 	var answer1 = [];
	 	var answer2 = [];
	 	var answer3 = [];
	 	var answer4 = [];
	 	var int = 0
    var counter = 0;

for (var i = 1; i <= (count * 4); i+=4) {
  for (let j = i; j <= i + 2; j++) {
    for (let k = j + 1; k <= i + 3; k++) {
      if (document.getElementById(`answer${j}`).value.toLowerCase() === document.getElementById(`answer${k}`).value.toLowerCase()) {
        // Code to execute if there's a match
        if(k%4 == 0){
          k = 4;
        } else {
          k = k%4;
        }
        if(j%4 == 0){
          j = 4;
        } else {
          j = j%4;
        }
        i = Math.round((i/4) + 1);
        alert(`Question ${i}: Answer ${j} is equal to Answer ${k}`);
        return false;
      }
    }
  }
}

	 	for (var i = 1; i < ((count*4)+1); i++) {
      counter += 1;
  if(document.getElementById(`answer${i}`).value.includes("|") == false){
    if(document.getElementById(`answer${i}`).value.length > 150){
      alert(`Answer 1 in Question ${counter} Too Long!`);
      return false;
    }
    else if(document.getElementById(`answer${i}`).value.length < 1){
      alert(`Answer 1 in Question ${counter} Too Short!`);
      return false;
    } else {
      answer1.push(document.getElementById(`answer${i}`).value);

    }
  } else {
  alert(`Invalid Character: "|" Found in Answer 1 in Question ${counter}`);
  return false
}
  if(document.getElementById(`answer${i+1}`).value.includes("|") == false){
  if(document.getElementById(`answer${i+1}`).value.length > 150){
    alert(`Answer 2 in Question ${counter} Too Long!`);
    return false;
  }
  else if(document.getElementById(`answer${i+1}`).value.length < 1){
    alert(`Answer 2 in Question ${counter} Too Short!`);
    return false;
  } else {
    answer2.push(document.getElementById(`answer${i+1}`).value);
  }

} else {
  alert(`Invalid Character: "|" Found in Answer 2 in Question ${counter}`);
  return false
}
if(document.getElementById(`answer${i+2}`).value.includes("|") == false){
  if(document.getElementById(`answer${i+2}`).value.length > 150){
    alert(`Answer 3 in Question ${counter} Too Long!`);
    return false;
  }
  else if(document.getElementById(`answer${i+2}`).value.length < 1){
    alert(`Answer 3 in Question ${counter} Too Short!`);
    return false;
  } else {
    answer3.push(document.getElementById(`answer${i+2}`).value);
  }
} else {
  alert(`Invalid Character: "|" Found in Answer 3 in Question ${counter}`);
  return false
}
if(document.getElementById(`answer${i+3}`).value.includes("|") == false){
  if(document.getElementById(`answer${i+3}`).value.length > 150){
    alert(`Answer 4 in Question ${counter} Too Long!`);
    return false;
  }
  else if(document.getElementById(`answer${i+3}`).value.length < 1){
    alert(`Answer 4 in Question ${counter} Too Short!`);
    return false;
  } else {
    answer4.push(document.getElementById(`answer${i+3}`).value);
  }
} else{
  alert(`Invalid Character: "|" Found in Answer 4 in Question ${counter}`);
  return false
}
  i = i + 3;      
	 	}

     document.getElementById("q").value = JSON.stringify(questions);
     document.getElementById("hints").value = JSON.stringify(hints);
    document.getElementById("a1").value = JSON.stringify(answer1);
    document.getElementById("a2").value = JSON.stringify(answer2);
    document.getElementById("a3").value = JSON.stringify(answer3);
    document.getElementById("a4").value = JSON.stringify(answer4);
    document.getElementById("c").value = JSON.stringify(correctanswer);
    document.getElementById("times").value = JSON.stringify(times);
    
if(document.getElementById('ytlink').checked && (document.getElementById('youtubeURL').value != '')){
  // if(document.getElementById('ytlink').checked){

  document.getElementById('pdf_file').value = '';
  document.getElementById('text_box').value = '';
  document.getElementById('linkURL').value = '';
  gapi.load("client", loadClient);  
}

if(document.getElementById('link').checked && (document.getElementById('linkURL').value != '')){


  if (document.getElementById('linkURL').value.length < 4) {
    alert('link too short, must be greater than 4 characters');
    return false;
  }
  if (document.getElementById('linkURL').value.length > 250) {
    alert('link too long, must be less than 250 characters');
    return false;
  }
  if(document.getElementById('linkURL').value.substring(0,7) != "http://" && document.getElementById('linkURL').value.substring(0,8) != "https://"){
    alert('link must start with http:// or https://');
    return false;
  }
  document.getElementById('pdf_file').value = '';
  document.getElementById('text_box').value = '';
  document.getElementById('youtubeURL').value = '';

  Form.submit();
}

if(document.getElementById('hm_box').checked && (document.getElementById('text_box').value != '')){


  if (document.getElementById('text_box').value.length < 4) {
    alert('Not enough text in prior reading textbox');
    return false;
  }
  if (document.getElementById('text_box').value.length > 5000) {
    alert('too much text in prior reading textbox');
    return false;
  }
  document.getElementById('pdf_file').value = '';
  document.getElementById('linkURL').value = '';
  document.getElementById('youtubeURL').value = '';

  Form.submit();
}

if(document.getElementById('pdf').checked && document.getElementById('pdf_file').value != ''){

  document.getElementById('linkURL').value = '';
  document.getElementById('youtubeURL').value = '';
  document.getElementById('text_box').value = '';

  Form.submit();
}
if(document.getElementById('none').checked){
  document.getElementById('pdf_file').value = '';
  document.getElementById('linkURL').value = '';
  document.getElementById('youtubeURL').value = '';
  document.getElementById('text_box').value = '';
  Form.submit();
}


    });
    function loadClient() {
      gapi.client.setApiKey(config.apiKeyGoogle);
      return gapi.client.load("https://www.googleapis.com/discovery/v1/apis/youtube/v3/rest")
          .then(function() { console.log("GAPI client loaded for API"); execute();},
                  function(err) { console.error("Error loading GAPI client for API", err); });
  }

    function execute() {
      var link = document.getElementById('youtubeURL').value;
      link = link.split("v=")[1];
      gapi.client.youtube.videos.list({
          "id": [
            link
          ]
        })
      .then(function(response) {
              if(response.result.pageInfo.totalResults == 0){
                alert('incorrect video url');
                return false;
                // ERRORRRRR
              } else {
                Form.submit();
                // ALL GOOD!
              }
            },
            function(err) { console.error("Execute error", err); });

          }
          function manual_input(){
      numofquestions = document.getElementById("numID").value;
      if (numofquestions < 1) {
        alert("Must have at least one question");
        return false;
      }

      if (numofquestions > 12) {
        alert("Too many questions");
        return false;
      }
      var questionAnswerHTML = "";
      var numofAnswer = 1
      var numrows = 0;
      var numquestionsinrow = 0;
      questionAnswerHTML += `<h1>Questions</h1>`;
      for (var i = 0; i < numofquestions; i++) {
        if(i % 3 == 0 && i != 0){
          questionAnswerHTML += `</div>`;
        }
        if(i % 3 == 0){
        questionAnswerHTML += `<div class="row">`;
        numrows += 1;
        }
        if(numquestionsinrow == 3){
          numquestionsinrow = 0;
        }
        numquestionsinrow += 1;
        questionAnswerHTML += `
            <div class="col-md-4">
            <label for="question${i}">
               Question: ${i+1}
            </label>
            <br />
            <input type="text" 
                   id="question${i}" class="answer"
                   placeholder="Type Question ${i+1} Here." required />

           <br /><br />
           <label for="answer${numofAnswer}">
               Answer: 1
           </label>
           <br />
           <input type="text" 
                  id="answer${numofAnswer}" class="answer"
                  placeholder="Type Answer 1 Here." required />
           <br /><br />
          <label for="answer${numofAnswer+1}">
               Answer: 2
           </label>
           <br />
           <input type="text" 
                  id="answer${numofAnswer+1}" class="answer"
                  placeholder="Type Answer 2 Here." required />
           <br /><br />
            <label for="answer${numofAnswer+2}">
               Answer: 3
           </label>
           <br />
           <input type="text" 
                  id="answer${numofAnswer+2}" class="answer"
                  placeholder="Type answer 3 Here." required />
           <br /><br />
            <label for="answer${numofAnswer+3}">
               Answer: 4
           </label>
           <br />
           <input type="text" 
                  id="answer${numofAnswer+3}" class="answer"
                  placeholder="Type answer 4 Here." required />
           <br /><br />
           <label for="hint${i}">
               Hint For Question ${i+1}
           </label>
           <br />
           <input type="text" 
                  id="hint${i}" class="answer"
                  placeholder="Type hint for Question ${i+1}" required />
           <br /><br />
          <label for="correctanswer${i}">
               Correct Answer For Question ${i+1}
           </label>
           <br />
           <select id="correctanswer${i}" name="answer">
            <option value="one">1</option>
            <option value="two">2</option>
            <option value="three">3</option>
            <option value="four">4</option>
           </select>
           <br /><br />
       `;
       questionAnswerHTML += `
           <label for="time${i}">
               Select The Time To Answer Question ${i+1} (In Seconds)
           </label>
           <input type="number" 
                  id="time${i}" 
                  placeholder="Time ${i+1}" value=15 min=1 max=100 required />
           <br /><br />`;
           questionAnswerHTML += `</div>`;
        numofAnswer += 4;
      }
    document.getElementById('count').value = numofquestions
      show_boxes_clicked = true;
      document.getElementById("question-paper").innerHTML = questionAnswerHTML;
    }
    function createNewElement() {
      numofquestions = document.getElementById("numID").value;
      if (numofquestions < 1) {
        alert("Must have at least one question");
        return false;
      }

      if (numofquestions > 12) {
        alert("Too many questions");
        return false;
      }
      if (document.getElementById('text_box').value.length < 4 || (document.getElementById('text_box').value == '')) {
        alert('Not enough text in prior reading textbox');
        return false;
      }
      if (document.getElementById('text_box').value.length > 4000) {
        alert('too much text in prior reading textbox to use the API (must be less than 3000 characters)');
        return false;
      }
      // Set your API key and model
      const api_key = config.apiKeyGPT;
      const model = "text-davinci-003";

      // Set the prompt to generate quiz questions and answers from
      const prompt = `Please turn this text into a quiz with ${numofquestions} questions each with 4 potential answers with one being the correct one and a hint (which is not the answer) for each question and format it as a JSON object that contains an array of quiz questions and answers. The attributes in this JSON object are: Quiz: The main object containing an array of quiz questions and answers, Question: The text of the question, Answers: An array of potential answers for the question, CorrectAnswer: The correct answer for the question which is word for word the same as the one in the Answers array, Hint: A hint related to the question that will help the student guess the answer, Each element in the Quiz array contains an object that has Question, Answers, CorrectAnswer, and Hint keys that have corresponding values as strings. The text to translate into the quiz is this: ` + document.getElementById("text_box").value;
      // Set the number of quiz questions and answers to generate

      // Set up the request body
      const data = {
        model: model,
        prompt: prompt,
        max_tokens: 2048,
        n: 1,
        temperature: 0.5
      };

      // Set up the request options
      const options = {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "Authorization": `Bearer ${api_key}`
        },
        body: JSON.stringify(data)
      };


      async function fetchData() {
    try {
        loader = document.getElementById('loading-overlay');
        loader.style.display = 'block';
        const response = await fetch("https://api.openai.com/v1/completions", options);
        const jsonResponse = await response.json();
        // Extract the quiz questions and answers from the response
        var questions_answers = jsonResponse.choices[0].text;
        questions_answers = questions_answers.substring(questions_answers.indexOf('Quiz'));
        questions_answers = "{ " + questions_answers;
        if((questions_answers.charAt(questions_answers.length - 1)) != "}"){
          questions_answers = questions_answers + " }";
        }
        try {
          let jsonObject = JSON.parse(questions_answers);
            if (jsonObject) {
                console.log('WORKING');
            } else {
              console.log("Not a valid JSON");
              return;
            }
            var questionAnswerHTML = "";
            var numofAnswer = 1
            var valueNo = 0
            var questionAnswerHTML = "";
      var numrows = 0;
      var numquestionsinrow = 0;
      for (var i = 0; i < numofquestions; i++) {
        if(i % 3 == 0){
        questionAnswerHTML += `<div class="row">`;
        numrows += 1;
        }
        if(numquestionsinrow == 3){
          numquestionsinrow = 0;
        }
        numquestionsinrow += 1;
        questionAnswerHTML += `
            <div class="col-md-4">
            <label for="question${i}">
               Question: ${i+1}
            </label>
            <br />
            <input type="text" 
                   id="question${i}" class="answer"
                   placeholder="Type Question ${i+1} Here." value="${jsonObject.Quiz[i].Question}" required />

           <br /><br />
           <label for="answer${numofAnswer}">
               Answer: 1
           </label>
           <br />
           <input type="text" 
                  id="answer${numofAnswer}" class="answer"
                  placeholder="Type Answer 1 Here." value="${jsonObject.Quiz[i].Answers[valueNo]}" required />
           <br /><br />
          <label for="answer${numofAnswer+1}">
               Answer: 2
           </label>
           <br />
           <input type="text" 
                  id="answer${numofAnswer+1}" class="answer"
                  placeholder="Type Answer 2 Here." value="${jsonObject.Quiz[i].Answers[valueNo+1]}" required />
           <br /><br />
            <label for="answer${numofAnswer+2}">
               Answer: 3
           </label>
           <br />
           <input type="text" 
                  id="answer${numofAnswer+2}" class="answer"
                  placeholder="Type answer 3 Here." value="${jsonObject.Quiz[i].Answers[valueNo+2]}" required />
           <br /><br />
            <label for="answer${numofAnswer+3}">
               Answer: 4
           </label>
           <br />
           <input type="text" 
                  id="answer${numofAnswer+3}" class="answer"
                  placeholder="Type answer 4 Here." value="${jsonObject.Quiz[i].Answers[valueNo+3]}" required />
           <br /><br />
           <label for="hint${i}">
               Hint For Question ${i+1}
           </label>
           <br />
           <input type="text" 
                  id="hint${i}" class="answer"
                  placeholder="Type hint for Question ${i+1}" value="${jsonObject.Quiz[i].Hint}"/>
           <br /><br />
          <label for="correctanswer${i}">
               Correct Answer For Question ${i+1}
           </label>
           <br />`;
           questionAnswerHTML += `<select id="correctanswer${i}" name="answer">`;
           questionAnswerHTML += `<option value="one"`;
           if (jsonObject.Quiz[i].Answers.indexOf(jsonObject.Quiz[i].CorrectAnswer) == 0) {
            questionAnswerHTML += `selected`;
           }
           questionAnswerHTML += `>1</option>`;
           questionAnswerHTML += `<option value="two"`;
           if (jsonObject.Quiz[i].Answers.indexOf(jsonObject.Quiz[i].CorrectAnswer) == 1) {
            questionAnswerHTML += `selected`;
           }
           questionAnswerHTML += `>2</option>`;
           questionAnswerHTML += `<option value="three"`;
           if (jsonObject.Quiz[i].Answers.indexOf(jsonObject.Quiz[i].CorrectAnswer) == 2) {
            questionAnswerHTML += `selected`;
           }
           questionAnswerHTML += `>3</option>`;
           questionAnswerHTML += `<option value="four"`;
           if (jsonObject.Quiz[i].Answers.indexOf(jsonObject.Quiz[i].CorrectAnswer) == 3) {
            questionAnswerHTML += `selected`;
           }
           questionAnswerHTML += `>4</option></select><br /><br />`;
           questionAnswerHTML += `
           <label for="time${i}">
               Select The Time To Answer Question ${i+1} (In Seconds)
           </label>
           <input type="number" 
                  id="time${i}" 
                  placeholder="Time ${i+1}" value=15 min=1 max=100 />
           <br /><br />`;
           questionAnswerHTML += `</div>`;
            if(numquestionsinrow == 3){
          questionAnswerHTML += `</div>`;
        }
        numofAnswer += 4;


      valueNo = 0;
      }
      if(numquestionsinrow < 2){
        questionAnswerHTML += `</div>`;
      }
      show_boxes_clicked = true;
      document.getElementById('count').value = numofquestions;
      document.getElementById("question-paper").innerHTML = questionAnswerHTML;
      loader.style.display = 'none';
        } catch(e) {
            console.log(e); 
            loader.style.display = 'none';
            alert('Error when using the API');
            manual_input();

        }
    } catch (err) {
        console.error(err);
        alert('Error when using the API');
        manual_input();
    }
}

fetchData();

    }


    function show_div() {
      var text_box = document.getElementById("hm_box");
      var pdf = document.getElementById("pdf");
      var none = document.getElementById("none");
      var link = document.getElementById("link");
      var ytlink = document.getElementById("ytlink");
      var show_text = document.getElementById("show_text");
      var button_showing = document.getElementById("show_button");
      var show_pdf = document.getElementById("show_pdf");
      var show_link = document.getElementById("show_link");
      var show_youtube = document.getElementById("show_youtube");
      show_text.style.display = text_box.checked ? "block" : "none";
      button_showing.style.display = text_box.checked ? "block" : "none";
      show_pdf.style.display = pdf.checked ? "block" : "none";
      show_link.style.display = link.checked ? "block" : "none";
      show_youtube.style.display = ytlink.checked ? "block" : "none";
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


</script>
</body>
</html>