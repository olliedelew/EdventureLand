<?php
session_start();
if (!isset($_SESSION['user']) || !isset($_SESSION['school_id']) || !isset($_SESSION['student_id'])) {
    echo '<script>location.href = "../login.php";</script>';
}
include '../connection.php';

function ssm_table($difficulty, $pdo)
{
    $sql = "SELECT *, LENGTH(JSON_EXTRACT(leaderboard.lifelines_used, '$.key')) as json_length FROM leaderboard INNER JOIN student ON student.student_id = leaderboard.student_id WHERE difficulty = :difficulty AND school_id = :school_id ORDER BY points DESC, json_length ASC LIMIT 5";
    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        'difficulty' => $difficulty,
        'school_id' => $_SESSION['school_id']
    ]);
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $counter = 0;
    echo '
        <h1>TOP 5 ' . strtoupper($difficulty) . '</h1>
        <table class="table table-bordered">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Name</th>
            <th scope="col">Year Group</th>
            <th scope="col">Number of questions right</th>
            <th scope="col">Lifelines used</th>
            <th scope="col">Topic</th>
            <th scope="col">Points</th>
        </tr>
        </thead>
        <tbody>';

    $first_relevant_sid_found = false;
    while ($row = $stmt->fetch()) {
        $counter++;
        if (json_decode($row['lifelines_used'])[0] == 'none') {
            $count = 0;
            $lifelines = json_decode($row['lifelines_used']);
            $lifelines_used = 'None used';
        } else {
            $lifelines = json_decode($row['lifelines_used']);
            $count = count($lifelines);
            for ($i = 0; $i < count($lifelines); $i++) {
                if ($lifelines[$i] == 'fiftyfifty') {
                    $lifelines[$i] = '50/50';
                } else if ($lifelines[$i] == 'check') {
                    $lifelines[$i] = 'Check Answer';
                } else if ($lifelines[$i] == 'hint') {
                    $lifelines[$i] = 'Hint';
                }
            }
            $lifelines_used = join(", ", $lifelines);
        }
        // All easys compete against eachother, all hards compete against each other etc..                                            
        if ($_SESSION['student_id'] == $row['student_id'] && $first_relevant_sid_found == false) {
            $first_relevant_sid_found = true;
            echo '
            <tr class="newest">
              <td>' . $counter . '</td>
              <td style="text-align:left;">' . '<img src="profile_pictures/' . $row['profile_picture'] . '" style="width:40px; height:40px;" alt="Profile Picture">' . ' ' . $row['name'] . '</td>
              <td>Year ' . $row['student_year'] . '</td>
              <td>' . $row['questions_right'] . ' / 12' . '</td>
              <td>' . $count . ' : ' . $lifelines_used . '</td>
              <td>' . $row['topic'] . '</td>
              <td>' . $row['points'] . '</td>
              </tr>';
        } else {
            echo '
            <tr>
              <td>' . $counter . '</td>
              <td style="text-align:left;">' . '<img src="profile_pictures/' . $row['profile_picture'] . '" style="width:40px; height:40px;" alt="Profile Picture">' . ' ' . $row['name'] . '</td>
              <td>Year ' . $row['student_year'] . '</td>
              <td>' . $row['questions_right'] . ' / 12' . '</td>
              <td>' . $count . ' : ' . $lifelines_used . '</td>
              <td>' . $row['topic'] . '</td>
              <td>' . $row['points'] . '</td>
              </tr>';
        }
    }
    echo '</tbody>
        </table>
        ';
}
function math_table($difficulty, $pdo)
{
    $sql = "SELECT *, LENGTH(JSON_EXTRACT(math_leaderboard.operators, '$.key')) as json_length FROM math_leaderboard INNER JOIN student ON student.student_id = math_leaderboard.student_id WHERE difficulty = :difficulty AND school_id = :school_id ORDER BY points DESC, json_length DESC, questions_right DESC, questions_wrong ASC LIMIT 5";
    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        'difficulty' => $difficulty,
        'school_id' => $_SESSION['school_id']
    ]);
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $counter = 0;
    echo '
    <h1>TOP 5 ' . strtoupper($difficulty) . '</h1>
    <table class="table table-bordered">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Name</th>
        <th scope="col">Year Group</th>
        <th scope="col">Correct Answers</th>
        <th scope="col">Incorrect Answers</th>
        <th scope="col">Operators used</th>
        <th scope="col">Total Questions Attempted</th>
        <th scope="col">Points</th>
      </tr>
    </thead>
    <tbody>';
    $first_relevant_sid_found = false;
    while ($row = $stmt->fetch()) {
        $counter++;
        $operators = explode("_", $row['operators']);
        $count = count($operators);
        for ($i = 0; $i < count($operators); $i++) {
            if ($operators[$i] == 'mult') {
                $operators[$i] = 'Multiplication';
            } else if ($operators[$i] == 'add') {
                $operators[$i] = 'Addition';
            } else if ($operators[$i] == 'minus') {
                $operators[$i] = 'Subtraction';
            } else if ($operators[$i] == 'div') {
                $operators[$i] = 'Division';
            }
        }
        $operators_used = join(", ", $operators);
        if ($_SESSION['student_id'] == $row['student_id'] && $first_relevant_sid_found == false) {
            $first_relevant_sid_found = true;
            echo '
        <tr class="newest">
        <td>' . $counter . '</td>
        <td>' . '<img src="profile_pictures/' . $row['profile_picture'] . '" style="width:40px; height:40px;" alt="Profile Picture">' . ' ' . $row['name'] . '</td>
        <td>Year ' . $row['student_year'] . '</td>
        <td>' . $row['questions_right'] . '</td>
        <td>' . $row['questions_wrong'] . '</td>
        <td>' . $count . ' : ' . $operators_used . '</td>
        <td>' . $row['questions_right'] + $row['questions_wrong'] . '</td>
        <td>' . $row['points'] . '</td>
        </tr>';
        } else {
            echo '
        <tr>
        <td>' . $counter . '</td>
        <td>' . '<img src="profile_pictures/' . $row['profile_picture'] . '" style="width:40px; height:40px;" alt="Profile Picture">' . ' ' . $row['name'] . '</td>
        <td>Year ' . $row['student_year'] . '</td>
        <td>' . $row['questions_right'] . '</td>
        <td>' . $row['questions_wrong'] . '</td>
        <td>' . $count . ' : ' . $operators_used . '</td>
        <td>' . $row['questions_right'] + $row['questions_wrong'] . '</td>
        <td>' . $row['points'] . '</td>
        </tr>';
        }
    }
    echo '</tbody>
    </table>
    ';
}

