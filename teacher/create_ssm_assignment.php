<?php 
session_start();

  if(!isset($_SESSION['user']) || !isset($_SESSION['school_id']) || !isset($_SESSION['teacher_id'])){
    echo '<script>location.href = "../login.php";</script>';
  }   
  ?>
<!DOCTYPE html>
<html>

<head>
  <title>Create a SSM Assignment</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

      <!-- Bootstrap Date-Picker Plugin -->
      <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>

  <style>
    *{
      font-size: 20px;
    }
    /* CSS for all input tags*/
    input {
      padding: 15px;
      border: solid black;
    }

    /* CSS for upload button*/
    .uploadbtn {
      background-color: green;
      color: white;
      padding: 16px;
      width: 100%;
    }

    #quizName {
      width: 25%;
      padding: 15px;
    }

    .col-md-4 {
      border: 5px solid black;
    }

    textarea {
      resize: none;
    }

    #checklabel {
      display: block;
      padding-left: 15px;
      text-indent: -15px;
    }

    #checkinput {
      width: 13px;
      height: 13px;
      padding: 0;
      margin: 0;
      vertical-align: bottom;
      position: relative;
      top: -1px;
      *overflow: hidden;
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
  width: 40px;
  height: 40px;
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
  margin-top: 40px;
}

.button {
            background-color: blue;
            color: white;
            font-size: 18px;
            padding: 14px 30px;
            width: 100%;
        }


        .button:hover {
            background-color: #555;
        }

        .col-sm-3{
          border: 5px solid black;
        }

        #activated {
            background-color: #555;
        }
  </style>
      <link rel="stylesheet" href="../style.css" />

</head>

