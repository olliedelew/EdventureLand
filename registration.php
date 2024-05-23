<!-- 
File: registration.php
Description: This file contains the register page to allow students and teachers to register their accounts with a school.
Author: Oliver Delew
Date: March 8, 2023
-->

<!DOCTYPE html>
<html>

<head>
  <title>Registration</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

  <script>
    function show_div() {
      var student = document.getElementById("student");
      var staff = document.getElementById("staff");
      var email_input = document.getElementById("institution_email_input");
      var access_token = document.getElementById("access_token");
      email_input.style.display = staff.checked ? "block" : "none";
      access_token.style.display = student.checked ? "block" : "none";
    }
  </script>


  <style>
    body {
      background-color: lightblue;
    }

    /* Full-width input fields */
    .texty {
      width: 30%;
      padding: 12px 20px;
      margin: 0px 0;
      display: inline-block;
      border: 1px solid black;
      box-sizing: border-box;
    }

    .login-button {
      background-color: green;
      color: white;
      width: 25%;
      padding: 7px 10px;
      border: 5px solid black;
      cursor: pointer;
      font-size: 20px;

    }

    select {
      display: block;
      margin: 0 auto;
    }

    .token_box {
      --width: 325px;
      --height: 60px;
      --spacing: 34px;
      margin-left: auto;
      margin-right: auto;
      display: block;
      position: relative;
      width: var(--width);
      height: var(--height);
      background-color: white;
    }

    .error {
      color: #FF0000;
    }

    .enter_pin {
      position: absolute;
      padding-left: 21px;
      font-size: var(--spacing);
      height: var(--height);
      letter-spacing: var(--spacing);
      border: 0;
      outline: none;
      clip: rect(0px, calc(var(--width) - 21px), var(--height), 0px);
    }
  </style>
</head>

