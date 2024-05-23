<?php
session_start();

if(!isset($_SESSION['user']) || !isset($_SESSION['school_id']) || !isset($_SESSION['teacher_id'])){
    echo '<script>location.href = "../login.php";</script>';
}
include '../connection.php';
$types = array('manual', 'math', 'quiz');
if(isset($_POST['type'])){
    if(!in_array($_POST['type'], $types)){
        $pdo = null;
        header('location: assign_game.php');
    } else{
        $assignment_type = $_POST['type'];
    }
}
if(!isset($_POST['group_ID'])){
    $pdo = null;
    header('location: set_assignment.php');
} else {
    $group_ID = $_POST['group_ID'];
    $groupID = $_POST['group_ID'];
}

if ($assignment_type == 'manual') {
    if(!isset($_POST['title']) || !isset($_POST['upload_type']) || !isset($_POST['prior_reading']) || !isset($_POST['time']) || !isset($_POST['date']) || !isset($_POST['points']) || !isset($_POST['desc'])){
        $pdo = null;
        header('location: set_assignment.php');
    }
    $title = $_POST['title'];
    $type = $_POST['upload_type'];
    $time = $_POST['time'];
    $date = $_POST['date'];
    $points = intval($_POST['points']);
    $description = nl2br($_POST['desc']);
    // $description = nl2br($description);
    $teachID = intval($_SESSION['teacher_id']);
    $group_ID = intval($group_ID);

    $text_box = NULL;
    $pdf_blob = NULL;
    $file_name = NULL;
    $link = NULL;
    $ytlink = NULL;
    $prior_reading = $_POST['prior_reading'];
    if ($prior_reading == "none") {
        // Upload none to db
    } else if ($prior_reading == "text_box") {
        $text_box = $_POST['text_box'];
        $text_box = nl2br($text_box);
        // upload text to db
    } else if ($prior_reading == "pdf") {
        // Upload file to db
        if ($_FILES['pdf_file']['error'] != 0) {
            echo 'Something wrong with the file.';
        } else { //pdf file uploaded okay.
            //project_name supplied from the form field
            //attached pdf file information
            $file_name = $_FILES['pdf_file']['name'];
            $file_tmp = $_FILES['pdf_file']['tmp_name'];
            if ($pdf_blob = fopen($file_tmp, "rb")) {
                if ($pdf_blob = fopen($file_tmp, "rb")) {
                    $yes = "yes";
                }
            }
        }

    } else if ($prior_reading == "ytlink") {
        $ytlink = $_POST['youtubeURL'];
    } else if ($prior_reading == "link") {
        $link = $_POST['linkURL'];
    }

    $insert_sql = "INSERT INTO `manual_assignment`(`teacher_id`, `student_group_id`, `title`, `description`, `prior_reading`, `points`, `test_datetime`, `text_box`, `pdf_doc`, `pdf_name`, `ytlink`, `link`, `submission_type`) 
    VALUES(:teacher_id, :student_group_id, :title, :description, :prior_reading, :points, :test_datetime,  :text_box, :pdf_doc, :project_name, :ytlink, :link, :submission_type);";
    $date_for_database = $date . ' ' . $time;

    $new_date_input = date("Y-m-d", strtotime($date) );
    $new_date_input = $new_date_input . ' ' . $time;

    $stmt = $pdo->prepare($insert_sql);

    $stmt->bindParam(':teacher_id', $teachID, PDO::PARAM_INT);
    $stmt->bindParam(':student_group_id', $group_ID, PDO::PARAM_INT);
    $stmt->bindParam(':title', $title, PDO::PARAM_STR);
    $stmt->bindParam(':description', $description, PDO::PARAM_STR);
    $stmt->bindParam(':prior_reading', $prior_reading, PDO::PARAM_STR);
    $stmt->bindParam(':points', $points, PDO::PARAM_INT);
    $stmt->bindParam(':test_datetime', $new_date_input, PDO::PARAM_STR);
    $stmt->bindParam(':text_box', $text_box, PDO::PARAM_STR);
    $stmt->bindParam(':pdf_doc', $pdf_blob, PDO::PARAM_LOB);
    $stmt->bindParam(':project_name', $file_name, PDO::PARAM_STR);
    $stmt->bindParam(':ytlink', $ytlink, PDO::PARAM_STR);
    $stmt->bindParam(':link', $link, PDO::PARAM_STR);
    $stmt->bindParam(':submission_type', $type, PDO::PARAM_STR);
    echo $teachID;
    echo '<br>';
    echo $group_ID;
    echo '<br>';
    echo $title;
    echo '<br>';
    echo $description;
    echo '<br>';
    echo $prior_reading;
    echo '<br>';
    echo $points;
    echo '<br>';
    echo $new_date_input;
    echo '<br>';
    echo $text_box;
    echo '<br>';
    echo $pdf_blob;
    echo '<br>';
    echo $file_name;
    echo '<br>';
    echo $ytlink;
    echo '<br>';
    echo $type;
    echo '<br>';

    if ($stmt->execute() === FALSE) {
        echo 'Could not save information to the database';
        var_dump($pdo->errorInfo());
        $pdo = null;
        header('location: assset.php');    
    } else {
        echo 'Information saved';
        $last_id = $pdo->lastInsertId();
        echo gettype($last_id);
    }
    $last_id = intval($last_id);

    $_POST['actual_assignment_id'] = $last_id;
    $_POST['group_id'] = $group_ID;
    $_POST['assignment_type'] = 'manual';


    $sql = "INSERT INTO `assignment`(`teacher_id`, `student_group_id`, `manual_id`) VALUES (:teacher_id, :student_group_id, :manual_id);";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':teacher_id', $teachID, PDO::PARAM_INT);
    $stmt->bindParam(':student_group_id', $group_ID, PDO::PARAM_INT);
    $stmt->bindParam(':manual_id', $last_id, PDO::PARAM_INT);

    if ($stmt->execute() === FALSE) {
        echo 'Could not save information to the database';
        var_dump($pdo->errorInfo());
        $pdo = null;
        header('location: assset.php');    
    } else {
        echo 'Information saved';
        $last_id = $pdo->lastInsertId();
        $last_id = intval($last_id);
    }
    $_POST['assignment_id'] = $last_id;

    $sql = "SELECT * FROM Student_student_group WHERE student_group_id = :student_group_id"; 
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'student_group_id' => $group_ID
    ]);
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $ids = [];
    while ($row = $stmt->fetch()) {
        array_push($ids, $row['student_id']);
    }

    $sql = "INSERT INTO `manual_submission`(`assignment_id`, `student_id`, `assignment_done`, `result`, `pdf_doc`, `pdf_name`, `text_box`) VALUES (:assignment_id, :student_id, :assignment_done, :result, NULL, NULL, NULL);";
    $stmt = $pdo->prepare($sql);

    $done = 0;

    foreach ($ids as $id) {
        echo gettype($last_id);
        $stmt->execute([
            'assignment_id' => $last_id,
            'student_id' => $id,
            'assignment_done' => $done,
            'result' => NULL
        ]);    
    }

    $_SESSION['POST'] = $_POST;
    $pdo = null;
    header('location: specific_assignment.php');

} else if ($assignment_type == 'math') {
    if(!isset($_POST['title']) || !isset($_POST['type']) || !isset($_POST['difficulty']) || !isset($_POST['mincorrect']) || !isset($_POST['check_list']) || !isset($_POST['pass']) || !isset($_POST['timer']) || !isset($_POST['time']) || !isset($_POST['date']) || !isset($_POST['points']) || !isset($_POST['desc'])){
        $pdo = null;
        header('location: set_assignment.php');
    }

    $title = $_POST['title'];
    $type = $_POST['type'];
    $time = $_POST['time'];
    $date = $_POST['date'];
    $points = intval($_POST['points']);
    $description = $_POST['desc'];
    $description = nl2br($description);
    $difficulty = $_POST['difficulty'];
    $min_no_questions = intval($_POST['mincorrect']);
    $checked_list = json_encode($_POST['check_list']);
    $pass_percentage = intval($_POST['pass']);
    $timer = intval($_POST['timer']);

    $teachID = intval($_SESSION['teacher_id']);
    $group_ID = intval($group_ID);
    $date_for_database = $date . ' ' . $time;

    $insert_sql = "INSERT INTO `math_assignment`(`teacher_id`, `student_group_id`, `title`, `description`, `points`, `test_datetime`, `operators`, `difficulty`, `duration`, `pass_percentage`, `min_no_questions`) 
    VALUES (:teacher_id, :student_group_id, :title, :description, :points, :test_datetime, :operators, :difficulty, :duration, :pass, :min_no_questions)";

    $stmt = $pdo->prepare($insert_sql);

    echo gettype($checked_list);
    
    $new_date_input = date("Y-m-d", strtotime($date) );
    $new_date_input = $new_date_input . ' ' . $time;

    $stmt->bindParam(':teacher_id', $teachID, PDO::PARAM_INT);
    $stmt->bindParam(':student_group_id', $group_ID, PDO::PARAM_INT);
    $stmt->bindParam(':title', $title, PDO::PARAM_STR);
    $stmt->bindParam(':description', $description, PDO::PARAM_STR);
    $stmt->bindParam(':points', $points, PDO::PARAM_INT);
    $stmt->bindParam(':test_datetime', $new_date_input, PDO::PARAM_STR);
    $stmt->bindParam(':operators', $checked_list, PDO::PARAM_STR);
    $stmt->bindParam(':difficulty', $difficulty, PDO::PARAM_STR);
    $stmt->bindParam(':duration', $timer, PDO::PARAM_INT);
    $stmt->bindParam(':pass', $pass_percentage, PDO::PARAM_INT);
    $stmt->bindParam(':min_no_questions', $min_no_questions, PDO::PARAM_INT);
    
    if ($stmt->execute() === FALSE) {
        echo 'Could not save information to the database';
        var_dump($pdo->errorInfo());
        $pdo = null;
        header('location: assset.php');    
    } else {
        echo 'Information saved';
        $last_id = $pdo->lastInsertId();
        echo gettype($last_id);
    }
    $last_id = intval($last_id);

    $_POST['actual_assignment_id'] = $last_id;
    $_POST['group_id'] = $group_ID;
    $_POST['assignment_type'] = 'math';

    $sql = "INSERT INTO `assignment`(`teacher_id`, `student_group_id`, `math_id`) VALUES (:teacher_id, :student_group_id, :math_id);";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':teacher_id', $teachID, PDO::PARAM_INT);
    $stmt->bindParam(':student_group_id', $group_ID, PDO::PARAM_INT);
    $stmt->bindParam(':math_id', $last_id, PDO::PARAM_INT);

    if ($stmt->execute() === FALSE) {
        echo 'Could not save information to the database';
        var_dump($pdo->errorInfo());
        $pdo = null;
        header('location: assset.php');
    } else {
        echo 'Information saved';
        $last_id = $pdo->lastInsertId();
    }

    $_POST['assignment_id'] = $last_id;

    $sql = "SELECT * FROM Student_student_group WHERE student_group_id = :student_group_id"; 

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'student_group_id' => $group_ID
    ]);
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $ids = [];
    while ($row = $stmt->fetch()) {
        array_push($ids, $row['student_id']);
    }

    $sql = "INSERT INTO `math_submission`(`assignment_id`, `student_id`) 
    VALUES (:assignment_id, :student_id)";
    $stmt = $pdo->prepare($sql);

    $done = 0;

    foreach ($ids as $id) {
        $stmt->execute([
            'assignment_id' => $last_id,
            'student_id' => $id
        ]);

    }

    $_SESSION['POST'] = $_POST;
    $pdo = null;
    header('location: specific_assignment.php');

} else if ($assignment_type == 'quiz') {
    if(!isset($_POST['quizName']) || !isset($_POST['type']) || !isset($_POST['shuffle']) || !isset($_POST['pass']) || !isset($_POST['time']) || !isset($_POST['date']) || !isset($_POST['points']) || !isset($_POST['prior_reading'])){
        $pdo = null;
        header('location: set_assignment.php');
    }
    $title = $_POST['quizName'];
    $type = $assignment_type;
    $time = $_POST['time'];
    $date = $_POST['date'];
    $points = intval($_POST['points']);
    $prior_reading = $_POST['prior_reading'];
    $text_box = NULL;
    $description = NULL;
    if(isset($_POST['description'])){
        $description = $_POST['description'];
        $description = nl2br($description);
    }
    $pdf_blob = NULL;
    $file_name = NULL;
    $link = NULL;
    $ytlink = NULL;
    if ($prior_reading == "none") {
        // Upload none to db
    } else if ($prior_reading == "text_box") {
        $text_box = $_POST['text_box'];
        $text_box = nl2br($text_box);
        // upload text to db
    } else if ($prior_reading == "pdf") {
        // Upload file to db
        if ($_FILES['pdf_file']['error'] != 0) {
            echo 'Something wrong with the file.';
        } else { //pdf file uploaded okay.
            //project_name supplied from the form field
            //attached pdf file information
            $file_name = $_FILES['pdf_file']['name'];
            $file_tmp = $_FILES['pdf_file']['tmp_name'];
            if ($pdf_blob = fopen($file_tmp, "rb")) {
                if ($pdf_blob = fopen($file_tmp, "rb")) {
                    $yes = "yes";
                }
            }
        }
    } else if ($prior_reading == "ytlink") {
        $ytlink = $_POST['youtubeURL'];
    } else if ($prior_reading == "link") {
        $link = $_POST['linkURL'];
    }
    $shuffle = $_POST['shuffle'];
    if ($shuffle == "yes") {
        $shuffle = 1;
    } else {
        $shuffle = 0;
    }
    if(isset($_POST['check_list'])){
        $checked_list = json_encode($_POST['check_list']);
    } else {
        $checked_list = '["NULL"]';
    }
    echo $checked_list;


    $numofquestions = $_POST['numofquestions'];
    $question = $_POST['hiddenquestion'];
    $answer1 = $_POST['hiddenanswer1'];
    $answer2 = $_POST['hiddenanswer2'];
    $answer3 = $_POST['hiddenanswer3'];
    $answer4 = $_POST['hiddenanswer4'];
    $correctanswer = $_POST['hiddencorrectanswer'];
    $hint = $_POST['hiddenhint'];
    $times = $_POST['hiddentimes'];
    $quizname = $_POST['quizName'];

    $teachID = intval($_SESSION['teacher_id']);
    $group_ID = intval($group_ID);

    $insert_sql = "INSERT INTO `quiz_assignment`(`teacher_id`, `student_group_id`, `title`, `description`, `prior_reading`, `points`, `test_datetime`, `text_box`, `pdf_doc`, `pdf_name`, `ytlink`, `link`, `lifelines`, `shuffle`, `pass_percentage`) 
    VALUES(:teacher_id, :student_group_id, :title, :description, :prior_reading, :points, :test_datetime, :text_box, :pdf_doc, :project_name, :ytlink, :link, :lifelines, :shuffle, :pass_percentage);";
    
    $date_for_database = $date . ' ' . $time;
    
    $new_date_input = date("Y-m-d", strtotime($date) );
    $new_date_input = $new_date_input . ' ' . $time;

    $stmt = $pdo->prepare($insert_sql);

    $pass_percentage = intval($_POST['pass']);

    $stmt->bindParam(':teacher_id', $teachID, PDO::PARAM_INT);
    $stmt->bindParam(':student_group_id', $group_ID, PDO::PARAM_INT);
    $stmt->bindParam(':title', $title, PDO::PARAM_STR);
    $stmt->bindParam(':description', $description, PDO::PARAM_STR);
    $stmt->bindParam(':prior_reading', $prior_reading, PDO::PARAM_STR);
    $stmt->bindParam(':points', $points, PDO::PARAM_INT);
    $stmt->bindParam(':test_datetime', $new_date_input, PDO::PARAM_STR);
    $stmt->bindParam(':text_box', $text_box, PDO::PARAM_STR);
    $stmt->bindParam(':pdf_doc', $pdf_blob, PDO::PARAM_LOB);
    $stmt->bindParam(':project_name', $file_name, PDO::PARAM_STR);
    $stmt->bindParam(':ytlink', $ytlink, PDO::PARAM_STR);
    $stmt->bindParam(':link', $link, PDO::PARAM_STR);
    $stmt->bindParam(':lifelines', $checked_list, PDO::PARAM_STR);
    $stmt->bindParam(':shuffle', $shuffle, PDO::PARAM_INT);
    $stmt->bindParam(':pass_percentage', $pass_percentage, PDO::PARAM_INT);
    
    if ($stmt->execute() === FALSE) {
        echo 'Could not save information to the database';
        var_dump($pdo->errorInfo());
        $pdo = null;
        header('location: assset.php');
    } else {
        echo 'Information saved';
        $quiz_id = $pdo->lastInsertId();
    }
    $quiz_id = intval($quiz_id);
    

    $sql = "INSERT INTO `assignment`(`teacher_id`, `student_group_id`, `quiz_id`) VALUES (:teacher_id, :student_group_id, :quiz_id);";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':teacher_id', $teachID, PDO::PARAM_INT);
    $stmt->bindParam(':student_group_id', $group_ID, PDO::PARAM_INT);
    $stmt->bindParam(':quiz_id', $quiz_id, PDO::PARAM_INT);

    if ($stmt->execute() === FALSE) {
        echo 'Could not save information to the database';
        var_dump($pdo->errorInfo());
        $pdo = null;
        header('location: assset.php');
    } else {
        echo 'Information saved';
        $last_id = $pdo->lastInsertId();
        $last_id = intval($last_id);
    }
    $_POST['actual_assignment_id'] = $quiz_id;
    $_POST['group_id'] = $group_ID;
    $_POST['assignment_type'] = 'quiz';
    $_POST['assignment_id'] = $last_id;

    $sql = "INSERT INTO questions_and_answers(quiz_assignment_id, question, answer1, answer2, answer3, answer4, correctanswer, hint, time_per_question)
                        VALUES (:quiz_assignment_id, :question, :answer1, :answer2, :answer3, :answer4, :correctanswer, :hint, :time_per_question)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'quiz_assignment_id' => $quiz_id,
            'question' => $question,
            'answer1' => $answer1,
            'answer2' => $answer2,
            'answer3' => $answer3,
            'answer4' => $answer4,
            'correctanswer' => $correctanswer,
            'hint' => $hint,
            'time_per_question' => $times
        ]);


    $sql = "SELECT * FROM Student_student_group WHERE student_group_id = :student_group_id";
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'student_group_id' => $group_ID
    ]);
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $ids = [];
    while ($row = $stmt->fetch()) {
        array_push($ids, $row['student_id']);
    }


    $sql = "INSERT INTO `quiz_submission`(`assignment_id`, `student_id`, `assignment_done`, `result`, `lifelines_used`, `attempts`, `questions_right`, `questions_total`)
     VALUES (:assignment_id, :student_id, :assignment_done,:result, :lifelines_used,:attempts,:questions_right,:questions_total)";
    $stmt = $pdo->prepare($sql);

    $done = 0;

    foreach ($ids as $id) {
        $stmt->execute([
            'assignment_id' => $last_id,
            'student_id' => $id,
            'assignment_done' => $done,
            'result' => NULL,
            'lifelines_used' => NULL,
            'attempts' => 0,
            'questions_right' => NULL,
            'questions_total' => NULL
        ]);    
    }

    $_SESSION['POST'] = $_POST;
    $pdo = null;
    header('location: specific_assignment.php');

}
?>