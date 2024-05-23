<?php
session_start();
if(!isset($_SESSION['user']) || !isset($_SESSION['school_id']) || !isset($_SESSION['teacher_id'])){
    echo '<script>location.href = "../login.php";</script>';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Assign Custom Assingment</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

  <!-- Bootstrap CSS -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

   <!-- jQuery
  <script type="text/javascript" src="https://code.jquery.com/jquery-1.11.3.min.js"></script> -->

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
form{
  text-align: left;
}
.center {
  display: block;
  margin: 0 auto;
  text-align: center;
}
input {
      padding: 15px;
      border: solid black;
    }

.texty {
  width: 50%;
  padding: 12px 20px;
  margin: 0px 0;
  display: inline-block;
  border: 1px solid black;
  box-sizing: border-box;
}
</style>
<link rel="stylesheet" href="../style.css" />
</head>

<body>
<div class="container">
  <?php
  ?>
<h1>Create a Custom Assignment</h1>

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
<?php
  if(isset($_SESSION['POST']['group_ID'])){
    $gid = intval($_SESSION['POST']['group_ID']);
  } else {
      // $pdo = null;
      header('location: set_assignment.php');
  }
?>
  <form name="myForm" id="myForm" action="assignment_set.php" enctype="multipart/form-data" method="post">
  <input type="hidden" name="type" value="manual" >
  <input type="hidden" name="group_ID" value="<?php echo $gid ?>" >
<br>
    <!-- OR SEND USER TO THE ASSSET.php where they can see it set -->
<p style="text-align:left;">Custom Assignments is a feature that allows you to create unique assignments that are tailored to the specific needs of your student group. With this feature, you have the flexibility to create assignments that may differ from the standard assignments given in the course curriculum.</p>

<p style="text-align:left;">You can use Custom Assignments to assign reading material, written essays, or any other type of assignment that you feel will benefit your students. You can also set a due date for the assignment and use it to track your students' progress.</p>

<p style="text-align:left;">By using Custom Assignments, you can provide your students with personalised assignments that will help them to develop the skills and knowledge they need to succeed in your course. This feature also allows you to be more creative with your assignments, which can make learning more engaging and enjoyable for your students.</p>

<p style="text-align:left;">We hope that you find this feature useful in creating a more customied and engaging learning experience for your students.</p>

      <br>
      <p><b>Title of assignment:</b></p>
      <input type="text" class="texty" id="title" name="title" placeholder="Title" required /><br><br>
      <p><b>Description of assignment:</b></p>
      <textarea class="form-control" style="font-size:20px;" placeholder="Description of assignment" id="description" rows="7" cols="60" name="desc" maxlength="2000" required></textarea>
      <div id="charCount2"></div>
      <p><b>Prior Reading Options:</b></p>
      <label for="none">
        <input type="radio" id="none" name="prior_reading" onclick="show_div()" value="none" checked="checked" />
        No Prior Reading
      </label><br>
      <label for="hm_box">
        <input type="radio" id="hm_box" name="prior_reading" onclick="show_div()" value="hm_box" />
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
        <input type="file" onchange="validatePDF(this)" name="pdf_file" id="pdf_file" accept=".pdf" /><br>
      </div>
      <div id="show_youtube" style="display: none">
        <h1>Youtube Link:</h1>
        <input type="text" class="texty" name="youtubeURL" id="youtubeURL" placeholder="Youtube Link e.g. www.youtube.com/watch?v=ABCDEFG" /><br><br>
      </div>
      <div id="show_link" style="display: none">
        <h1>Link:</h1>
        <input type="text" class="texty" name="linkURL" id="linkURL" placeholder="Link e.g. https://google.com" /><br><br>
      </div>
      <div id="show_text" style="display: none">
        <textarea class="form-control" style="font-size:20px;" placeholder="Input the text you want the student to read" rows="10" id="text_box" name="text_box" maxlength="5000"></textarea>
        <div id="charCount"></div><br>
      </div>
      <label for="points"><b>Maximum Points On Completion: </b></label>
      <input type="number" class="login-input" name="points" value="50" max="500" min="10" required /><br><br>
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
      <br>
      <label for="time">Time</label>
      <input type="time" id="time" name="time" value="18:00" required><br><br>
      <label for="upload_type">What input type would you like to be submitted by students? </label>
      <select name="upload_type" id="upload_type">
        <option value="text">Text</option>
        <option value="pdf">PDF</option>
        <option value="none">No input required</option>
      </select><br><br>
      <button type="submit" class="login-button">Submit Assignment</button>
    </div>
  </form>
</body>

<script src="https://apis.google.com/js/api.js"></script>
<script src="../config.js"></script>
<script>
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
    if(document.getElementById('description').value.length > 2000){
        alert('description too long');
        return false;
    } 
    if(document.getElementById('description').value.length < 5){
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
  document.getElementById('linkURL').value = '';
  document.getElementById('youtubeURL').value = '';
  document.getElementById('text_box').value = '';
  document.getElementById('pdf_file').value = '';
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
                return Form.submit();
                // ALL GOOD!
              }
            },
            function(err) { console.error("Execute error", err); });

          }

  function show_div() {
      var text_box = document.getElementById("hm_box");
      var pdf = document.getElementById("pdf");
      var none = document.getElementById("none");
      var link = document.getElementById("link");
      var ytlink = document.getElementById("ytlink");
      var show_text = document.getElementById("show_text");
      var show_pdf = document.getElementById("show_pdf");
      var show_link = document.getElementById("show_link");
      var show_youtube = document.getElementById("show_youtube");
      show_text.style.display = text_box.checked ? "block" : "none";
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

</html>