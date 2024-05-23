    <?php
session_start();
if(!isset($_SESSION['user']) || !isset($_SESSION['school_id']) || (!isset($_SESSION['student_id']) && !isset($_SESSION['teacher_id']))){
    echo '<script>location.href = "../login.php";</script>';
}
date_default_timezone_set('Europe/London');
if(!isset($_POST['body']) || !isset($_POST['title'])){
    header('db_grouper.php');
}
include '../connection.php';

if(isset($_POST['discussion_board_id'])){
    $reply_id = $_POST['discussion_board_id'];
    // }
}else {
    $reply_id = NULL;
}
$anon = 0;
if($_SESSION['isStaff'] == 'no'){
    if(isset($_POST['anon'])){
        $anon = 1;
    } else {
        $anon = 0;
    }
    $student_or_teacher = 'student';
    $author_id = $_SESSION['student_id'];
} else {
    $student_or_teacher = 'teacher';
    $author_id = $_SESSION['teacher_id'];
}
$body = $_POST['body'];
$body = nl2br($body);
$title = $_POST['title'];
$sql = "INSERT INTO `discussion_board`(`title`, `body`, `student_id`, `teacher_id`, `datetime`, `reply_id`, `student_group_id`, `anonymous`)
 VALUES (:title, :body, :student_id, :teacher_id, :datetime, :reply_id, :student_group_id, :anonymous)";

$stmt = $pdo->prepare($sql);
if($student_or_teacher == 'teacher'){
$stmt->execute([
    'title' => $title,
    'body' => $body,
    'student_id' => NULL,
    'teacher_id' => $author_id,
    'datetime' => date("Y-m-d H:i:s"),
    'reply_id' => $reply_id,
    'student_group_id' => $_POST['group_id'],
    'anonymous' => $anon
]);
$last_id = $pdo->lastInsertId();
} else {
    $stmt->execute([
        'title' => $title,
        'body' => $body,
        'student_id' => $author_id,
        'teacher_id' => NULL,
        'datetime' => date("Y-m-d H:i:s"),
        'reply_id' => $reply_id,
        'student_group_id' => $_POST['group_id'],
        'anonymous' => $anon
    ]); 
    $last_id = $pdo->lastInsertId();
}

    if(!isset($_POST['discussion_board_id'])){
        $_POST['discussion_board_id'] = $last_id;
        $_POST['discussion_board'] = $last_id;
    }
    $_SESSION['POST'] = $_POST;
    $pdo = null;
    header('location: db_grouper_discussion.php');

?>