?>
<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <title>Homepage</title>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#subject').on('change', function() {
                var selectedOption = $(this).val();
                if (selectedOption === 'history') {
                    $('#topic').html('<option value="The_cold_war">The Cold War</option><option value="World_war_one">World War One</option><option value="World_war_two">World War Two</option>');
                } else if (selectedOption === 'chemistry') {
                    $('#topic').html('<option value="Atoms_elements_and_compounds">Atoms, Elements, and Compounds</option><option value="The_periodic_table">The periodic table</option><option value="Chemical_formulas_and_reactions">Chemical formulas and reactions</option>');
                } else if (selectedOption === 'biology') {
                    $('#topic').html('<option value="The_cell_and_its_structure">The cell and its structure</option><option value="The_human_body">The human body</option><option value="Microorganisms">Microorganisms</option><option value="Photosynthesis">Photosynthesis</option><option value="Effect_of_environment_on_organisms">The effect of the environment on organisms</option><option value="Evolution">Evolution</option>');
                } else if (selectedOption === 'geography') {
                    $('#topic').html('<option value="Physical_geography">Physical geography</option><option value="Human_geography">Human geography</option><option value="Natural_resources">Natural resources</option>');
                } else if (selectedOption === 'physics') {
                    $('#topic').html('<option value="Energy_and_energy_resources">Energy and energy resources</option><option value="Forces_and_motion">Forces and motion</option><option value="Waves_and_wave_properties">Waves and wave properties</option><option value="Light">Light</option><option value="Astrophysics_and_cosmology">Astrophysics and cosmology</option>');
                } else if (selectedOption === 'general_knowledge') {
                    $('#topic').html('<option value="General_knowledge">General Knowledge</option>');
                } else if (selectedOption === 'select') {
                    $('#topic').html('<option></option>');
                }

            });
        });
    </script>
    <link rel="stylesheet" href="../style.css" />

    <style type="text/css">
        #test {
            font-size: 20px;
            color: #23234c;
            text-align: center;
            padding: 0 4px;
            border: 3px solid black;
            margin: 0 5px;
        }

        .playBtn {
            font-size: 20px;
            padding: 14px 10px;
            background-color: orange;
            color: white;
            width: 15%;
        }

        .table th {
            background-color: grey;
            color: white;
            text-align: center;
        }

        .table td {
            background-color: white;
            text-align: center;
        }

        .table {
            border: 1px solid black;

        }

        p {
            font-size: large;
        }
    </style>
