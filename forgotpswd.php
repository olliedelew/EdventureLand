<!-- 
File: forgotpswd.php
Description: This file contains the forgot password page to allow a user to get sent an email to reset their password.
Author: Oliver Delew
Date: March 8, 2023
-->

<!DOCTYPE html>
<html>

<head>
<title>Forgot Password</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <style>
        body {
            background-color: lightblue;
        }

        /* Full-width input fields */
        .texty {
            width: 50%;
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
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    require 'PHPMailer-master/src/Exception.php';
    require 'PHPMailer-master/src/PHPMailer.php';
    require 'PHPMailer-master/src/SMTP.php';

    include 'connection.php';
    function sendMail($row, $pin, $user){
        include 'connection.php';
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
        if (!empty($row['email'])) {
            // Set up the email to be sent
            $mail->setFrom('olliedelew@gmail.com', 'EdVenture Land Team');
            $mail->addAddress($row['email'], 'EdVenture Land User');
            $mail->addReplyTo('olliedelew@gmail.com', 'EdVenture Land Team');
            $mail->isHTML(true); // Set email format to HTML
            $mail->Subject = 'Reset Password';
            $mail->Body    = "<h1>Dear User,</h1>
            <h3>PLEASE IGNORE THIS EMAIL IF YOU HAVE NOT REQUESTED A PASSWORD CHANGE!</h3>
            <p>The token to reset your password is: <b>{$pin}</b></p> 
            <p>Any questions please don't hesitate to email me at oliver.delew@student.manchester.ac.uk or 
            olliedelew@gmail.com</p><p>Best wishes,<br><b>EdVenture Land Team</b></p>";
            $mail->AltBody = "Dear User, PLEASE IGNORE THIS EMAIL IF YOU HAVE NOT REQUESTED A PASSWORD CHANGE! 
            The token to reset your password is: {$pin} Any questions please don't hesitate to email me at 
            oliver.delew@student.manchester.ac.uk or olliedelew@gmail.com Best wishes,EdVenture Land Team";
            // Now attempt to send the email
            if (!$mail->Send()) {
                // If the email failed sending then show this error
                return "Error while sending Email.";
            } else {
                // If the email succeeded then set the reset token for the Student
                if($user == 'student'){
                $query = "UPDATE student SET reset_token = '$pin' WHERE email = :email";
                } elseif($user == 'teacher'){
                $query = "UPDATE teacher SET reset_token = '$pin' WHERE email = :email";
                } elseif($user == 'admin'){
                $query = "UPDATE admin SET reset_token = '$pin' WHERE email = :email";
                }    

                $stmt2 = $pdo->prepare($query);
                $stmt2->execute([
                    'email' => $row['email']
                ]);
                
                // Send the user to the resetpswd page to input the token they have recieved to reset the password.
                $pdo = null;
                header('location: resetpswd.php');
                return 'Email has been sent to the address with a Reset Password Link to input below';

            }
        } else {
            // Shouldn't ever reach here...
            return 'Incorrect username or email inputted';
        }

    }

    // client side validation
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = '';
        $email = '';
        // this while loop runs until a spot for the pin is found as we don't want there to be a case where
        // two people have the same reset token (it is very unlikely that this would even happen but it is just a precaution)
        $pinSpot = false;
        while ($pinSpot == false) {
            // This creates a random string of numbers and letters that's 6 characters long
            $pin = bin2hex(random_bytes(3));
            // We then go through all students, teachers and admins checking whether the $pin has been found or not
            $sql = "SELECT reset_token FROM student WHERE reset_token = :reset_token";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'reset_token' => $pin
            ]);
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $counter = 0;
            while ($row = $stmt->fetch()) {
                $counter++;
            }
            $sql = "SELECT reset_token FROM teacher WHERE reset_token = :reset_token";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'reset_token' => $pin
            ]);
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            while ($row = $stmt->fetch()) {
                $counter++;
            }
            $sql = "SELECT reset_token FROM admin WHERE reset_token = :reset_token";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'reset_token' => $pin
            ]);
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            while ($row = $stmt->fetch()) {
                $counter++;
            }
            // If the pin has not been found anywhere then the pin's spot has been found so break out the while loop.
            if ($counter == 0) {
                $pinSpot = true;
            }
        }
        // Here is where the real validation starts:
        // First we check the case when just the email has been input
        if ($_POST['email'] != '' && $_POST['username'] == '') {
            $email = stripslashes($_POST['email']);

            // First we check if the email is a Student's email
            $sql = "SELECT email FROM student WHERE email = :email";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'email' => $email
            ]);

            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            // This counter will show if the user is a student, teacher or admin
            $counter = 0;
            while ($row = $stmt->fetch()) {
                $counter += 1;
                // Send email to users email about forgotten pswd
                $success = sendMail($row, $pin, 'student');
            }
            // Now if the email was not the student's then the counter would not have changed so check the teacher table
            if($counter == 0){
                // check if the email input is a teacher's personal email or their institute specific email
                // Use LIMIT 1 so that only 1 user appears when we attempt to find a user
                $sql = "SELECT email FROM teacher WHERE email = :email OR institute_email = :email LIMIT 1";

                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    'email' => $email
                ]);
    
                $stmt->setFetchMode(PDO::FETCH_ASSOC);
                while ($row = $stmt->fetch()) {
                    // if the was a teacher then increase the counter
                    $counter += 1;
                    // Send email to users email about forgotten pswd
                    $success = sendMail($row, $pin, 'teacher');
                }    
            }
            if($counter == 0){
                // check if the email input is a admin's email
                $sql = "SELECT email FROM admin WHERE email = :email";
    
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    'email' => $email
                ]);
    
                $stmt->setFetchMode(PDO::FETCH_ASSOC);
                while ($row = $stmt->fetch()) {
                    // if the was a admin then increase the counter
                    $counter += 1;
                    // Send email to users email about forgotten pswd
                    $success = sendMail($row, $pin, 'admin');
                }    

            }
            // If no user found then output incorrect email inputted
            if ($counter == 0) {
                $successful = 'Incorrect email inputted';
            }
        } elseif ($_POST['email'] == '' && $_POST['username'] != '') {
            $username = stripslashes($_POST['username']);
            // Check user is exist in the database
            $sql = "SELECT email FROM student WHERE username = :userID";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'userID' => $username
            ]);

            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $counter = 0;
            while ($row = $stmt->fetch()) {
                $counter += 1;
                // Send email to users email about forgotten pswd
                $success = sendMail($row, $pin, 'student');

            }
            if($counter == 0){
                // check if the teacher's username was input
                $sql = "SELECT email FROM teacher WHERE username = :userID";
    
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    'userID' => $username
                ]);
                $stmt->setFetchMode(PDO::FETCH_ASSOC);
                while ($row = $stmt->fetch()) {
                    $counter += 1;
                    // Send email to users email about forgotten pswd
                    $success = sendMail($row, $pin, 'teacher');
                }
            }
            if($counter == 0){
                // check if the admin's username was input
                $sql = "SELECT email FROM admin WHERE username = :userID";
    
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    'userID' => $username
                ]);
    
                $stmt->setFetchMode(PDO::FETCH_ASSOC);
                while ($row = $stmt->fetch()) {
                    $counter += 1;
                    // Send email to users email about forgotten pswd
                    $success = sendMail($row, $pin, 'admin');
                }    
            }
            // if no user found then output incorrect username input
            if ($counter == 0) {
                $successful = 'Incorrect username inputted';
            }
        // In the case that both the username and email were input
        } elseif ($_POST['email'] != '' && $_POST['username'] != '') {
            $username = stripslashes($_POST['username']);
            $email = stripslashes($_POST['email']);
            // Check user is exist in the database
            $sql = "SELECT email FROM student WHERE username = :userID OR email = :emailID LIMIT 1";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'userID' => $username,
                'emailID' => $email
            ]);

            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $counter = 0;
            while ($row = $stmt->fetch()) {
                $counter += 1;
                // Send email to users email about forgotten pswd
                $success = sendMail($row, $pin, 'student');
            }
            // if student not found try teachers
            if($counter == 0){
                $sql = "SELECT email FROM teacher WHERE username = :userID OR email = :emailID OR institute_email = :emailID LIMIT 1";
    
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    'userID' => $username,
                    'emailID' => $email
                ]);
                $stmt->setFetchMode(PDO::FETCH_ASSOC);
                while ($row = $stmt->fetch()) {
                    $counter += 1;
                    // Send email to users email about forgotten pswd
                    $success = sendMail($row, $pin, 'teacher');
                }    
            }
            if($counter == 0){
                // if its not a teacher or students information then check admins
                $sql = "SELECT email FROM admin WHERE username = :userID OR email = :emailID LIMIT 1";
    
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    'userID' => $username,
                    'emailID' => $email
                ]);
                $stmt->setFetchMode(PDO::FETCH_ASSOC);
                while ($row = $stmt->fetch()) {
                    $counter += 1;
                    // Send email to users email about forgotten pswd
                    $success = sendMail($row, $pin, 'admin');
                }    
            }
            // if no user found then output incorrect email or username inputted
            if ($counter == 0) {
                $successful = 'Incorrect email or username inputted';
            }
        }
    }
    
    ?>
    <!-- Here we have a form which uses the server variable PHP_SELF to do client side validation -->
    <form class="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <!-- Everything is in a container class -->
        <div class="container" style="text-align:center;">
        <!-- another div to create an orange box around everything -->
            <div class="boxy">
                <h1 text-align:>Forgot Password</h1>
                <p><b>Please input your email or username and we will send an email with a token to reset your password</b></p>
                <!-- Here is where the user inputs their username or email -->
                <input type="text" class="texty" name="username" placeholder="Username" autofocus="true" /><br><br>
                <input type="email" class="texty" name="email" placeholder="Email" />
                <!-- Any error messages go here -->
                <span class="error"><br><?php echo $successful; ?></span><br>
                <input type="submit" value="Reset" class="login-button" />
                <p>Remembered your password? <a href="login.php"> Click here</a></p>
                <p>Don't have an account? <a href="registration.php">Register Now</a></p>
                <p>Already been sent a token to put in? <a href="resetpswd.php">Click here</a></p>

            </div>
    </form>

</body>

</html>