<body>

  <?php
  if(isset($_SESSION['POST']['group_ID'])){
      $gid = intval($_SESSION['POST']['group_ID']);
  } else {
      header('location: set_assignment.php');
  }
  ?>
  <div class="container">
  <h1>Create A Subject Savvy Millionaire Assignment</h1>

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


  <form name="myForm" id="myForm" action="assignment_set.php" enctype="multipart/form-data" method="post">
  <input type="hidden" name="type" value="quiz" >
  <input type="hidden" name="group_ID" value="<?php echo $gid ?>" >
      <br>
      <p>Welcome to Subject Savvy Millionaire, the thrilling educational game that will test your students' knowledge and skills in various subject areas!</p>

      <p>In this game, students will be challenged with subject-specific questions and given (up to) three lifelines to help them along the way: a hint from the teacher, a chance to attempt an answer without losing the game, and a 50/50 lifeline that removes two random incorrect answers. But if a student selects an incorrect answer or takes to long to answer, the game is over and they will need to attempt the assignment again!</p>

      <p>The goal of the game is to answer as many questions correctly as possible to climb the leaderboard and become a Subject Savvy Millionaire! You may assign up to a total of 12 questions to help them improve their knowledge and skills in their chosen subject area.</p>

      <p>Subject Savvy Millionaire is a points-based game that encourages students to learn and retain knowledge through a fun and engaging experience. It provides an opportunity for students to test their knowledge in a competitive setting while also helping them to identify areas where they may need additional support.</p>

      <p>So, get ready to challenge your students' knowledge and skills with Subject Savvy Millionaire and see who will become the ultimate Subject Savvy Millionaire!</p>
      <hr>
      <p><b>Prior Reading Options:</b></p>
      <label for="none">
        <input type="radio" id="none" name="prior_reading" onclick="show_div()" value="none" checked="checked" />
        No Prior Reading
      </label><br>
      <label for="hm_box">
        <input type="radio" id="hm_box" name="prior_reading" onclick="show_div()" value="text_box" />
        Text
      </label><br>
      <label for="pdf">
        <input type="radio" id="pdf" name="prior_reading" onclick="show_div()" value="pdf" />
        PDF
      </label><br>
      <label for="ytlink">
        <input type="radio" id="ytlink" name="prior_reading" onclick="show_div()" value="ytlink" />
        YouTube Link
      </label><br>
      <label for="link">
        <input type="radio" id="link" name="prior_reading" onclick="show_div()" value="link" />
        Other Link
      </label><br>


      <div id="show_pdf" style="display: none">
        <h1>PDF upload:</h1>
        <input type="file" onchange="validatePDF(this)" name="pdf_file" id="pdf_file" accept=".pdf" />
      </div>
      <div id="show_youtube" style="display: none">
        <h1>Youtube Link:</h1>
        <input type="text" class="texty" style="width:50%;" id="youtubeURL" name="youtubeURL" placeholder="Youtube Link e.g. www.youtube.com/watch?v=ABCDEFG"/>
      </div>
      <div id="show_link" style="display: none">
        <h1>Link:</h1>
        <input type="text" class="texty" style="width:50%;" id="linkURL" name="linkURL" placeholder="Link e.g. https://google.com" />
      </div>
      <div id="show_text" style="display: none">
        <textarea class="form-control" style="font-size:20px;" placeholder="Input the text you want the student to read" rows="10" name="text_box" id="text_box" maxlength="5000"></textarea>
        <div id="charCount"></div>
      </div>
      <hr>

      <label for="quizName"><b>Subject Savvy Millionaire Game Name</b></label>
      <input type="text" placeholder="Enter title for reference" name="quizName" id="quizName">
      <hr>
      <label for="description"><b>(Optional) Description</b></label>
      <textarea class="form-control" style="font-size:20px;" placeholder="Input the description of the game" rows="10" name="description" id="description" maxlength="2000"></textarea>
      <div id="charCount2"></div>

      <hr>

      <label for="shuffle"><b>Shuffle Questions?</b></label>
      <select name="shuffle" id="shuffle">
        <option value="yes">Yes</option>
        <option value="no">No</option>
      </select>
      <hr>
      <label for="pass"><b>What percentage do students need to achieve to pass the game? (they will not be told this)</b></label>
      <input type="number" name="pass" max=100 min=0 value=60>%
      <hr>
      <?php
      date_default_timezone_set('Europe/London');
      if(date("H") >= 18){
        $date = date("d-m-Y", strtotime('tomorrow'));
      } else {
        $date = date("d-m-Y");
      }
      ?>
      <div id="date-picker-example" class="md-form md-outline input-with-post-icon datepicker" inline="true">
        <label class="control-label" for="date">Due Date</label>
        <input class="login-input" id="date" name="date" placeholder="MM-DD-YYYY" type="text" value=<?php echo $date?> required/>
      </div>
      <label for="time">Time</label>
      <input type="time" id="time" name="time" value="18:00" required><br>
      <label for="points">Maximum Points On Completion</label>
      <input type="number" class="login-input" name="points" value="100" max="500" min="10" required /><br>
      <label for="check_list" id="checklabel">Which lifelines should be available to students (each can only be used once per game)</label>
      <div class="checkboxes">
        <input type="checkbox" id="checkinput" name="check_list[]" value="5050"> 50/50 - 2 random wrong answers removed<br>
        <input type="checkbox" id="checkinput" name="check_list[]" value="ask"> Ask the teacher - A hint is revealed<br>
        <input type="checkbox" id="checkinput" name="check_list[]" value="life"> Another life - The student can attempt to answer a question without losing the game
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
      <br><button type="button" onclick="manual_input();">Manually Input Questions</button>
      <hr>
      <div id="question-paper">
      </div>

      <input type="hidden" id="hiddenquestion" name="hiddenquestion" value="">
      <input type="hidden" id="hiddenanswer1" name="hiddenanswer1" value="">
      <input type="hidden" id="hiddenanswer2" name="hiddenanswer2" value="">
      <input type="hidden" id="hiddenanswer3" name="hiddenanswer3" value="">
      <input type="hidden" id="hiddenanswer4" name="hiddenanswer4" value="">
      <input type="hidden" id="hiddencorrectanswer" name="hiddencorrectanswer" value="">
      <input type="hidden" id="hiddenhint" name="hiddenhint" value="">
      <input type="hidden" id="hiddentimes" name="hiddentimes" value="">
      <input type="hidden" id="hiddencount" name="hiddencount" value="">
      <hr><button type="submit" class="uploadbtn">Create</button>
    </div>
  </form>
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
      numofquestions = document.getElementById('hiddencount').value;
      if(numofquestions == ''){
        alert("Must have at least one question");
        return false;
      }

if (numofquestions < 1) {
  alert("Must have at least one question");
  return false;
} else if (numofquestions > 12) {
  alert("Must have at least one question");
  return false;
}


var questions = [];
var answer1 = [];
var answer2 = [];
var answer3 = [];
var answer4 = [];
var correctanswers = [];
var hints = [];
var times = [];

if (show_boxes_clicked == false){
  return false;
}

