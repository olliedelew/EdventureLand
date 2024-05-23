<?php
    session_start();
    if (($_SESSION['isAdmin'] != 'yes') || !isset($_SESSION['user']) || !isset($_SESSION['school_id'])){ //check if user is a user and display buttons
        header('location: ../login.php');
    }
    // Delete everything associated with a teacher
    if(isset($_POST['teacher_id'])){
        include '../connection.php';
        $sql = "SELECT * FROM teacher_student_group WHERE teacher_id = :teacher_id";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'teacher_id' => $_POST['teacher_id']
        ]);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $sg_array = array();
        while($row = $stmt->fetch()){
            array_push($sg_array, $row['student_group_id']);
        }
        foreach ($sg_array as $group_id) {

            $sql = "DELETE FROM Student_student_group WHERE student_group_id = $group_id";    
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $sql = "SELECT * FROM assignment INNER JOIN math_assignment ON math_assignment.math_assignment_id = assignment.math_id WHERE math_assignment.student_group_id = $group_id";
        
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $assignment_ids = array();
            while($row = $stmt->fetch()){
                array_push($assignment_ids, $row['assignment_id']);
            }
        
            foreach ($assignment_ids as $ids) {
                $sql = "DELETE FROM math_submission WHERE assignment_id = $ids";
        
                $stmt = $pdo->prepare($sql);
                $stmt->execute();
            }
        
        
        
            $sql = "SELECT * FROM assignment INNER JOIN manual_assignment ON manual_assignment.manual_assignment_id = assignment.manual_id WHERE manual_assignment.student_group_id = $group_id";
        
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $assignment_ids = array();
            while($row = $stmt->fetch()){
                // $quizID = $row['quizID'];
                array_push($assignment_ids, $row['assignment_id']);
            }
        
            foreach ($assignment_ids as $ids) {
                $sql = "DELETE FROM manual_submission WHERE assignment_id = $ids";
        
                $stmt = $pdo->prepare($sql);
                $stmt->execute();
            }
        
        
            $sql = "SELECT * FROM assignment INNER JOIN quiz_assignment ON quiz_assignment.quiz_assignment_id = assignment.quiz_id WHERE quiz_assignment.student_group_id = $group_id";
        
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $assignment_ids = array();
            while($row = $stmt->fetch()){
                array_push($assignment_ids, $row['assignment_id']);
            }
        
            foreach ($assignment_ids as $ids) {
                $sql = "DELETE FROM quiz_submission WHERE assignment_id = $ids";
                $stmt = $pdo->prepare($sql);
                $stmt->execute();
            }
        
            $sql = "DELETE FROM assignment WHERE student_group_id = $group_id";
        
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
        
        
            $sql = "DELETE FROM math_assignment WHERE student_group_id = $group_id";
        
            $stmt = $pdo->prepare($sql);
            if($stmt->execute() === TRUE){
                echo 'success';
            } else {
                echo 'faile';
            }
        
            $sql = "DELETE FROM manual_assignment WHERE student_group_id = $group_id";
        
            $stmt = $pdo->prepare($sql);
            if($stmt->execute() === TRUE){
                echo 'successssss';
            } else {
                echo 'faileeee';
            }
                
            $sql = "SELECT * FROM quiz_assignment WHERE student_group_id = $group_id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $quizzes = array();
            while($row = $stmt->fetch()){
                // $quizID = $row['quizID'];
                array_push($quizzes, $row['quiz_assignment_id']);
            }
            
            foreach ($quizzes as $ids) {
                $sql = "DELETE FROM questions_and_answers WHERE quiz_assignment_id = $ids";
                $stmt = $pdo->prepare($sql);
                $stmt->execute();
            }
                    
            $sql = "DELETE FROM quiz_assignment WHERE student_group_id = $group_id";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            echo 'here';
            
            
            $sql = "DELETE FROM discussion_board WHERE student_group_id = $group_id";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute();

        
            $sql = "DELETE FROM teacher_student_group WHERE student_group_id = $group_id";

            $stmt = $pdo->prepare($sql);
            $stmt->execute();
    
            
            $sql = "DELETE FROM student_group WHERE student_group_id = $group_id";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
        }

        $query = "DELETE FROM teacher_student WHERE teacher_id = :teacher_id";
            
        $stmt2 = $pdo->prepare($query);
        $stmt2->execute([
            'teacher_id' => $_POST['teacher_id']
        ]);
            
        
        $query = "DELETE FROM teacher WHERE teacher_id = :teacher_id";
            
        $stmt2 = $pdo->prepare($query);
        $stmt2->execute([
            'teacher_id' => $_POST['teacher_id']
        ]);
        $pdo = null;
        header('location: teachers.php');

        // Delete everything associated with a student
    } elseif(isset($_POST['student_id'])){
        include '../connection.php';

        $query = "DELETE FROM math_submission WHERE student_id = :student_id";
            
        $stmt2 = $pdo->prepare($query);
        $stmt2->execute([
            'student_id' => $_POST['student_id']
        ]);

        $query = "DELETE FROM quiz_submission WHERE student_id = :student_id";
            
        $stmt2 = $pdo->prepare($query);
        $stmt2->execute([
            'student_id' => $_POST['student_id']
        ]);
        $query = "DELETE FROM manual_submission WHERE student_id = :student_id";
            
        $stmt2 = $pdo->prepare($query);
        $stmt2->execute([
            'student_id' => $_POST['student_id']
        ]);

        $query = "DELETE FROM leaderboard WHERE student_id = :student_id";
            
        $stmt2 = $pdo->prepare($query);
        $stmt2->execute([
            'student_id' => $_POST['student_id']
        ]);

        $query = "DELETE FROM math_leaderboard WHERE student_id = :student_id";
            
        $stmt2 = $pdo->prepare($query);
        $stmt2->execute([
            'student_id' => $_POST['student_id']
        ]);

        $query = "DELETE FROM Student_student_group WHERE student_id = :student_id";
            
        $stmt2 = $pdo->prepare($query);
        $stmt2->execute([
            'student_id' => $_POST['student_id']
        ]);

        $query = "DELETE FROM teacher_student WHERE student_id = :student_id";
            
        $stmt2 = $pdo->prepare($query);
        $stmt2->execute([
            'student_id' => $_POST['student_id']
        ]);
        $query = "DELETE FROM student_badge WHERE student_id = :student_id";
            
        $stmt2 = $pdo->prepare($query);
        $stmt2->execute([
            'student_id' => $_POST['student_id']
        ]);

        $query = "DELETE FROM discussion_board WHERE student_id = :student_id";
            
        $stmt2 = $pdo->prepare($query);
        $stmt2->execute([
            'student_id' => $_POST['student_id']
        ]);

        $query = "DELETE FROM student WHERE student_id = :student_id";
            
        $stmt2 = $pdo->prepare($query);
        $stmt2->execute([
            'student_id' => $_POST['student_id']
        ]);
        $pdo = null;
        header('location: students.php');
    } else {
        header('location: admin_homepage.php');
    }
?>
