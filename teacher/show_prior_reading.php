<?php
session_start();
if(!isset($_SESSION['user']) || !isset($_SESSION['school_id']) || !isset($_SESSION['teacher_id'])){
  echo '<script>location.href = "../login.php";</script>';
}

if (isset($_POST['id']) && isset($_POST['type'])) {
    include '../connection.php';
    $type = $_POST['type'];
    if ($type == 'manual'){
        $sql = "SELECT * FROM manual_assignment WHERE manual_assignment_id = :aid";
    } else if ($type == 'quiz'){
        $sql = "SELECT * FROM quiz_assignment WHERE quiz_assignment_id = :aid";
    } else if ($type == 'submission'){
        $sql = "SELECT * FROM manual_submission WHERE manual_submission_id = :aid";
    }
    $assignmentID = $_POST['id'];
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'aid' => $assignmentID
    ]);

    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    while ($row = $stmt->fetch()) {
        $stmt->fetch(PDO::FETCH_BOUND);
        header("Content-type: application/pdf");  
        header('Content-disposition: attachment; filename="'.$row['pdf_name'].'"');
        header('Content-Transfer-Encoding: binary');
        header('Accept-Ranges: bytes');
        echo $row['pdf_doc']; 
    }
    $pdo = null;
} else {
    header('location: ../login.php');
}
?>