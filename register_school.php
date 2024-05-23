<!-- 
File: register_school.php
Description: This file contains the register page to allow admins to register themselves and a school.
Author: Oliver Delew
Date: March 8, 2023
-->

<!DOCTYPE html>
<html>

<head>
  <title>Register School</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

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

    .error {
      color: #FF0000;
    }
  </style>
</head>

<body>
  <?php
  session_start();

  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\Exception;

  require 'PHPMailer-master/src/Exception.php';
  require 'PHPMailer-master/src/PHPMailer.php';
  require 'PHPMailer-master/src/SMTP.php';
  
  include 'connection.php';
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pswdErr = $cpswdErr = $usrErr = $nameErr = $emailErr = $institutionErr = $successful = "";
    $name = stripslashes($_POST['name']);
    $username = stripslashes($_POST['username']);
    $password = $_POST['pswd'];
    $cpassword = $_POST['cpswd'];

    $email    = stripslashes($_POST['personalemail']);
    $institutionemail = stripslashes($_POST['institution_email']);

    if (strpos($username, ' ') !== false) {
      $usrErr = '<br>Username should not contain spaces';
  } else {
      if (strlen($username) < 6) {
          $usrErr = "<br>Username too short";
      } elseif (strlen($username) > 14) {
          $usrErr = "<br>Username too long";
      }
  }

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

    if (strlen($name) < 5) {
      $nameErr = "<br>School name too short";
    } elseif (strlen($name) > 60) {
      $nameErr = "<br>School name too long";
    }
    if (strlen($email) < 6) {
      $emailErr = "<br>Email too short";
    } elseif (strlen($email) > 320) {
      $emailErr = "<br>Email too long";
    }
    if (strlen($institutionemail) < 6) {
      $institutionErr = "<br>Email too short";
    } elseif (strlen($institutionemail) > 320) {
      $institutionErr = "<br>Email too long";
    }
    if ($institutionemail[0] != '@') {
      $institutionErr = "<br>Email must start with an @ e.g. @gmail.com";
    } else {
      $institutionemail = ltrim($institutionemail, '@');
      // $institutionemail
    }
    $explodedEmail = explode('@', $email);
    if ($institutionemail != $explodedEmail[1]) {
      $emailErr = "<br>Email does not have the same ending as the institution email ending";
    }


    $sql = "SELECT username FROM student WHERE username = :username";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
      'username' => $username
    ]);
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
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
    // $counter = 0;
    while ($row = $stmt->fetch()) {
      $counter += 1;
    }
    $sql = "SELECT username FROM admin WHERE username = :username";
    $stmt = $pdo->prepare($sql);

    $stmt->execute([
      'username' => $username
    ]);
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    // $counter = 0;
    while ($row = $stmt->fetch()) {
      $counter += 1;
    }


    if ($counter > 0) {
      $usrErr = "<br>Username already taken";
    }

    $counter = 0;

    $sql = "SELECT email FROM teacher WHERE email = :email";
    $stmt = $pdo->prepare($sql);

    $stmt->execute([
      'email' => $email
    ]);
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    while ($row = $stmt->fetch()) {
      $counter += 1;
    }

    $sql = "SELECT email FROM student WHERE email = :email";
    $stmt = $pdo->prepare($sql);

    $stmt->execute([
      'email' => $email
    ]);
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    while ($row = $stmt->fetch()) {
      $counter += 1;
    }

    $sql = "SELECT email FROM admin WHERE email = :email";
    $stmt = $pdo->prepare($sql);

    $stmt->execute([
      'email' => $email
    ]);
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    while ($row = $stmt->fetch()) {
      $counter += 1;
    }


    if ($counter > 0) {
      $emailErr = "<br>Email already in use";
    }


    $sql = "SELECT school_name FROM school WHERE school_name = :school_name";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
      'school_name' => $name
    ]);
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $counter = 0;
    while ($row = $stmt->fetch()) {
      $counter += 1;
    }

    if ($counter > 0) {
      $nameErr = "<br>School name already taken";
    }


    if ($pswdErr == '' && $cpswdErr == '' && $usrErr == '' && $nameErr == '' &&  $institutionErr == '' && $emailErr == '') {
      $pinSet = false;
      $verification_code = bin2hex(random_bytes(3));
      while ($pinSet != true) {
        $sql = "SELECT school_pin FROM school WHERE school_pin = :school_pin";
        $stmt = $pdo->prepare($sql);
        $pin = bin2hex(random_bytes(3));

        $stmt->execute([
          'school_pin' => $pin
        ]);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $counter = 0;
        while ($row = $stmt->fetch()) {
          $counter += 1;
        }

        if ($counter == 0) {
          //   No school set yet so pin = 000000
          $pinSet = true;
        }
      }
      require 'config.php';
      $mail = new PHPMailer();
      $mail->IsSMTP();
      $mail->Mailer = "smtp";
      $mail->SMTPDebug  = 0;
      $mail->SMTPAuth   = TRUE;
      $mail->SMTPSecure = "tls";
      $mail->Port       = 587;
      $mail->Host       = "smtp.gmail.com";
      $mail->Username   = $config['username'];
      $mail->Password   = $config['password'];
      
      $mail->setFrom('olliedelew@gmail.com', 'EdVenture Land Team');
      $mail->addAddress($email, 'Administrator');
      $mail->addReplyTo('olliedelew@gmail.com', 'EdVenture Land Team');
      $mail->isHTML(true);                  // Set email format to HTML
      $mail->Subject = 'Successful Registration';
      $mail->Body    = "<h1>Dear Administrator,</h1>
            <p>You have successfully registered your account on EdVenture Land VLE.</p>
            <p>Your verification code is: <b>{$verification_code}</b></p>
            <p>Your school specific pin (for student's to get registered) is: <b>{$pin}</b></p>
            <p>Any questions please don't hesitate to email me at oliver.delew@student.manchester.ac.uk or olliedelew@gmail.com</p><p>Best wishes,<br><b>EdVenture Land Team</b></p>";
      $mail->AltBody = "Dear Administrator, You have successfully registered your account on EdVenture Land VLE. Your School specific pin is: {$pin} Any questions please don't hesitate to email me at oliver.delew@student.manchester.ac.uk or olliedelew@gmail.com.   Best wishes,EdVenture Land Team";
      if (!$mail->Send()) {
        $successful = "Error while sending Email, please check the Admin Email Address you entered.";
      } else {
        $sql = "INSERT INTO school(school_name, school_pin, school_email, admin_email)
            VALUES (:school_name, :school_pin, :school_email, :admin_email)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
          'school_name' => $name,
          'school_pin' => $pin,
          'school_email' => $institutionemail,
          'admin_email' => $email
        ]);
        $last_id = $pdo->lastInsertId();

        $successful = '<br>Successfully Registered School, A Pin has been sent to the admin\'s email address that will allow students to register their accounts<br> linked with the school and teachers can register by using their school email account';
        $sql = "INSERT INTO admin(username, email, password, school_id, verification_code)
            VALUES (:username, :email, :password, :school_id, :verification_code)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
          'username' => $username,
          'email' => $email,
          'password' => password_hash($password, PASSWORD_DEFAULT),
          'school_id' => $last_id,
          'verification_code' => $verification_code
        ]);
        $pdo = null;
        header('location: verification_page.php');
      }
    }
  }
  ?>

  <form class="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <div class="container" style="text-align:center;">
      <h1 class="login-title">Register A School</h1>
      <p><b>School Name</b></p><input type="text" class="texty" name="name" placeholder="School Name" required />
      <span class="error">* <?php echo $nameErr; ?></span>
      <br>
      <br>
      <p><b>Admin Username</b></p><input type="text" class="texty" name="username" placeholder="Admin Username" required />
      <span class="error">* <?php echo $usrErr; ?></span>
      <br>
      <br>
      <p><b>Admin School Specific Email Address</b></p><input type="email" class="texty" name="personalemail" placeholder="e.g. admin@school_email.com" required />
      <span class="error">* <?php echo $emailErr; ?></span>
      <br>
      <br>
      <p><b>Admin Password</b></p><input type="password" class="texty" name="pswd" placeholder="Admin Password" required />
      <span class="error">* <?php echo $pswdErr; ?></span>
      <br>
      <br>
      <p><b>Confirm Password</b></p><input type="password" class="texty" name="cpswd" placeholder="Confrim Password" required />
      <span class="error">* <?php echo $cpswdErr; ?></span>
      <br>
      <br>
      <p><b>Institution Specific Email Address</b></p><input type="text" class="texty" name="institution_email" placeholder="e.g. @school_email.com" required />
      <span class="error">* <?php echo $institutionErr; ?></span>
      <br>
      <br>
      <span class="error"><?php echo $successful; ?></span>
      <input type="submit" name="submit" value="Register" class="login-button">
      <p>To go back to the login page <a href="login.php">Click Here</a></p>
      <p>Already have a verification token? <a href="verification_page.php">Click Here</a></p>
    </div>

    </div>
  </form>
</body>

</html>
<?php

?>