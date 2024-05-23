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

.texty {
  width: 50%;
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

<?php
if(!isset($_POST['manual_id'])){
  $pdo = null;
  header('location: assset.php');
}
$id = $_POST['manual_id'];
$sql = "SELECT * FROM manual_assignment WHERE manual_assignment_id = :manual_assignment_id";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    'manual_assignment_id' => $id
]);

$stmt->setFetchMode(PDO::FETCH_ASSOC);
while ($row = $stmt->fetch()) {
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
    $submission_type = $row['submission_type'];
    $description = str_replace("<br />", "", $row['description']);
}
$pdo = null;
?>
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

  <form name="myForm" id="myForm" action="updateSql.php" enctype="multipart/form-data" method="post">
    <?php
    $explodedURL = explode('?', $_POST['url']);
    echo '<input type="hidden" name="url" value="' . $explodedURL[1] . '">';
        echo '<input type="hidden" name="id" id="id" value=' . $_POST['manual_id'] . '>';
        echo '<input type="hidden" name="type" id="type" value="manual">';

    ?>
    <br>
    <!-- OR SEND USER TO THE ASSSET.php where they can see it set -->
      <p><b>Input title of assignment:</b></p>
      <input type="text" class="texty" name="title" id="title" placeholder="Title" value = "<?php echo $title; ?>" required /><br>
      <br><p><b>Description of assignment:</b></p>
      <textarea class="form-control" placeholder="Description of assignment" style="font-size:20px;" id="desc" rows="7" cols="60" name="desc" maxlength="2000" required><?php echo $description; ?></textarea>
      <div id="charCount2"></div><br>
      <p><b>Prior Reading Options:</b></p>
      <label for="none">
        <?php
        echo '<input type="radio" id="none" name="prior_reading" onclick="show_div()" value="none" ';
        if($prior_reading == 'none'){ echo "checked";}
        echo ' /> No Prior Reading';
        ?>
      </label><br>
      <label for="hm_box">
      <?php
        echo '<input type="radio" id="hm_box" name="prior_reading" onclick="show_div()" value="hm_box" ';
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
        <?php 
          if($prior_reading == 'pdf'){ 
              echo 'If you would like to change the file then please input a new one below:';
          } 
        ?>
    
        <input type="file" onchange="validatePDF(this)" name="pdf_file" id="pdf_file" accept=".pdf" /> <br>
      </div>
        <div id="show_youtube" <?php if($prior_reading == 'ytlink'){ echo 'style="display: block"'; } else { echo 'style="display: none"'; }?> >
        <h1>Youtube Link:</h1>
        <input type="text" class="texty" name="youtubeURL" id="youtubeURL" placeholder="Youtube Link e.g. www.youtube.com/watch?v=ABCDEFG" value=<?php if($prior_reading == 'ytlink'){ echo $ytlink;} ?>> </input><br>
      </div>
      <div id="show_link" <?php if($prior_reading == 'link'){ echo 'style="display: block"'; } else { echo 'style="display: none"'; } ?>>
        <h1>Link:</h1>
        <input type="text" class="texty" name="linkURL" id="linkURL" placeholder="Link e.g. https://google.com" value=<?php if($prior_reading == 'link'){ echo $link;} ?>></input><br><br>

              </div>
      <div id="show_text" <?php if($prior_reading == 'text_box'){ echo 'style="display: block"'; } else { echo 'style="display: none"'; } ?>>
      <textarea class="form-control" style="font-size:20px;" placeholder="Input the text you want the student to read" rows="10" id="text_box" name="text_box" maxlength="5000"><?php if($prior_reading == 'text_box'){ echo $text_box;} ?></textarea>
        <div id="charCount"></div>

      </div>

      <br><label for="points">Maximum Points On Completion</label>
      <input type="number" class="login-input" name="points" value= <?php echo $points?> max="500" min="10" required /><br><br>
      <div id="date-picker-example" class="md-form md-outline input-with-post-icon datepicker" inline="true">
        <label class="control-label" for="date">Due Date</label>
        <input class="login-input" id="date" name="date" placeholder="MM-DD-YYYY" type="text" value=<?php echo $date?> required/>
      </div>
      <br>
      <label for="time">Time</label>
      <input type="time" id="time" name="time" value=<?php echo $time?> required><br><br>
      <label for="type">What input type would you like to be submitted by students? </label>
      <?php 
      echo '<select name="submission_type" id="submission_type">';
      echo '<option value="text" ';
      if($submission_type == 'text'){ echo "selected";}
      echo '>Text</option>';
      echo '<option value="pdf" ';
      if($submission_type == 'pdf'){ echo "selected";}
      echo '>PDF</option>';
      echo '<option value="none" ';
      if($submission_type == 'none'){ echo "selected";}
      echo '>No input required</option></select><br><br>';

      ?>
      <button class="submit" id="sub">Update</button>
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
const textarea2 = document.getElementById("desc");
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