</head>

<body>

    <?php
    $checklistErr = '';
    // When form submitted, insert values into the database.
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        $checklistErr = '';
        $subjectErr = '';
        if (isset($_GET['check_list']) && isset($_GET['difficulty'])) {
            if (count($_GET['check_list']) > 0) {
                $diff = $_GET['difficulty'];
                $checklist = $_GET['check_list'];
                $checklistURL = '';
                for ($i = 0; $i < count($checklist); $i++) {
                    $checklistURL .= '&check_list%5B%5D=' . $checklist[$i];
                }
                echo '<script>location.href = "../Math_Game/formula_frenzy.php?difficulty=' . $diff . $checklistURL . '";</script>';
            } else {
                $checklistErr = 'You must input the operators you will play with';
            }
        } else {
            $checklistErr = 'You must input the operators you will play with';
        }
        if (isset($_GET['topic']) && isset($_GET['difficulty']) && isset($_GET['subject'])) {
            $diff = $_GET['difficulty'];
            $topic = $_GET['topic'];
            $subject = $_GET['subject'];
            echo '<script>location.href = "../Generic_SSM/playGeneric.php?difficulty=' . $diff . '&topic=' . $topic . '&subject=' . $subject . '";</script>';
        } else {
            $subjectErr = 'You must select a subject and topic';
        }
    }


    ?>
    <div class="container" style="text-align:left;">


        <br>
        <div class="row" id="top_row">
            <div class="col-sm-2">
                <div class="card">
                    <div class="card-body">
                        <div class="parent" style="text-align: center;">

                            <?php
                            $sql = "SELECT * FROM student WHERE student_id = :student_id";
                            $teachid = $_SESSION['student_id'];
                            $stmt = $pdo->prepare($sql);
                            $stmt->execute([
                                'student_id' => $teachid
                            ]);

                            $stmt->setFetchMode(PDO::FETCH_ASSOC);
                            while ($row = $stmt->fetch()) {
                                if (!empty($row["profile_picture"])) {
                                    echo ('<a href="change_pic.php"><img src="profile_pictures/' . $row["profile_picture"] . '" class="image1"></a>');
                                } else {
                                    echo ('<a href="change_pic.php"><img src="emptyIcon.png" class="image1"></a>');
                                }



                                echo '</div>';
                                echo '</div></div></div>';
                                echo '<div class="col-md-3">';
                                echo '<h1>' . $row['name'] . ' (' . $row['username'] . ')</h1>';
                                echo '<h1>Login Streak: ' . $row['login_streak'] . '</h1>';
                                echo '</div>';
                                echo '<div class="col-sm-7">';
                                echo '<a href="achievements.php"><h1><u>Achievements</u></h1></a>';
                                echo '<div id="achievements" style="overflow: scroll; height: 100px; width: 100%; border: 1px solid black; text-align: center;">
        <div class="row">';
                                echo '<h2>Math Badges</h2>';
                                $sql = "SELECT * FROM student_badge INNER JOIN badge ON student_badge.badge_id = badge.badge_id WHERE student_badge.student_id = :student_id AND badge.badge_type = :badge_type";
                                $stmt = $pdo->prepare($sql);
                                $stmt->execute([
                                    'student_id' => $teachid,
                                    'badge_type' => "math"
                                ]);
                                $saved_ids = array();
                                $stmt->setFetchMode(PDO::FETCH_ASSOC);
                                while ($row = $stmt->fetch()) {
                                    echo '<div class="col-md-3">';
                                    echo '<img src="badges/Math/' . $row['picture'] . '" class="image3">';
                                    echo '</div>';
                                    array_push($saved_ids, $row['badge_id']);
                                }
                                $sql = "SELECT * FROM badge WHERE badge_type = :badge_type";
                                $stmt = $pdo->prepare($sql);
                                $stmt->execute([
                                    'badge_type' => "math"
                                ]);
                                $stmt->setFetchMode(PDO::FETCH_ASSOC);
                                while ($row = $stmt->fetch()) {
                                    if (!in_array($row['badge_id'], $saved_ids)) {
                                        echo '<div class="col-md-3">';
                                        echo '<img src="badges/Math/' . $row['picture'] . '" class="image_greyed">';
                                        echo '</div>';
                                    }
                                }


                                echo '</div>';

                                echo '<div class="row"><h2>Leaderboard Badges</h2><br>';

                                $sql = "SELECT * FROM student_badge INNER JOIN badge ON student_badge.badge_id = badge.badge_id WHERE student_badge.student_id = :student_id AND badge.badge_type = :badge_type";
                                $stmt = $pdo->prepare($sql);
                                $stmt->execute([
                                    'student_id' => $teachid,
                                    'badge_type' => "leader"
                                ]);
                                $saved_ids = array();
                                $stmt->setFetchMode(PDO::FETCH_ASSOC);
                                while ($row = $stmt->fetch()) {
                                    echo '<div class="col-md-3">';
                                    echo '<img src="badges/leader/' . $row['picture'] . '" class="image3">';
                                    echo '</div>';
                                    array_push($saved_ids, $row['badge_id']);
                                }
                                $sql = "SELECT * FROM badge WHERE badge_type = :badge_type";
                                $stmt = $pdo->prepare($sql);
                                $stmt->execute([
                                    'badge_type' => "leader"
                                ]);
                                $stmt->setFetchMode(PDO::FETCH_ASSOC);
                                while ($row = $stmt->fetch()) {
                                    if (!in_array($row['badge_id'], $saved_ids)) {
                                        echo '<div class="col-md-3">';
                                        echo '<img src="badges/leader/' . $row['picture'] . '" class="image_greyed">';
                                        echo '</div>';
                                    }
                                }

                                echo '</div>
        <div class="row">
        <h2>Subject Savvy Millionaire Badges</h2>
        <br>';
                                $sql = "SELECT * FROM student_badge INNER JOIN badge ON student_badge.badge_id = badge.badge_id WHERE student_badge.student_id = :student_id AND badge.badge_type = :badge_type";
                                $stmt = $pdo->prepare($sql);
                                $stmt->execute([
                                    'student_id' => $teachid,
                                    'badge_type' => "ssm"
                                ]);
                                $saved_ids = array();
                                $stmt->setFetchMode(PDO::FETCH_ASSOC);
                                while ($row = $stmt->fetch()) {
                                    echo '<div class="col-md-3">';
                                    echo '<img src="badges/SSM/' . $row['picture'] . '" class="image3">';
                                    echo '</div>';
                                    array_push($saved_ids, $row['badge_id']);
                                }
                                $sql = "SELECT * FROM badge WHERE badge_type = :badge_type";
                                $stmt = $pdo->prepare($sql);
                                $stmt->execute([
                                    'badge_type' => "ssm"
                                ]);
                                $stmt->setFetchMode(PDO::FETCH_ASSOC);
                                while ($row = $stmt->fetch()) {
                                    if (!in_array($row['badge_id'], $saved_ids)) {
                                        echo '<div class="col-md-3">';
                                        echo '<img src="badges/SSM/' . $row['picture'] . '" class="image_greyed">';
                                        echo '</div>';
                                    }
                                }
                                echo '</div>
        <br>
        <div class="row">';

                                echo '<h2>Login Badges</h2><br>';
                                $sql = "SELECT * FROM student_badge INNER JOIN badge ON student_badge.badge_id = badge.badge_id WHERE student_badge.student_id = :student_id AND badge.badge_type = :badge_type";
                                $stmt = $pdo->prepare($sql);
                                $stmt->execute([
                                    'student_id' => $teachid,
                                    'badge_type' => "login"
                                ]);
                                $saved_ids = array();
                                $stmt->setFetchMode(PDO::FETCH_ASSOC);
                                while ($row = $stmt->fetch()) {
                                    echo '<div class="col-md-3">';
                                    echo '<img src="badges/login/' . $row['picture'] . '" class="image3">';
                                    echo '</div>';
                                    array_push($saved_ids, $row['badge_id']);
                                }
                                $sql = "SELECT * FROM badge WHERE badge_type = :badge_type";
                                $stmt = $pdo->prepare($sql);
                                $stmt->execute([
                                    'badge_type' => "login"
                                ]);
                                $stmt->setFetchMode(PDO::FETCH_ASSOC);
                                while ($row = $stmt->fetch()) {
                                    if (!in_array($row['badge_id'], $saved_ids)) {
                                        echo '<div class="col-md-3">';
                                        echo '<img src="badges/login/' . $row['picture'] . '" class="image_greyed">';
                                        echo '</div>';
                                    }
                                }
                                echo '<br>';
                                echo '
        </div>

        </div>';

                                echo '</div>';
                            }

                            ?>
                        </div>
                        <h1>Homepage</h1>
                        <div class="row" style="background-color: white;">
                            <div class="col-sm-3">
                                <br>
                                <div class="card">
                                    <div class="card-body">

                                        <button type="button" class="button" id="activated">Games</button><br><br>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <br>
                                <div class="card">
                                    <div class="card-body">
                                        <button type="button" class="button" onclick="location.href='db_grouper.php'">Discussion Boards</button><br><br> <!-- Student groupmates -->
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <br>
                                <div class="card">
                                    <div class="card-body">
                                        <button type="button" class="button" onclick="location.href='student_assignments.php'">Assignments</button><br><br>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <br>
                                <div class="card">
                                    <div class="card-body">
                                        <button type="button" class="button" onclick="location.href='student_settings.php'">Account</button><br><br>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-md-3">
                            </div>
                            <div class="col-md-3">
                                <br>
                                <div class="item">
                                    <a href="#" class="myBtn">
                                        <img src="SSM.png" alt="Subject Savvy Millionaire" class="img-thumbnail img-fluid" style="width: 200px; height: 200px">
                                        <span class="caption">Subject Savvy Millionaire</span>
                                    </a>
                                    <form class="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="get">
                                        <div id="myModal" class="modal">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <span class="close">&times;</span>
                                                    <h2>Subject Savvy Millionaire</h2>
                                                </div>
                                                <div class="modal-body">
                                                    <p style="text-align:left;"><b>Welcome to Subject Savvy Millionaire,</b> the exciting quiz game designed to test your knowledge and skills in various subject areas!</p>

                                                    <p style="text-align:left;">In this game, you will be given 12 subject-specific questions to answer. Each question will have four possible answers, but only one of them is correct. You will need to choose the right answer to move on to the next question and earn points.</p>

                                                    <p style="text-align:left;">To help you along the way, you will have three lifelines: a hint, a chance to attempt an answer without losing the game, and a 50/50 lifeline that removes two random incorrect answers. But be careful, if you select the wrong answer or take too long to answer, the game is over.</p>

                                                    <p style="text-align:left;">There are three difficulty levels to choose from, with each level providing harder questions. As you progress through the game and answer more questions correctly, you will climb the leaderboard and become a Subject Savvy Millionaire!</p>

                                                    <p style="text-align:left;">So, get ready to put your knowledge and skills to the test and see how high you can climb on the leaderboard. <b>Good luck, and have fun!</b></p>
                                                    <p><b>Pick a difficulty, a subject and a topic then click play to get started!</b></p>
                                                    <label for="difficulty" style="font-size:18px;"><b>Difficulty</b></label>
                                                    <select name="difficulty" id="difficulty">
                                                        <option value="easy">Easy</option>
                                                        <option value="medium">Medium</option>
                                                        <option value="hard">Hard</option>
                                                    </select>
                                                    <label for="subject" style="font-size:18px;"><b>Subject</b></label>
                                                    <select name="subject" id="subject">
                                                        <option value="select">Select a subject</option>
                                                        <option value="biology">Biology</option>
                                                        <option value="history">History</option>
                                                        <option value="chemistry">Chemistry</option>
                                                        <option value="geography">Geography</option>
                                                        <option value="physics">Physics</option>
                                                        <option value="general_knowledge">General knowledge</option>
                                                    </select>
                                                    <label for="topic" style="font-size:18px;"><b>Topics</b></label>
                                                    <select name="topic" id="topic">
                                                    </select>
                                                    <button class="playBtn">Play the game</button>
                                                    <span class="error">
                                                        <p><?php echo $subjectErr; ?></p>
                                                    </span>
                                                    <?php

                                                    ssm_table('easy', $pdo);
                                                    ssm_table('medium', $pdo);
                                                    ssm_table('hard', $pdo);


                                                    ?>
                                                </div>
                                            </div>
                                            <br>
                                            <br>
                                        </div>
                                    </form>

                                </div>
                            </div>
                            <div class="col-md-3">
                                <br>

                                <div class="item">
                                    <a href="#" class="myBtn">
                                        <img src="mathgame.png" alt="Math Game" class="img-thumbnail img-fluid" style="width: 200px; height: 200px">
                                        <span class="caption">Formula Frenzy</span>
                                    </a>
                                    <form class="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="get">
                                        <div id="myModal" class="modal">

                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <span class="close">&times;</span>
                                                    <h2>FORMULA FRENZY</h2>
                                                </div>
                                                <div class="modal-body">
                                                    <p style="text-align:left;"><b>Welcome to Formula Frenzy,</b> the thrilling educational game that will put your math skills to the test!</p>
                                                    <p style="text-align:left;">In this game, you'll be challenged to fill in the blank square in various formulas involving addition, multiplication, division, and subtraction (depending on what you pick). But don't worry, we'll give you helpful hints along the way. As you progress, the game will become more challenging by decreasing the time you have to complete each formula. But you'll also be given more hints to help you solve each equation.</p>

                                                    <p style="text-align:left;">The goal of the game is to earn as many points as possible by correctly solving each formula, but watch out because any incorrect answer will make you lose points. And, as you improve your skills, you can increase the difficulty level to challenge yourself even further. With three difficulty levels to choose from, you can customise your gameplay to your skill level and ensure that you are continuously improving.</p>

                                                    <p style="text-align:left;">So, get ready to put your math skills to the test and become a Formula Frenzy champion! <b>Good luck, and have fun!</b></p>

                                                    <label for="difficulty" style="font-size:18px"><b>Difficulty</b></label>
                                                    <select id="difficulty" name="difficulty" onChange="showBoxes(this);">
                                                        <option value="easy">Easy (2 terms)</option>
                                                        <option value="medium">Medium (3 terms)</option>
                                                        <option value="hard">Hard (4 terms)</option>
                                                    </select><br>
                                                    <div id="easy-box" style="display:block;">
                                                        <div class="col-md-4">
                                                        </div>
                                                        <div class="col-md-5">
                                                            <p>Example (operators depend on what you pick):</p>
                                                            <div class="row">
                                                                <div class="col-md-3">
                                                                </div>

                                                                <div class="col-md-1">
                                                                    <p>2</p>
                                                                </div>
                                                                <div class="col-md-1">
                                                                    <p>+</p>
                                                                </div>
                                                                <div class="col-md-1">
                                                                    <span id="test">?</span>
                                                                </div>
                                                                <div class="col-md-1">

                                                                    <p>=</p>
                                                                </div>

                                                                <div class="col-md-1">

                                                                    <p>5</p>
                                                                </div>
                                                                <div class="col-md-4">
                                                                </div>

                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                        </div>
                                                    </div>
                                                    <div id="medium-box" style="display:none;">
                                                        <div class="col-md-4">
                                                        </div>
                                                        <div class="col-md-5">
                                                            <p>Example (operators depend on what you pick):</p>
                                                            <div class="row">
                                                                <div class="col-md-2">
                                                                </div>

                                                                <div class="col-md-1">
                                                                    <p>2</p>
                                                                </div>
                                                                <div class="col-md-1">
                                                                    <p>+</p>
                                                                </div>
                                                                <div class="col-md-1">
                                                                    <p>(<span id="test">?</span></p>
                                                                </div>
                                                                <div class="col-md-1">

                                                                    <p>*</p>
                                                                </div>

                                                                <div class="col-md-1">
                                                                    <p>5)</p>

                                                                </div>
                                                                <div class="col-md-1">
                                                                    <p>=</p>

                                                                </div>
                                                                <div class="col-md-1">
                                                                    <p>27</p>

                                                                </div>

                                                                <div class="col-md-3">
                                                                </div>

                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                        </div>
                                                    </div>
                                                    <div id="hard-box" style="display:none;">
                                                        <div class="col-md-4">
                                                        </div>
                                                        <div class="col-md-5">
                                                            <p>Example (operators depend on what you pick):</p>
                                                            <div class="row">
                                                                <div class="col-md-1">
                                                                </div>
                                                                <div class="col-md-1">
                                                                    <p>2</p>
                                                                </div>
                                                                <div class="col-md-1">
                                                                    <p>+</p>
                                                                </div>
                                                                <div class="col-md-1">
                                                                    <p>(<span id="test">?</span></p>
                                                                </div>
                                                                <div class="col-md-1">

                                                                    <p>*</p>
                                                                </div>

                                                                <div class="col-md-1">
                                                                    <p>5)</p>

                                                                </div>
                                                                <div class="col-md-1">
                                                                    <p>-</p>

                                                                </div>
                                                                <div class="col-md-1">
                                                                    <p>14</p>

                                                                </div>
                                                                <div class="col-md-1">
                                                                    <p>=</p>

                                                                </div>
                                                                <div class="col-md-1">
                                                                    <p>13</p>

                                                                </div>
                                                                <div class="col-md-2">
                                                                </div>

                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                        </div>
                                                    </div>

                                                    <br><br><br><br>

                                                    <p><b>Pick some operators</b></p>
                                                    <div class="checkboxes">
                                                        <label style="font-size:18px;"><input type="checkbox" id="checkinput" name="check_list[]" value="add"> Addition (+)</label><br>
                                                        <label style="font-size:18px;"><input type="checkbox" id="checkinput" name="check_list[]" value="minus"> Subtraction (-)</label><br>
                                                        <label style="font-size:18px;"><input type="checkbox" id="checkinput" name="check_list[]" value="mult"> Multiplication (*)</label><br>
                                                        <label style="font-size:18px;"><input type="checkbox" id="checkinput" name="check_list[]" value="div"> Division (/)</label> <!-- Maybe division Icon here? -->
                                                    </div>
                                                    <span class="error">
                                                        <p><?php echo $checklistErr; ?></p>
                                                    </span><br>
                                                    <button class='playBtn' type="submit">Play the game</button>

                                                    <!-- <p>Image here?</p> -->
                                                    <?php
                                                    math_table('easy', $pdo);
                                                    math_table('medium', $pdo);
                                                    math_table('hard', $pdo);
                                                    $pdo = null;
                                                    ?>
                                                </div>
                                            </div>
                                            <br>
                                            <br>
                                        </div>
                                    </form>

                                </div>
                            </div>
                            <div class="col-md-3">
                            </div>
                        </div>

                    </div>
                </div>
            </div>
