<?php
session_start();
if(!isset($_SESSION['user']) || !isset($_SESSION['school_id']) || !isset($_SESSION['student_id'])){
    echo '<script>location.href = "../login.php";</script>';
}
if(!isset($_POST['assignment_type']) || !isset($_POST['assignment_id']) || (!isset($_POST['text_box']) && !isset($_FILES['pdf_file']['name']))){
    header('location: student_assignments.php');
}
include '../connection.php';

$assignment_type = $_POST['assignment_type'];
$text = NULL;
$pdf_blob = NULL;
$pdf_name = NULL;
$assignment_id = intval($_POST['assignment_id']);
$student_id = intval($_SESSION['student_id']);
$text_box = NULL;
$pdf_blob = NULL;
$file_name = NULL;

if ($assignment_type == 'text') {

    $text_box = $_POST['text_box'];
    $text_box = nl2br($text_box);
        $sql =  "UPDATE `manual_submission` SET `assignment_done`= 1, `text_box`=:text_box WHERE `assignment_id` = $assignment_id AND `student_id` = $student_id;";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'text_box' => $text_box
    ]);
    $pdo = null;
    header('location: student_assignments.php');

} else if ($assignment_type == 'none') {
            $sql =  "UPDATE `manual_submission` SET `assignment_done`= 1 WHERE `assignment_id` = $assignment_id AND `student_id` = $student_id;";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $pdo = null;
    header('location: student_assignments.php');

} else if ($assignment_type == 'pdf') {
    if ($_FILES['pdf_file']['error'] != 0) {
        echo 'Something wrong with the file.';
        $pdo = null;
        header('location: student_assignments.php');
    } else { 
        $file_name = $_FILES['pdf_file']['name'];
        $file_tmp = $_FILES['pdf_file']['tmp_name'];
        if ($pdf_blob = fopen($file_tmp, "rb")) {
            if ($pdf_blob = fopen($file_tmp, "rb")) {
                try {
                    $sql = "UPDATE `manual_submission` SET  `assignment_done` = :assignment_done, `pdf_doc` = :pdf_doc, `pdf_name` = :pdf_name WHERE `assignment_id` = :assignment_id AND `student_id` = :student_id;";
                    $stmt = $pdo->prepare($sql);
                    $one = 1;
                    $stmt->bindParam(':assignment_done', $one, PDO::PARAM_INT);
                    $stmt->bindParam(':pdf_doc', $pdf_blob, PDO::PARAM_LOB);
                    $stmt->bindParam(':pdf_name', $file_name, PDO::PARAM_STR);
                    $stmt->bindParam(':assignment_id', $assignment_id, PDO::PARAM_INT);
                    $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
                    if ($stmt->execute() === FALSE) {
                        $pdo = null;
                        header('location: student_assignments.php');
                    } else {
                        echo 'Information saved';
                        $pdo = null;
                        header('location: student_assignments.php');
                    }
                } catch (PDOException $e) {
                    echo 'Database Error ' . $e->getMessage() . ' in ' . $e->getFile() .
                        ': ' . $e->getLine();
                    $pdo = null;
                    header('location: student_assignments.php');
        
                }
                $pdo = null;
                header('location: student_assignments.php');
            } else {
                echo 'Could not open the attached pdf file';
                $pdo = null;
                header('location: student_assignments.php');
            }
        }
        $pdo = null;
        header('location: student_assignments.php');
    }
} else {
    $pdo = null;
    header('location: student_assignments.php');
}


?>