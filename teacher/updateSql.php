
	<?php

		session_start();
		if(!isset($_SESSION['user']) || !isset($_SESSION['school_id']) || !isset($_SESSION['teacher_id'])){
		  echo '<script>location.href = "../login.php";</script>';
		}
		if(!isset($_POST['type'])){
			header('location: assset.php');
		}
		include '../connection.php';
		$type = $_POST['type'];
		if($type == 'quiz'){

			if(!isset($_POST['quizID']) || !isset($_POST['q']) || !isset($_POST['a1']) || !isset($_POST['a2']) || !isset($_POST['a3']) || 
			!isset($_POST['a4']) || !isset($_POST['c']) || !isset($_POST['hints']) || !isset($_POST['qid']) || !isset($_POST['desc']) ||
			!isset($_POST['title']) || !isset($_POST['time']) || !isset($_POST['times']) 
			|| !isset($_POST['date']) || !isset($_POST['points']) || !isset($_POST['prior_reading']) || !isset($_POST['shuffle'])){
				$pdo = null;
				header('location: assset.php');
			}

			$id = $_POST['quizID'];
		$questions = json_decode($_POST['q']);
        $answer1 = json_decode($_POST['a1']);
        $answer2 = json_decode($_POST['a2']);
        $answer3 = json_decode($_POST['a3']);
        $answer4 = json_decode($_POST['a4']);
        $correctanswer = json_decode($_POST['c']);
        $hints = json_decode($_POST['hints']);
        $times = json_decode($_POST['times']);
		$qid = intval($_POST['qid']);
		$description = NULL;
		if(isset($_POST['desc'])){
			$description = $_POST['desc'];
			$description = nl2br($description);
		}
		$title = $_POST['title'];
		$time = $_POST['time'];
		$date = $_POST['date'];
		$points = intval($_POST['points']);
		$prior_reading = $_POST['prior_reading'];
		$text_box = NULL;
		$pdf_blob = NULL;
		$file_name = NULL;
		$link = NULL;
		$ytlink = NULL;

		if ($prior_reading == "none") {
			// Upload none to db
		} else if ($prior_reading == "text_box" || $prior_reading == "hm_box") {
			$prior_reading = "text_box";
			$text_box = $_POST['text_box'];
			$text_box = nl2br($text_box);
			// upload text to db
		} else if ($prior_reading == "pdf") {
			// Upload file to db
			if ($_FILES['pdf_file']['error'] != 0) {
				echo 'Something wrong with the file.';
				$pdo = null;
				header('location: assset.php');	
			} else { //pdf file uploaded okay.
				//project_name supplied from the form field
				//attached pdf file information
				$file_name = $_FILES['pdf_file']['name'];
				$file_tmp = $_FILES['pdf_file']['tmp_name'];
				if ($pdf_blob = fopen($file_tmp, "rb")) {
						$yes = "yes";
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

		  $update_sql = "UPDATE `quiz_assignment` SET `title`=:title, `description`=:description, `prior_reading`=:prior_reading, `points`=:points, `test_datetime`=:test_datetime, `text_box`=:text_box, `pdf_doc`=:pdf_doc, `pdf_name`=:pdf_name, `ytlink`=:ytlink, `link`=:link, `lifelines`=:lifelines, `shuffle`=:shuffle  WHERE quiz_assignment_id=:quiz_assignment_id;";
		$new_date_input = date("Y-m-d", strtotime($date) );
		$new_date_input = $new_date_input . ' ' . $time;
		  $stmt = $pdo->prepare($update_sql);
		  $stmt->bindParam(':title', $title, PDO::PARAM_STR);
		  $stmt->bindParam(':description', $description, PDO::PARAM_STR);
		  $stmt->bindParam(':prior_reading', $prior_reading, PDO::PARAM_STR);
		  $stmt->bindParam(':points', $points, PDO::PARAM_INT);
		  $stmt->bindParam(':test_datetime', $new_date_input, PDO::PARAM_STR);
		  $stmt->bindParam(':text_box', $text_box, PDO::PARAM_STR);
		  $stmt->bindParam(':pdf_doc', $pdf_blob, PDO::PARAM_LOB);
		  $stmt->bindParam(':pdf_name', $file_name, PDO::PARAM_STR);
		  $stmt->bindParam(':ytlink', $ytlink, PDO::PARAM_STR);
		  $stmt->bindParam(':link', $link, PDO::PARAM_STR);
		  $stmt->bindParam(':lifelines', $checked_list, PDO::PARAM_STR);
		  $stmt->bindParam(':shuffle', $shuffle, PDO::PARAM_INT);
		  $stmt->bindParam(':quiz_assignment_id', $id, PDO::PARAM_INT);

		  if ($stmt->execute() === FALSE) {
			$pdo = null;
			header('location: assset.php');
		  } else {
			echo 'Information saved';
		  }

  
		$update_sql = "UPDATE `questions_and_answers` SET `question`=:question, `answer1`=:answer1, `answer2`=:answer2, `answer3`=:answer3, `answer4`=:answer4, `correctanswer`=:correctanswer, `hint`=:hint, `time_per_question`=:time_per_question  WHERE questionID=:questionID;";

		$stmt = $pdo->prepare($update_sql);
   
		$stmt->bindParam(':question', json_encode($questions), PDO::PARAM_STR);
		$stmt->bindParam(':answer1', json_encode($answer1), PDO::PARAM_STR);
		$stmt->bindParam(':answer2', json_encode($answer2), PDO::PARAM_STR);
		$stmt->bindParam(':answer3', json_encode($answer3), PDO::PARAM_STR);
		$stmt->bindParam(':answer4', json_encode($answer4), PDO::PARAM_STR);
		$stmt->bindParam(':correctanswer', json_encode($correctanswer), PDO::PARAM_STR);
		$stmt->bindParam(':hint', json_encode($hints), PDO::PARAM_STR);
		$stmt->bindParam(':time_per_question', json_encode($times), PDO::PARAM_STR);
		$stmt->bindParam(':questionID', $qid, PDO::PARAM_INT);
		if ($stmt->execute() === FALSE) {
			$pdo = null;
			header('location: assset.php');
		} else {
			echo 'Information saved';
			$pdo = null;
			header("location: specific_assignment.php?".$_POST['url']);
		}
	} elseif($type == 'math'){
		print_r($_POST);
		if(!isset($_POST['math_id']) || !isset($_POST['desc']) || !isset($_POST['title']) || !isset($_POST['timer']) || !isset($_POST['check_list']) 
		|| !isset($_POST['pass']) || !isset($_POST['time']) || !isset($_POST['date']) || !isset($_POST['points']) || !isset($_POST['mincorrect']) || !isset($_POST['difficulty'])){
			$pdo = null;
			header('location: assset.php');
		}

		$id = intval($_POST['math_id']);
		$title = $_POST['title'];
		$description = nl2br($_POST['desc']);
		$points = intval($_POST['points']);
		$date = $_POST['date'];
		$time = $_POST['time'];
		$check_list = json_encode($_POST['check_list']);
		$difficulty = $_POST['difficulty'];
		$timer = intval($_POST['timer']);
		$pass = intval($_POST['pass']);
		$min_no_correct = intval($_POST['mincorrect']);

		$sql = "UPDATE math_assignment SET title=:title, description=:description, points=:points, test_datetime=:test_datetime, 
		operators=:operators, difficulty=:difficulty, duration=:duration, pass_percentage=:pass_percentage, min_no_questions=:min_no_questions  
		WHERE math_assignment_id=:math_assignment_id";
		$new_date_input = date("Y-m-d", strtotime($date) );
		$new_date_input = $new_date_input . ' ' . $time;
	
		$stmt = $pdo->prepare($sql);
		$stmt->execute([
			'title' => $title,
			'description' => $description,
			'points' => $points,
			'test_datetime' => $new_date_input,
			'operators' => $check_list,
			'difficulty' => $difficulty,
			'duration' => $timer,
			'pass_percentage' => $pass,
			'min_no_questions' => $min_no_correct,
			'math_assignment_id' => $id
		]);
		$pdo = null;
		header("location: specific_assignment.php?");
	} elseif($type == 'manual'){
		if(!isset($_POST['id']) || !isset($_POST['submission_type']) || !isset($_POST['title']) || !isset($_POST['prior_reading']) 
		|| !isset($_POST['time']) || !isset($_POST['date']) || !isset($_POST['points'])){
			$pdo = null;
			header('location: assset.php');
		}
		$id = $_POST['id'];

		$title = $_POST['title'];
		$type = $_POST['submission_type'];
		$time = $_POST['time'];
		$date = $_POST['date'];
		$points = intval($_POST['points']);
		$description = nl2br($_POST['desc']);
		$teachID = intval($_SESSION['teacher_id']);
		$text_box = NULL;
		$pdf_blob = NULL;
		$file_name = NULL;
		$link = NULL;
		$ytlink = NULL;

		$prior_reading = $_POST['prior_reading'];
		if ($prior_reading == "none") {
			// Upload none to db
		} else if ($prior_reading == "text_box" || $prior_reading == "hm_box") {
			$prior_reading = "text_box";
			$text_box = $_POST['text_box'];
			$text_box = nl2br($text_box);
			// upload text to db
		} else if ($prior_reading == "pdf") {
			// Upload file to db
			if ($_FILES['pdf_file']['error'] != 0) {
				echo 'Something wrong with the file.';
				$pdo = null;
				header('location: assset.php');	
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
			// USE GOOGLE API HERE TO CHECK IF LINK IS VALID OR NOT.
		} else if ($prior_reading == "link") {
			$link = $_POST['linkURL'];
		}
	
		$insert_sql = "UPDATE `manual_assignment` SET `title`=:title, `description`=:description, `prior_reading`=:prior_reading, `points`=:points, `test_datetime`=:test_datetime, `text_box`=:text_box, `pdf_doc`=:pdf_doc, `pdf_name`=:pdf_name, `ytlink`=:ytlink, `link`=:link, `submission_type`=:submission_type WHERE manual_assignment_id=:manual_assignment_id;";
		$date_for_database = $date . ' ' . $time;

		$new_date_input = date("Y-m-d", strtotime($date) );
		$new_date_input = $new_date_input . ' ' . $time;

	
		$stmt = $pdo->prepare($insert_sql);
 
		$stmt->bindParam(':title', $title, PDO::PARAM_STR);
		$stmt->bindParam(':description', $description, PDO::PARAM_STR);
		$stmt->bindParam(':prior_reading', $prior_reading, PDO::PARAM_STR);
		$stmt->bindParam(':points', $points, PDO::PARAM_INT);
		$stmt->bindParam(':test_datetime', $new_date_input, PDO::PARAM_STR);
		$stmt->bindParam(':text_box', $text_box, PDO::PARAM_STR);
		$stmt->bindParam(':pdf_doc', $pdf_blob, PDO::PARAM_LOB);
		$stmt->bindParam(':pdf_name', $file_name, PDO::PARAM_STR);
		$stmt->bindParam(':ytlink', $ytlink, PDO::PARAM_STR);
		$stmt->bindParam(':link', $link, PDO::PARAM_STR);
		$stmt->bindParam(':submission_type', $type, PDO::PARAM_STR);
		$stmt->bindParam(':manual_assignment_id', $id, PDO::PARAM_STR);

		if ($stmt->execute() === FALSE) {
			echo 'Could not save information to the database';
			var_dump($pdo->errorInfo());
			$pdo = null;
			header('location: assset.php');
		} else {
			echo 'Information saved';
			$pdo = null;
			header("location: specific_assignment.php?".$_POST['url']);
		}
		
	} else {
		$pdo = null;
		header("location: specific_assignment.php?".$_POST['url']);
	}

?>