<!DOCTYPE html>
<html>

<head>
    <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <style>
        body {
            background-color: lightblue;
        }

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

        .error {
            color: #FF0000;
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
        $pswdErr = $usrErr = $failure = '';
        $username = stripslashes($_POST['username']);    // removes backslashes
        $password = stripslashes($_POST['password']);
        if (strpos($username, ' ') !== false) {
            $usrErr = 'Username should not contain spaces';
        } else {
            if (strlen($username) < 6) {
                $usrErr = "Username too short";
            } elseif (strlen($username) > 14) {
                $usrErr = "Username too long";
            }
        }
        if (strlen($password) < 6) {
            $pswdErr = "Password is too short";
        } elseif (strlen($password) > 15) {
            $pswdErr = "Password is too long";
        }
        if($usrErr == '' && $pswdErr == ''){

        // Check user is exist in the database

        // Select first from teachers then from students??
        $sql = "SELECT * FROM student INNER JOIN school ON student.school_id = school.school_id WHERE student.username = :username";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'username' => $username
        ]);

        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $counter = 0;
        while ($row = $stmt->fetch()) {
            $counter++;
            if ($row['verified'] == 1) {
                if (password_verify($password, $row['password'])) {
                    $yesterday = date('Y-m-d', strtotime(date('Y-m-d') . ' -1 day'));

                    if ($row['last_login'] < $yesterday) {
                        $sql2 = "UPDATE `student` SET `last_login`= :last_login,`login_streak`= 0 WHERE `student_id` = :student_id";

                        $stmt2 = $pdo->prepare($sql2);
                        $stmt2->execute([
                            'last_login' => date('Y-m-d'),
                            'student_id' => $row['student_id']
                        ]);
                    } elseif ($row['last_login'] == $yesterday) {
                        if (intval($row['login_streak']) == 2) {

                            $sql3 = "INSERT IGNORE INTO `student_badge`(`student_id`, `badge_id`) VALUES (:student_id, :badge_id)";

                            $stmt3 = $pdo->prepare($sql3);
                            $stmt3->execute([
                                'student_id' => $row['student_id'],
                                'badge_id' => 3
                            ]);
                        }
                        if (intval($row['login_streak']) == 4) {

                            $sql3 = "INSERT IGNORE INTO `student_badge`(`student_id`, `badge_id`) VALUES (:student_id, :badge_id)";

                            $stmt3 = $pdo->prepare($sql3);
                            $stmt3->execute([
                                'student_id' => $row['student_id'],
                                'badge_id' => 4
                            ]);
                        }
                        if (intval($row['login_streak']) == 9) {

                            $sql3 = "INSERT IGNORE INTO `student_badge`(`student_id`, `badge_id`) VALUES (:student_id, :badge_id)";

                            $stmt3 = $pdo->prepare($sql3);
                            $stmt3->execute([
                                'student_id' => $row['student_id'],
                                'badge_id' => 5
                            ]);
                        }
                        if (intval($row['login_streak']) == 29) {

                            $sql3 = "INSERT IGNORE INTO `student_badge`(`student_id`, `badge_id`) VALUES (:student_id, :badge_id)";

                            $stmt3 = $pdo->prepare($sql3);
                            $stmt3->execute([
                                'student_id' => $row['student_id'],
                                'badge_id' => 6
                            ]);
                        }
                        $sql2 = "UPDATE `student` SET `last_login`= :last_login,`login_streak`= `login_streak` + 1 WHERE `student_id` = :student_id";

                        $stmt2 = $pdo->prepare($sql2);
                        $stmt2->execute([
                            'last_login' => date('Y-m-d'),
                            'student_id' => $row['student_id']
                        ]);
                    }
                    $_SESSION['user'] = $row['username'];
                    $_SESSION['isStaff'] = 'no';
                    $_SESSION['student_id'] = $row['student_id'];
                    $_SESSION['school_id'] = $row['school_id'];
                    $pdo = null;
                    header("location: student/student_homepage.php");
                } else {
                    $pswdErr = 'Either your username or password is incorrect';
                }
            } else {
                $failure = 'School has not yet been verified by the Administrator!';
            }
        }

        $sql = "SELECT teacher_id, username, password, school_id, approval FROM teacher WHERE username = :username";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'username' => $username
        ]);

        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        while ($row = $stmt->fetch()) {
            $counter++;
            if ($row['approval'] == 1) {
                if (password_verify($password, $row['password'])) {
                    $_SESSION['user'] = $row['username'];
                    $_SESSION['teacher_id'] = $row['teacher_id'];
                    $_SESSION['isStaff'] = 'yes';
                    $_SESSION['school_id'] = $row['school_id'];
                    $pdo = null;
                    header("location: teacher/teacher_profile.php");
                } else {
                    $pswdErr = 'Either your username or password is incorrect';
                }
            } else {
                $failure = 'You have not been approved by the administrator yet please confer with them';
            }
        }

        $sql = "SELECT username, password, school_id, verified FROM admin WHERE username = :username";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'username' => $username
        ]);

        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        while ($row = $stmt->fetch()) {
            $counter++;
            if ($row['verified'] == 1) {
                if (password_verify($password, $row['password'])) {
                    $_SESSION['user'] = $row['username'];
                    $_SESSION['isAdmin'] = 'yes';
                    $_SESSION['school_id'] = $row['school_id'];
                    $pdo = null;
                    header("location: admin/admin_homepage.php");
                } else {
                    $pswdErr = 'Either your username or password is incorrect';
                }
            } else {
                $failure = 'You have not inputted your verification code yet.<br>Please input it <a href="verification_page.php">here</a>';
            }
        }

        if ($counter == 0) {
            $pswdErr = 'Either your username or password is incorrect';
        }
    }
    }
    ?>
    <form class="form" name="login" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="container" style="text-align:center;">
            <div class="boxy">
                <h1 class="login-title" text-align:>Login</h1>
                <input type="text" name="username" placeholder="Username" autofocus="true" class="texty" required />
                <span class="error"><p><?php echo $usrErr; ?></p></span>
                <input type="password" name="password" placeholder="Password" class="texty" required />
                <span class="error"><p><?php echo $pswdErr; ?></p></span>
                <div class="row">
                    <div class="col-md-6">
                        <?php
                        if ($failure != ''){
                            echo '<p style="text-align:center;">Forgotten your password? <br><a href="forgotpswd.php"> Click here</a></p>';
                        } else {
                            echo '<p style="text-align:center;">Forgotten your password? <a href="forgotpswd.php"> Click here</a></p>';
                        }
                        ?>
                    </div>
                    <div class="col-md-6">
                        <p style="text-align:center;">Don't have an account? <br><a href="registration.php">Register Now</a></p>
                    </div>
                </div>
                <button type="submit" class="login-button">Login</button>
                <span class="error"><p><?php echo $failure; ?></p></span>
            </div>
        </div>
        </div>
    </form>
</body>

</html>