<!DOCTYPE html>
<html>

<head>
  <title>Verification Page</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <style>
    body {
      background-color: lightblue;
    }

    /* Full-width input fields */
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
    $tokenErr = $successful = "";
    // get the token for the input
    $token = $_POST['token'];
    // if the token is not 6 characters then it is incorrect
    if (strlen($token) != 6) {
      $tokenErr = 'Token incorrect';
    }
    // if there are no errors then
    if ($tokenErr == '') {
      // find the admin where the verification code is the token and get the school id
      $sql = "SELECT school_id, username FROM admin WHERE verification_code = :token";
      $stmt = $pdo->prepare($sql);
      $stmt->execute([
        'token' => $token
      ]);
      $stmt->setFetchMode(PDO::FETCH_ASSOC);
      $counter = 0;
      while ($row = $stmt->fetch()) {
        $counter += 1;
        $school_id = $row['school_id'];
        $username = $row['username'];
      }

      if($counter == 0){
        $tokenErr = 'Token incorrect';
      } else {
        // then update the admin and school to be verified
        $sql = "UPDATE `admin` SET `verification_code`= NULL, `verified` = 1 WHERE `verification_code`=:token";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
          'token' => $token
        ]);
  
        $sql = "UPDATE `school` SET `verified`= 1 WHERE `school_id`='$school_id'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $_SESSION['user'] = $username;
        $_SESSION['isStaff'] = 'yes';
        $_SESSION['school_id'] = $school_id;
        $pdo = null;
        header("location: admin/admin_homepage.php");
  
      }
    }
  }
  ?>
    <!-- Here we have a form which uses the server variable PHP_SELF to do client side validation -->
  <form class="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <!-- Hold everything in a container -->
    <div class="container" style="text-align:center;">
    <!-- surround everything in an orange box -->
      <div class="boxy">
        <h1 text-align:>Verify Admin Account</h1>
        <p>Please input the verification code you have recieved in your email to setup the school and admin's account</p>
        <!-- Input the reset password token here -->
        <div class="token_box" style='text-align:left;'>
          <input class="enter_pin" name="token" type="text" maxlength=6 autocomplete=off required />
        </div>
        <span class="error">
          <p><?php echo $tokenErr; ?></p>
        </span>
        <span class="error">
          <p><?php echo $successful; ?></p>
        </span>
        <input type="submit" value="Verify" name="submit" class="login-button" />
        <p>Already have an account? <a href="login.php"> Click here</a></p>
        <p>Want to register as a teacher or student? <a href="registration.php">Register Now</a></p>

      </div>
  </form>

</body>

</html>