if(document.getElementById('quizName').value.length < 4) {
  alert('Title too short');
  return false;
}
if(document.getElementById('quizName').value.length > 25) {
  alert('Title too long');
  return false;
}
if(document.getElementById('description').value.length > 2000) {
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

// Questions / MC's must have text in them as they have required field!
for (var i = 0; i < numofquestions; i++) {
  if(document.getElementById(`question${i}`).value.includes("|") == false){
    if(document.getElementById(`question${i}`).value.length > 150){
      alert(`Question ${i+1} Too Long!`);
      return false;
    } else {
      questions.push(document.getElementById(`question${i}`).value);
    }
  } else {
    alert('Invalid Character: "|" Found in Question ' +  (i+1));
    return false;
  }
  correctanswers.push(document.getElementById(`correctanswer${i}`).value);
  if(document.getElementById(`hint${i}`).value.includes("|") == false){
    if(document.getElementById(`hint${i}`).value.length > 150){
      alert(`Hint ${i+1} Too Long!`);
      return false;
    } else {
      hints.push(document.getElementById(`hint${i}`).value);
    }
  } else {
    alert(`Invalid Character: "|" Found in Hint ${i+1}`);
    return false;
  }
  times.push(document.getElementById(`time${i}`).value);

}
questions = JSON.stringify(questions);
correctanswers = JSON.stringify(correctanswers);
hints = JSON.stringify(hints);
times = JSON.stringify(times);
var counter = 0;

for (var i = 1; i <= (numofquestions * 4); i+=4) {
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

for (var i = 1; i < ((numofquestions * 4) + 1); i++) {
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
if(document.getElementById(`answer${i+2}`).value.includes("|") == false){
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
answer1 = JSON.stringify(answer1);
answer2 = JSON.stringify(answer2);
answer3 = JSON.stringify(answer3);
answer4 = JSON.stringify(answer4);

document.getElementById("hiddenquestion").value = questions;
document.getElementById("hiddenanswer1").value = answer1;
document.getElementById("hiddenanswer2").value = answer2;
document.getElementById("hiddenanswer3").value = answer3;
document.getElementById("hiddenanswer4").value = answer4;
document.getElementById("hiddencorrectanswer").value = correctanswers;
document.getElementById("hiddenhint").value = hints;
document.getElementById("hiddentimes").value = times;


if(document.getElementById('ytlink').checked && (document.getElementById('youtubeURL').value != '')){
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
              } else {
                return Form.submit();
              }
            },
            function(err) { console.error("Execute error", err); });

          }

    var numofquestions = 0;

    var show_boxes_clicked = false;
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
      document.getElementById('hiddencount').value = numofquestions;
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
        alert('too much text in prior reading textbox to use the API (must be less than 4000 characters)');
        return false;
      }
      var api_key = config.apiKeyGPT;
      const model = "text-davinci-003";

      // Set the prompt to generate quiz questions and answers from
      

      const prompt = `Please turn this text into a quiz with ${numofquestions} questions each with 4 potential answers with one being the correct one and a hint (which is not the answer) for each question and format it as a JSON object that contains an array of quiz questions and answers. The attributes in this JSON object are: Quiz: The main object containing an array of quiz questions and answers, Question: The text of the question, Answers: An array of potential answers for the question, CorrectAnswer: The correct answer for the question which is word for word the same as the one in the Answers array, Hint: A hint related to the question that will help the student guess the answer, Each element in the Quiz array contains an object that has Question, Answers, CorrectAnswer, and Hint keys that have corresponding values as strings. The text to translate into the quiz is this: ` + document.getElementById("text_box").value;

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
        questions_answers = questions_answers.substring(questions_answers.indexOf('{'));
        try {
            let jsonObject = JSON.parse(questions_answers);
            if (!jsonObject) {
              console.log("Not a valid JSON");
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
                  placeholder="Type hint for Question ${i+1}" value="${jsonObject.Quiz[i].Hint}" required />
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
                  placeholder="Time ${i+1}" value=15 min=1 max=100 required />
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
      document.getElementById('hiddencount').value = numofquestions;
      show_boxes_clicked = true;
      document.getElementById("question-paper").innerHTML = questionAnswerHTML;
      loader.style.display = 'none';
        } catch(e) {
          loader.style.display = 'none';
          alert('Error when using the API');
          console.log(e); 
          manual_input();
        }
    } catch (err) {
      alert(err);
      loader.style.display = 'none';
      alert('Error when using the API');
      console.error(err);
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
    
    $(document).ready(function(){
      var date_input=$('input[name="date"]'); //our date input has the name "date"
      var container=$('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";
      var options={
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