<body>
  <?php
  session_start();
  include 'connection.php';

  // Here is the client side validation
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Set all errors to empty quotes so any resubmission resets previous errors
    $pswdErr = $cpswdErr = $fnErr = $lnErr = $failure = $usrErr = $emailErr = $tokenErr = $student_staff_err = $authemailErr = "";
    
    // Get all the post variables that have been sent by the form
    $username = stripslashes($_POST['username']);
    $personalemail = stripslashes($_POST['personalemail']);
    $email    = stripslashes($_POST['email']);
    $student_or_staff = $_POST['student_o_staff'];
    $password = $_POST['password'];
    $firstName = stripslashes($_POST['fn']);
    $lastName = stripslashes($_POST['ln']);
    $cpassword = $_POST['confirmpassword'];
    $year = $_POST['years'];
    $token = $_POST['token'];

    // Here we do all the client side validation to make sure usernames, first names and last names do not contain spaces
    // If they dont then we check the length of each variable to make sure it is not too long or too short
    if (strpos($username, ' ') !== false) {
      $usrErr = '<br>Username should not contain spaces';
  } else {
      if (strlen($username) < 6) {
          $usrErr = "<br>Username too short";
      } elseif (strlen($username) > 14) {
          $usrErr = "<br>Username too long";
      }
  }
  if (strpos($firstName, ' ') !== false) {
    $fnErr = '<br>First name should not contain spaces';
    } else {
      if (strlen($firstName) < 3) {
        $fnErr = "<br>First name is too short";
      } elseif (strlen($firstName) > 30) {
        $fnErr = "<br>First name is too long";
      }
    }
    if (strpos($lastName, ' ') !== false) {
      $lnErr = '<br>First name should not contain spaces';
      } else {
        if (strlen($lastName) < 3) {
          $lnErr = "<br>Last name is too short";
        } elseif (strlen($lastName) > 30) {
          $lnErr = "<br>Last name is too long";
        }
      }
    
    // Check if the passwords are the same, else produce errors and if they are then check the length
    if ($password != $cpassword) {
      $pswdErr = "<br>Passwords not the same";
      $cpswdErr = "<br>Passwords not the same";
    } elseif (strlen($password) < 6) {
      $pswdErr = "<br>Password is too short";
      $cpswdErr = "<br>Password is too short";
    } elseif (strlen($password) > 15) {
      $pswdErr = "<br>Password is too long";
      $cpswdErr = "<br>Password is too long";
    }

    // Checks the emails entered by students and staff
    if ($student_or_staff == 'Staff') {
      if ($email == '') {
        $authemailErr = "<br>Must input email to be a teacher";
      } elseif (strlen($email) > 320) {
        $authemailErr = "<br>Email is too long";
      }
      if ($personalemail == '') {
        $emailErr = "<br>Must input email to be a teacher";
      } elseif (strlen($personalemail) > 320) {
        $emailErr = "<br>Email is too long";
      }
    } else {
      if ($personalemail == '') {
        $emailErr = "<br>Must input email";
      } elseif (strlen($personalemail) > 320) {
        $emailErr = "<br>Email is too long";
      }
    }

    // Here we check if the username has already been taken by either: Students, Teachers or Admins as we do not want clashing unsernames
    $sql = "SELECT username FROM student WHERE username = :username";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
      'username' => $username
    ]);
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    
    // Create a counter and check if by the end it has increased by 1 then we know that someone else has the username
    $counter = 0;
    while ($row = $stmt->fetch()) {
      $counter += 1;
    }
    $sql = "SELECT username FROM teacher WHERE username = :username";
    $stmt = $pdo->prepare($sql);

    $stmt->execute([
      'username' => $username
    ]);
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    while ($row = $stmt->fetch()) {
      $counter += 1;
    }
    $sql = "SELECT username FROM admin WHERE username = :username";
    $stmt = $pdo->prepare($sql);

    $stmt->execute([
      'username' => $username
    ]);
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    while ($row = $stmt->fetch()) {
      $counter += 1;
    }
    // If the counter has changed values then the username has already been taken
    if ($counter > 0) {
      $usrErr = "<br>Username already taken";
    }

    // Now we will check if the personal email has already been taken as this would cause issues when requesting a new password
    // We do the same method as when we checked the username
    $counter = 0;

    $sql = "SELECT email FROM teacher WHERE email = :email";
    $stmt = $pdo->prepare($sql);

    $stmt->execute([
      'email' => $personalemail
    ]);
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    while ($row = $stmt->fetch()) {
      $counter += 1;
    }

    $sql = "SELECT email FROM student WHERE email = :email";
    $stmt = $pdo->prepare($sql);

    $stmt->execute([
      'email' => $personalemail
    ]);
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    while ($row = $stmt->fetch()) {
      $counter += 1;
    }

    $sql = "SELECT email FROM admin WHERE email = :email";
    $stmt = $pdo->prepare($sql);

    $stmt->execute([
      'email' => $personalemail
    ]);
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    while ($row = $stmt->fetch()) {
      $counter += 1;
    }
    if ($counter > 0) {
      $emailErr = "<br>Email already in use";
    }

    

    if ($student_or_staff == 'Staff') {
      // We then check if the user is a Staff member then check if hte institute email has already been taken
      $counter = 0;
      $sql = "SELECT institute_email FROM teacher WHERE institute_email = :institute_email";
      $stmt = $pdo->prepare($sql);

      $stmt->execute([
        'institute_email' => $email
      ]);
      $stmt->setFetchMode(PDO::FETCH_ASSOC);
      // $counter = 0;
      while ($row = $stmt->fetch()) {
        $counter += 1;
      }
      $sql = "SELECT email FROM admin WHERE email = :email";
      $stmt = $pdo->prepare($sql);

      $stmt->execute([
        'email' => $email
      ]);
      $stmt->setFetchMode(PDO::FETCH_ASSOC);
      // $counter = 0;
      while ($row = $stmt->fetch()) {
        $counter += 1;
      }

      if ($counter > 0) {
        $authemailErr = "<br>Email already in use";
      } else {
        
        // If the email is not in use then we make sure that the ending on the email e.g. @gmail.com is associated with a registered school
        $counter = 0;
        $sql = "SELECT school_id, verified FROM school WHERE school_email = :school_email";
        $stmt = $pdo->prepare($sql);
        $temp_email = explode("@", $email);
        $stmt->execute([
          'school_email' => $temp_email[1]
        ]);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        while ($row = $stmt->fetch()) {
          $counter += 1;
          $school_id = $row['school_id'];
          $verified = $row['verified'];
          // If the school has not yet been verified then produce an error and do not let any users register to that school
          if($verified == 0) {
            $failure = 'School has not yet been verified please speak to the administrator';
          }
        }
        // If the counter has stayed the same then the email is incorrect
        if ($counter == 0) {
          $authemailErr = "<br>Incorrect Institution Email Ending";
        }
      }
    } elseif ($student_or_staff == 'Student') {
      if ($token != '') {
        // Here we check if the student has entered the correct pin to register with the school
        $sql = "SELECT school_id, verified FROM school WHERE school_pin = :school_pin";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
          'school_pin' => $token
        ]);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $counter = 0;
        while ($row = $stmt->fetch()) {
          $counter += 1;
          $school_id = $row['school_id'];
          $verified = $row['verified'];
          // If the school has not yet been verified then produce an error and do not let any users register to that school
          if($verified == 0) {
            $failure = 'School has not yet been verified please speak to the administrator';
          }
        }
        // If the counter has stayed the same then the pin is incorrect
        if ($counter == 0) {
          $tokenErr = "Incorrect access code";
        }
      } else {
        $tokenErr = 'Must input an access code';
      }
    }

    // Here we check that there are no errors before proceeding
    if ($usrErr == '' && $failure == '' && $fnErr == '' &&  $lnErr == '' &&  $emailErr == '' &&  $cpswdErr == '' && $student_staff_err == '' && $pswdErr == '' &&  $tokenErr == '' &&  $authemailErr == '') {

      // Join up the first and last name
      $name = $firstName . ' ' . $lastName;
      // If it is a student registering then add them to the student table
      if ($student_or_staff == 'Student') {
        $sql = "INSERT INTO student(username, name, password, first_name, surname, email, profile_picture, student_year, school_id, last_login)
          VALUES (:username, :name, :password, :first_name, :surname, :email, :profile_picture, :student_year, :school_id, :last_login)";
        $stmt = $pdo->prepare($sql);
        // We hash the password using the default hashing algorithm as this will always take the best current algorithm
        $stmt->execute([
          'username' => $username,
          'name' => $name,
          'first_name' => $firstName,
          'surname' => $lastName,
          'email' => $personalemail,
          'password' => password_hash($password, PASSWORD_DEFAULT),
          'profile_picture' => 'emptyIcon.png',
          'student_year' => intval($year),
          'school_id' => $school_id,
          'last_login' => date('Y-m-d')
        ]);
        // We then take the ID that was insterted, created the session variables for the user and send them to the homepage
        $last_id = $pdo->lastInsertId();
        $_SESSION['user'] = $username;
        $_SESSION['isStaff'] = 'no';
        $_SESSION['student_id'] = $last_id;
        $_SESSION['school_id'] = $school_id;
        $pdo = null;
        header("location: student/student_homepage.php");
      } else {
        $sql = "INSERT INTO teacher(username, email, institute_email, name, password, profile_picture, first_name, surname, school_id)
          VALUES (:username, :email, :institute_email, :name, :password, :profile_picture, :first_name, :surname, :school_id)";
        $stmt = $pdo->prepare($sql);
        // We hash the password using the default hashing algorithm as this will always take the best current algorithm
        $stmt->execute([
          'username' => $username,
          'email' => $personalemail,
          'institute_email' => $email,
          'name' => $name,
          'first_name' => $firstName,
          'surname' => $lastName,
          'password' => password_hash($password, PASSWORD_DEFAULT),
          'school_id' => $school_id,
          'profile_picture' => 'emptyIcon.png'
        ]);
        // We then take the ID that was insterted, created the session variables for the user and send them to the homepage
        // $last_id = $pdo->lastInsertId();
        // $_SESSION['user'] = $username;
        // $_SESSION['isStaff'] = 'yes';
        // $_SESSION['teacher_id'] = $last_id;
        // $_SESSION['school_id'] = $school_id;
        $pdo = null;
        header("location: login.php");
      }
    }
  }
  ?>
  <!-- Here we have a form which uses the server variable PHP_SELF to do client side validation -->
  <form class="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
  <!-- Hold everything in a container -->
    <div class="container" style="text-align:center;">
      <h1>Registration</h1>
      <!-- Here are all the different fields we ask the users to fill in to get registered on the VLE -->
      <p><b>Username</b></p><input type="text" class="texty" name="username" placeholder="Username" required />
      <span class="error">* <?php echo $usrErr; ?></span>
      <br>
      <br>
      <p><b>First Name</b></p><input type="text" class="texty" name="fn" placeholder="First Name" required />
      <span class="error">* <?php echo $fnErr; ?></span>
      <br>
      <br>
      <p><b>Last Name</b></p><input type="text" class="texty" name="ln" placeholder="Last Name" required />
      <span class="error">* <?php echo $lnErr; ?></span>
      <br>
      <br>
      <p><b>Personal Email</b></p><input type="email" class="texty" name="personalemail" placeholder="Personal Email" required />
      <span class="error">* <?php echo $emailErr; ?></span>
      <br>
      <br>
      <p><b>Password</b></p><input type="password" class="texty" name="password" placeholder="Password" required>
      <span class="error">* <?php echo $pswdErr; ?></span>
      <br>
      <br>
      <p><b>Confirm Password</b></p><input type="password" class="texty" name="confirmpassword" placeholder="Confirm Password" required>
      <span class="error">* <?php echo $cpswdErr; ?></span>
      <div>
        <br>
        <!-- Here we have radio buttons to check whether the person registering is a student or a staff -->
        <label for="student">
          <input type="radio" id="student" name="student_o_staff" onclick="show_div()" value="Student" required/>
          Student
        </label>
        <label for="staff">
          <input type="radio" id="staff" name="student_o_staff" onclick="show_div()" value="Staff" required/>
          Teacher
        </label>
        <span class="error"><?php echo $student_staff_err; ?></span>
      </div>
      <!-- If the teacher radio button has been selected then this div will show up -->
      <div id="institution_email_input" style="display: none">
        <br>
        <!-- Ask the teacher for their institution specific email address -->
        <p style='text-align:center'><b>Institution email address</b></p>
        <input type="email" class="texty" name="email" placeholder="e.g. name@institute_specific_email.com" />
        <span class="error">* <?php echo $authemailErr; ?></span>
      </div>
    </div>
      <!-- If the student radio button has been selected then this div will show up -->
    <div id="access_token" style="display: none">
      <br>
      <!-- Ask the student for the school specific access code (this will have been given by the teacher) -->
      <p style='text-align:center;'><b>Access code:</b></p>
      <!-- Here is where the student will have to input the access token -->
      <div class="token_box">
        <input class="enter_pin" name="token" type=text maxlength=6 placeholder="TOKEN" autocomplete=off>
      </div>
      <p class="error" style='text-align:center;'><?php echo $tokenErr; ?></p>
      <!-- Also ask what year the student is in -->
      <p style='text-align:center'><b>What year are you in?</b></p>
      <select name="years" id="years">
        <option value="7">Year 7</option>
        <option value="8">Year 8</option>
        <option value="9">Year 9</option>
        <option value="10">Year 10</option>
        <option value="11">Year 11</option>
      </select>
    </div>

    <div class="container" style="text-align:center;">
      <br>
      <!-- Finally have submit button and other links -->        
      <p class="error"><?php echo $failure; ?></p>
      <input type="submit" name="submit" value="Register" class="login-button">
      <p>If you already have an account <a href="login.php">Login Here</a></p>
      <p>If you want to register a school <a href="register_school.php">Click Here</a></p>
    </div>

    </div>
  </form>
</body>

</html>
<?php
?>