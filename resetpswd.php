<!-- 
File: resetpswd.php
Description: This file contains the reset password page to allow students, teachers and admins to input the reset password 
token they have been sent to reset their password.
Author: Oliver Delew
Date: March 8, 2023
-->

<!DOCTYPE html>
<html>

<head>
  <title>Reset Password</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <style>
    body {
      background-color: lightblue;
    }

    .texty {
      width: 250px;
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

    .token_box {
      --width: 250px;
      --height: 60px;
      --spacing: 25px;
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

    .boxy {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      background-color: orange;
      padding: 20px;
      border-radius: 10px;
      text-align: center;

    }
  </style>

</head>

<body>
  <?php
  session_start();
  include 'connection.php';

  // client side validation
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Set all errors to empty quotes so any resubmission resets previous errors
    $tokenErr = $pswdErr = $cpswdErr = $successful = "";
    $password = $_POST['newpswd'];
    $cpassword = $_POST['cnewpswd'];
    $token = $_POST['token'];
    // Check both the passwords are the same firstly then check if the password is too long or short
    if ($password != $cpassword) {
      $pswdErr = "Passwords not the same";
      $cpswdErr = "Passwords not the same";
    } elseif (strlen($password) < 6) {
      $pswdErr = "Password is too short";
      $cpswdErr = "Password is too short";
    } elseif (strlen($password) > 15) {
      $pswdErr = "Password is too long";
      $cpswdErr = "Password is too long";
    }
    // If the token is not 6 characters long then it is not a correct token
    if (strlen($token) != 6) {
      $tokenErr = 'Token incorrect';
    }
    // If there are not errors then
    if ($tokenErr == '' && $pswdErr == '' && $cpswdErr == '' && $successful == '') {
      // Hash the password using the current default hashing algorithm
      $pswdUpload = password_hash($password, PASSWORD_DEFAULT);
      // Goes through each of the teacher, student and admin table and when it finds the unique reset token then it will update the password
      $sql = "UPDATE `teacher` SET `password`= :password, `reset_token` = NULL WHERE `reset_token`= :token";
      $stmt = $pdo->prepare($sql);
      // if executed then go to the login page
      $stmt->execute([
        'password' => $pswdUpload,
        'token' => $token
      ]);
        // else check the student table
        $sql = "UPDATE `student` SET `password`= :password, `reset_token` = NULL WHERE `reset_token`= :token";
        $stmt = $pdo->prepare($sql); 
        // if executed then go to the login page 
        $stmt->execute([
          'password' => $pswdUpload,
          'token' => $token
        ]);
          // else check the admin table
          $sql = "UPDATE `admin` SET `password`= :password, `reset_token` = NULL WHERE `reset_token`= :token";
          $stmt = $pdo->prepare($sql);
          // if executed then go to the login page
          $stmt->execute([
            'password' => $pswdUpload,
            'token' => $token
          ]);
            
            // Else return error
            $successful = 'Error updating password';
            $pdo = null;
            header("location: login.php");
    }
  }
  ?>
  <!-- Here we have a form which uses the server variable PHP_SELF to do client side validation -->
  <form class="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <!-- Hold everything in a container -->
    <div class="container" style="text-align:center;">
    <!-- surround everything in an orange box -->
      <div class="boxy">
        <h1 text-align:>Reset Password</h1>
        <p>Please input the token you have recieved in your email to setup the new pswd</p>
        <!-- Input the reset password token here -->
        <div class="token_box" style='text-align:left;'>
          <input class="enter_pin" name="token" type="text" maxlength=6 autocomplete=off required />
        </div>
        <span class="error">
          <p><?php echo $tokenErr; ?></p>
        </span>
        <!-- Input the new password (twice) -->
        <input type="password" class='texty' name="newpswd" placeholder="New Password" required />
        <span class="error">
          <p><?php echo $pswdErr; ?></p>
        </span>
        <input type="password" class='texty' name="cnewpswd" placeholder="Confirm Password" required />
        <span class="error">
          <p><?php echo $cpswdErr; ?></p>
        </span>
        <span class="error">
          <p><?php echo $successful; ?></p>
        </span>
        <input type="submit" id="Login" value="Reset" name="submit" class="login-button" />
        <p>Remembered your password? <a href="login.php"> Click here</a></p>
        <p>Don't have an account? <a href="registration.php">Register Now</a></p>
      </div>
  </form>

</body>

</html>