</body>
<script>
    function showBoxes(selected) {
        if (selected.options[selected.selectedIndex].value == 'easy') {
            document.getElementById("easy-box").style.display = "block";
            document.getElementById("medium-box").style.display = "none";
            document.getElementById("hard-box").style.display = "none";
        } else if (selected.options[selected.selectedIndex].value == 'medium') {
            document.getElementById("medium-box").style.display = "block";
            document.getElementById("easy-box").style.display = "none";
            document.getElementById("hard-box").style.display = "none";
        } else if (selected.options[selected.selectedIndex].value == 'hard') {
            document.getElementById("hard-box").style.display = "block";
            document.getElementById("medium-box").style.display = "none";
            document.getElementById("easy-box").style.display = "none";
        }
    }

    // Get the modal
    var modal = document.getElementsByClassName('modal');
    // Get the button that opens the modal
    var btn = document.getElementsByClassName("myBtn");


    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close");

    // When the user clicks the button, open the modal 
    // alert(btn.length);
    for (let index = 0; index < modal.length; index++) {
        const element = modal[index];
        btn[index].onclick = function() {
            modal[index].style.display = "block";
        }
        span[index].onclick = function() {
            modal[index].style.display = "none";
        }
    }
    window.onclick = function(event) {
        // alert(event.target);
        for (let index = 0; index < modal.length; index++) {
            if (event.target == modal[index]) {
                modal[index].style.display = "none";
            }
        }
    }
</script>

</html>