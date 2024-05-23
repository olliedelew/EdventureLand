<?php
    session_start();
    if(!isset($_SESSION['user']) || !isset($_SESSION['school_id']) || (!isset($_SESSION['student_id']) && !isset($_SESSION['teacher_id']))){
        echo '<script>location.href = "../login.php";</script>';
      }
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

    <title>New Discussion</title>
    <style>
        *{
            font-size: 20px
        }
        textarea {
            resize: none;
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

.texty {
  width: 30%;
  padding: 12px 20px;
  margin: 0px 0;
  display: inline-block;
  border: 1px solid black;
  box-sizing: border-box;
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
    </style>
    <link rel="stylesheet" href="../style.css" />

</head>
<body>

    <div class="container" style="text-align:center;">
    <h1 style="text-align:left;">Upload Discussion</h1>
        <?php
        if($_SESSION['isStaff'] == 'no'){
        ?>
        <div class="row" style="background-color:white;">
            <div class="col-sm-3">
                <br>
                <div class="card">
                    <div class="card-body">

                        <button type="button" class="button" onclick="location.href='student_homepage.php'">Games</button><br><br>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <br>
                <div class="card">
                    <div class="card-body">
                        <button type="button" class="button" id="activated" onclick="location.href='db_grouper.php'">Discussion Boards</button><br><br> <!-- Student groupmates -->
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <br>
                <div class="card">
                    <div class="card-body">
                        <button type="button" class="button" onclick="location.href='student_assignments.php'">Assignments</button><br><br>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <br>
                <div class="card">
                    <div class="card-body">
                        <button type="button" class="button" onclick="location.href='student_settings.php'">Account</button><br><br>
                    </div>
                </div>
            </div>
        </div>
        <?php
        } else if($_SESSION['isStaff'] == 'yes'){
        ?>
        <div class="row" style="background-color:white;">
            <div class="col-sm-3">
                <br>
                <div class="card">
                    <div class="card-body">
                        <button type="button" class="button" id="activated" onclick="location.href='../teacher/teacher_profile.php'">Homepage</button><br><br>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <br>
                <div class="card">
                    <div class="card-body">
                        <button type="button" class="button" onclick="location.href='../teacher/groups.php'">Student Groups</button><br><br> <!-- Student groupmates -->
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <br>
                <div class="card">
                    <div class="card-body">
                        <button type="button" class="button" onclick="location.href='../teacher/assset.php'">Assignments Set</button><br><br>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <br>
                <div class="card">
                    <div class="card-body">
                        <button type="button" class="button" onclick="location.href='../teacher/set_assignment.php'">Set Assignments</button><br><br>
                    </div>
                </div>
            </div>

        </div>

        <?php
        }
        ?>

    <?php
    if(isset($_POST['discussion_board_id']) && isset($_POST['group_id'])){
     echo '<form id="myForm" class="form" action="upload_to_db_grouper.php" method="post">';   
     echo '<input type="hidden" name="group_id" value="'. $_POST['group_id'] .'">
     <input type="hidden" name="discussion_board" value="'. $_POST['discussion_board'] .'">
     <input type="hidden" name="discussion_board_id" value="'. $_POST['discussion_board_id'] .'">';
    } elseif(isset($_POST['group_id'])){
      echo '<form id="myForm" class="form" action="upload_to_db_grouper.php" method="post">';  
      echo '<input type="hidden" name="group_id" value="'. $_POST['group_id'] .'">';
    } else {
        header('location: db_grouper.php');
    }

    if(isset($_POST['currenturl'])){
        $query = $_POST['currenturl'];
        $query = rtrim($query, "/");
        echo '<input type="hidden" name="currenturl" value=' . $query . '/>';
    }
    ?>
            <div class="col-md-12">
            <div style="display: none" id="loading-overlay">
                <div id="loading_symbol"></div>
                <p id="loading-text">Please wait while we check for rude words inside the text...</p>
            </div>
            <div class="row">
                <br>
            <p><b>Title</b></p>
            <input type="text" class="texty" name="title" id="title" placeholder="Title" required />
            <span class="error"><p id="titleErr"></p></span>
            </div>

            <br>
            <div class="row">

            <p><b>Body</b></p>
            <textarea class="form-control" style="font-size: 20px;" placeholder="Body of text" rows="10" id="body" name="body" maxlength="2000" required></textarea>           
            <span class="error"><p id="bodyErr"></p></span> 

            <div id="charCount"></div>
            </div>
            <?php
            if($_SESSION['isStaff'] == 'no'){
            ?>
            <div class="row">
            <label for="anon">Would you like to be anonymous?</label>
            <input type="checkbox" id="anon" name="anon" value="anonymous">
            </div>
            <?php
            }
            ?>


            <br>
            <span class="error"><p id="rudeWordsErr"></p></span> 
            <div class="row">

            <button type="submit" class="button">Upload Discussion</button>
            </div>
            </div>
        </form>
    </div>
<script src="../config.js"></script>
<script>
    const textarea = document.getElementById("body");
const charCount = document.getElementById("charCount");
const maxChars = 2000;

textarea.addEventListener("input", function() {
  const currentChars = textarea.value.length;
  charCount.textContent = `${currentChars}/${maxChars}`;
});

    const Form = document.getElementById('myForm');
    Form.addEventListener('submit', e => {
        e.preventDefault();
        var title = document.getElementById('title').value;
        var basetext = document.getElementById('body').value;
        var titleErr = '';
        var bodyErr = '';
        if(title == ''){
            titleErr = 'Must input a title';
        } else if (title.length > 60){
            titleErr = 'Title too long';
        }  else if (title.length < 5){
            titleErr = 'Title too short';
        }
        if(basetext == ''){
            bodyErr = 'Must input a body';
        } else if (basetext.length > 2000){
            bodyErr = 'Body too long';
        }  else if (basetext.length < 5){
            bodyErr = 'Body too short';
        }

        if(titleErr == '' && bodyErr == ''){
        loader = document.getElementById('loading-overlay');
        loader.style.display = 'block';

        var myHeaders = new Headers();
        myHeaders.append("apikey", config.apiKeyBad);
        var raw = title + ' ' + basetext
        raw = raw.replace(/(?:\r\n|\r|\n)/g, '%20');

        var requestOptions = {
        method: 'POST',
        redirect: 'follow',
        headers: myHeaders,
        body: raw
        };
        fetch("https://api.apilayer.com/bad_words?censor_character=*", requestOptions)
        .then(response => response.json())
        .then(result => {
            if (result.bad_words_total == 0) {
                loader.style.display = 'none';
                Form.submit();
            } else {
                loader.style.display = 'none';
                console.log(result);
                document.getElementById('rudeWordsErr').innerHTML = 'There seems to be some rude words in your title or body please look over what you have written';
            }
        })
        .catch(error => {loader.style.display = 'none'; console.log('error', error);});

        } else {
            document.getElementById('bodyErr').innerHTML = bodyErr;
            document.getElementById('titleErr').innerHTML = titleErr;
        }
    });
</script>
</body>
</html>