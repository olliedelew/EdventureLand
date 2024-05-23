<?php
    session_start();
    if(!isset($_SESSION['user']) || !isset($_SESSION['school_id']) || !isset($_SESSION['student_id'])){
        echo '<script>location.href = "../login.php";</script>';
    }
    include '../connection.php';

    ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Progress</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/regression/2.0.1/regression.min.js" integrity="sha512-0k6FXllQktdobw8Nc8KQN2WtZrOuxpMn7jC2RKCF6LR7EdOhhrg3H5cBPxhs3CFzQVlO6ni1B9SDLUPhBs0Alg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="../style.css" />

</head>

<body>
<div class="container">

<h1>Assignments Due</h1>
      <div class="row" style="background-color:white;">
          <div class="col-sm-3">
              <br>
              <div class="card">
                  <div class="card-body">

                      <button type="button" class="button" onclick="location.href='student_homepage.php'">Games</button><br><br>
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
                      <button type="button" class="button"  id="activated" onclick="location.href='student_settings.php'">Account</button><br><br>
                  </div>
              </div>
              </div>
              </div>

<?php
    session_start();
    $sid = $_SESSION['student_id'];
    $sql = "SELECT * FROM student WHERE student_id = :student_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'student_id' => $sid
    ]);  
    $stmt->setFetchMode(PDO::FETCH_ASSOC);

    while($row = $stmt->fetch()){
        $s_name = $row['name'];
    }
    echo '<input type="hidden" id="student_name" name = "student_name" value = "' . $s_name . '">';

    $sql = "SELECT * FROM manual_submission INNER JOIN assignment ON assignment.assignment_id = manual_submission.assignment_id INNER JOIN manual_assignment ON assignment.manual_id = manual_assignment.manual_assignment_id WHERE manual_submission.student_id = :student_id ORDER BY manual_assignment.test_datetime ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'student_id' => $sid
    ]);  
    echo '<input type="hidden" id="student_id" name = "student_id" value = "' . $sid . '">';
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $manual_submission_id_array = [];
    $manual_submission_results = [];
    $manual_id_array = [];
    $manual_titles_array = [];
    $manual_assignment_id = [];

    while($row = $stmt->fetch()){
        array_push($manual_submission_id_array, $row['manual_submission_id']);
        array_push($manual_assignment_id, $row['assignment_id']);
        if($row['result'] == NULL){
            $row['result'] = 0;
        }
        array_push($manual_submission_results, $row['result']);
        array_push($manual_id_array, $row['manual_id']);
        array_push($manual_titles_array, $row['title']);
    }
    $manual_submission_id_array = [1, 2, 3, 4, 5, 6, end($manual_submission_id_array)];
    $manual_id_array = [1, 2, 3, 4, 5, 6, end($manual_id_array)];
    $manual_assignment_id = [1, 2, 3, 4, 5, 6, end($manual_assignment_id)];
    $manual_titles_array = ['assignment1', 'assignment2', 'assignment3', 'assignment4', 'assignment5', 'assignment6', end($manual_titles_array)];
    $manual_submission_results = [33, 45, 78, 55, 88, 99, end($manual_submission_results)];

    echo '<input type="hidden" id="manual_ids" name = "manual_ids" value = "' . implode(',', $manual_id_array) . '">
    <input type="hidden" id="manual_sub_ids" name = "manual_sub_ids" value = "' . implode(',', $manual_submission_id_array) . '">
    <input type="hidden" id="manual_assignment_ids" name = "manual_assignment_ids" value = "' . implode(',', $manual_assignment_id) . '">';

    $sql = "SELECT * FROM math_submission INNER JOIN assignment ON assignment.assignment_id = math_submission.assignment_id INNER JOIN math_assignment ON assignment.math_id = math_assignment.math_assignment_id WHERE math_submission.student_id = :student_id ORDER BY math_assignment.test_datetime ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'student_id' => $sid
    ]);  

    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $math_submission_id_array = [];
    $math_submission_results = [];
    $math_id_array = [];
    $math_titles_array = [];
    $math_assignment_id = [];
    while($row = $stmt->fetch()){
        array_push($math_assignment_id, $row['assignment_id']);
        array_push($math_id_array, $row['math_id']);
        array_push($math_titles_array, $row['title']);
        array_push($math_submission_id_array, $row['math_submission_id']);
        if($row['result'] == NULL){
            $row['result'] = 0;
        }
        array_push($math_submission_results, $row['result']);
    }
    $math_submission_id_array = [1, 2, 3, 4, 5, 6, end($math_submission_id_array)];
    $math_id_array = [1, 2, 3, 4, 5, 6, end($math_id_array)];
    $math_assignment_id = [1, 2, 3, 4, 5, 6, end($math_assignment_id)];
    $math_titles_array = ['assignment1', 'assignment2', 'assignment3', 'assignment4', 'assignment5', 'assignment6', end($math_titles_array)];
    $math_submission_results = [33, 45, 78, 55, 88, 99, end($math_submission_results)];

    echo '<input type="hidden" id="math_ids" name = "math_ids" value = "' . implode(',', $math_id_array) . '">
    <input type="hidden" id="math_assignment_ids" name = "math_assignment_ids" value = "' . implode(',', $math_assignment_id) . '">
    <input type="hidden" id="math_sub_ids" name = "math_sub_ids" value = "' . implode(',', $math_submission_id_array) . '">';


    $sql = "SELECT * FROM quiz_submission INNER JOIN assignment ON assignment.assignment_id = quiz_submission.assignment_id INNER JOIN quiz_assignment ON assignment.quiz_id = quiz_assignment.quiz_assignment_id WHERE quiz_submission.student_id = :student_id ORDER BY quiz_assignment.test_datetime ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'student_id' => $sid
    ]);  

    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $quiz_submission_id_array = [];
    $quiz_id_array = [];
    $quiz_titles_array = [];
    $quiz_submission_results = [];
    $quiz_assignment_id = [];
    while($row = $stmt->fetch()){
        array_push($quiz_assignment_id, $row['assignment_id']);
        array_push($quiz_id_array, $row['quiz_id']);
        array_push($quiz_submission_id_array, $row['quiz_submission_id']);
        array_push($quiz_titles_array, $row['title']);
        if($row['result'] == NULL){
            $row['result'] = 0;
        }
        array_push($quiz_submission_results, $row['result']);
    }
    $quiz_submission_id_array = [1, 2, 3, 4, 5, 6, end($quiz_submission_id_array)];
    $quiz_id_array = [1, 2, 3, 4, 5, 6, end($quiz_id_array)];
    $quiz_assignment_id = [1, 2, 3, 4, 5, 6, end($quiz_assignment_id)];
    $quiz_titles_array = ['assignment1', 'assignment2', 'assignment3', 'assignment4', 'assignment5', 'assignment6', end($quiz_titles_array)];
    $quiz_submission_results = [33, 45, 78, 55, 88, 99, end($quiz_submission_results)];

    echo '<input type="hidden" id="quiz_ids" name = "quiz_ids" value = "' . implode(',', $quiz_id_array) . '">
    <input type="hidden" id="quiz_assignment_ids" name = "quiz_assignment_ids" value = "' . implode(',', $quiz_assignment_id) . '">
    <input type="hidden" id="quiz_sub_ids" name = "quiz_sub_ids" value = "' . implode(',', $quiz_submission_id_array) . '">';
$data = array(12, 19, 3, 50, 2, 100, 75);
    $sql = "SELECT * FROM student WHERE student_id = :student_id";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'student_id' => $sid
    ]);  

    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    while($row = $stmt->fetch()){
        echo '<u><h1 style="text-align: center;">' . $row['name'] . '</h1></u>';
    }
    $pdo = null;
    ?>

<h2 style="text-align: center;">Click any data point to get more information on the assignment</h2>
    <div id="line-graph">
        <h2><u>Subject Savvy Millionaire Assignment Progress</u></h2>
        <canvas id="myChart" style="background-color: white;"></canvas>
        <h2><u>Math Assignment Progress</u></h2>
        <canvas id="myChart1" style="background-color: white;"></canvas>
        <h2><u>Custom Assignment Progress</u></h2>
        <canvas id="myChart2" style="background-color: white;"></canvas>
    </div>
    <script>

            // getting the regression object
            // Edited from https://www.anychart.com/blog/2018/05/29/regression-analysis-anychart-javascript-charts/#:~:text=js-,Regression.,and%20on%20the%20cdnjs%20CDN
            function line_of_best_fit(results){
                var dataset = results;
                var arr = [];
                for (let index = 0; index < dataset.length; index++) {
                    const element = dataset[index];
                    arr.push([index, element]);
                }
                var result = regression.linear(arr);

                //get coefficients from the calculated formula
                var coeff = result.equation;
                
                function formula(coeff, x) {
                    var result = null;
                    for (var i = 0, j = coeff.length - 1; i < coeff.length; i++, j--) {
                        result += coeff[i] * Math.pow(x, j);
                    }
                    return result;
                }
                function setTheoryData(rawData) {
                    var theoryData = [];
                    for (var i = 0; i < rawData.length; i++) {
                        theoryData[i] = [rawData[i][0], formula(coeff, rawData[i][0])];
                    }
                    return theoryData;
                }

                var data_2 = setTheoryData(arr);
                // alert(data_2.length);
                var ys = [];
                for (let index = 0; index < data_2.length; index++) {
                    const element = data_2[index][1];
                    ys.push(element);
                }
                return ys;
            }
            quiz_ys = line_of_best_fit(<?php echo json_encode($quiz_submission_results); ?>);

        var ctx = document.getElementById('myChart').getContext('2d');
        var chart = new Chart(ctx, {
            // The type of chart we want to create
            type: 'line',

            // The data for our dataset
            data: {
                labels: <?php echo json_encode($quiz_titles_array); ?>,
                datasets: [{
                    label: 'Subject Savvy Millionaire Assignment %' ,
                    backgroundColor: 'green',
                    borderColor: 'green',
                    borderWidth: 1,

                    // pass mark for each quiz
                    data: <?php echo json_encode($quiz_submission_results); ?>,
                    fill: false,
                    // Set the point radius to 10
                    pointRadius: 10,
                    pointHoverCursor: 'pointer',

                    // Set the hover radius to 12
                    pointHoverRadius: 12,
                },
      {
        label: "Line of Best Fit",
        data: quiz_ys,                    
        fill: false,
        backgroundColor: "rgba(54, 162, 235, 0.2)",
        borderColor: "rgba(54, 162, 235, 1)",
        borderWidth: 3,
        pointRadius: 0,
        pointHoverCursor: 'pointer',
      }]
            },

            options: {
                responsive: true,
                backgroundColor: 'rgba(255, 255, 255, 0.2)', // set the background color of the chart area
                maintainAspectRatio: true,
                aspectRatio: 3, // aspect ratio of 2:1

                legend: {
                        onClick: (e) => e.stopPropagation()
                    },
                onClick: function(event, elements) {
                // Check if an element was clicked
                if (elements.length > 0) {
                    // Get the first element that was clicked
                    var element = elements[0];
                    // Get the label of the element
                    var label = this.data.labels[element._index];
                    var split_sub_ids = (document.getElementById('quiz_sub_ids').value).split(",");
                    var split_quiz_ids = (document.getElementById('quiz_ids').value).split(",");
                    var split_assignment_ids = (document.getElementById('quiz_assignment_ids').value).split(",");
                    var student_id = (document.getElementById('student_id').value);
                    var student_name = (document.getElementById('student_name').value).replace(/ /g,"_");
                    var form = document.createElement("form");
                    form.setAttribute("method", "post");
                    form.setAttribute("action", 'quiz_information.php');

                    var input3 = document.createElement("input");
                    input3.setAttribute("type", "hidden");
                    input3.setAttribute("name", "quiz_id");
                    input3.setAttribute("value", split_quiz_ids[element._index]);

                    var input4 = document.createElement("input");
                    input4.setAttribute("type", "hidden");
                    input4.setAttribute("name", "quiz_submission_id");
                    input4.setAttribute("value", split_sub_ids[element._index]);

                    // Create button element
                    var button = document.createElement("input");
                    button.setAttribute("type", "submit");
                    button.setAttribute("value", "Submit");

                    // Append hidden inputs and button to form
                    form.appendChild(input3);
                    form.appendChild(input4);

                    form.appendChild(button);

                    // Append form to document body
                    document.body.appendChild(form);

                    // Automatically submit form
                    form.submit();

                }
                },
                tooltips: {
                      // Disable the display of the color boxes
                    displayColors: false,
                      // Define a custom label callback function
                callbacks: {
                    label: function(tooltipItem) {
                    // Get the y-axis value of the data point
                    var value = tooltipItem.yLabel;
                    // Add the y-axis value to the tooltip
                    return value + '%';
                    }
                }
                },
  
                scales: {
                    yAxes: [{
                            scaleLabel: {
                            display: true,
                            labelString: '% achieved'
                            },
                            ticks: {
                                beginAtZero: true,
                                steps: 10,
                                stepValue: 5,
                                max: 100,
                                min: 0
                            }

                        }],
                        xAxes: [{
                            scaleLabel: {
                            display: true,
                            labelString: 'Subject Savvy Millionaire assignment'
                            }
                        }]

                    }
            }
        });
        math_ys = line_of_best_fit(<?php echo json_encode($math_submission_results); ?>);

        var ctx = document.getElementById('myChart1').getContext('2d');
        var chart = new Chart(ctx, {
            // The type of chart we want to create
            type: 'line',

            // The data for our dataset
            data: {
                labels: <?php echo json_encode($math_titles_array); ?>,
                datasets: [{
                    label: 'Formula Frenzy Assignment %',
                    backgroundColor: 'purple',
                    borderColor: 'purple',
                    data: <?php echo json_encode($math_submission_results); ?>,
                    fill: false,
                    // Set the point radius to 10
                    pointRadius: 10,
                    // Set the hover radius to 12
                    pointHoverRadius: 12,
                    pointHoverCursor: 'pointer'
                },
      {
        label: "Line of Best Fit",
        data: math_ys,                    
        fill: false,
        backgroundColor: "rgba(54, 162, 235, 0.2)",
        borderColor: "rgba(54, 162, 235, 1)",
        borderWidth: 3,
        pointRadius: 0,
        pointHoverCursor: 'pointer',
      }]
            },

            // Configuration options go here
            options: {
                responsive: true,
                maintainAspectRatio: true,
                aspectRatio: 3, // aspect ratio of 2:1

                // Stops you being able to click the legend thing and make the graph vanish.
                legend: {
                        onClick: (e) => e.stopPropagation()
                    },
                onClick: function(event, elements) {
                // Check if an element was clicked
                if (elements.length > 0) {
                    // Get the first element that was clicked
                    var element = elements[0];
                    // Get the label of the element
                    var label = this.data.labels[element._index];
                    var split_sub_ids = (document.getElementById('math_sub_ids').value).split(",");
                    var split_assignment_ids = (document.getElementById('math_assignment_ids').value).split(",");
                    var split_math_ids = (document.getElementById('math_ids').value).split(",");
                    var student_id = (document.getElementById('student_id').value);
                    var student_name = (document.getElementById('student_name').value).replace(/ /g,"_");

                    var form = document.createElement("form");
                    form.setAttribute("method", "post");
                    form.setAttribute("action", 'math_information.php');

                    var input3 = document.createElement("input");
                    input3.setAttribute("type", "hidden");
                    input3.setAttribute("name", "math_id");
                    input3.setAttribute("value", split_math_ids[element._index]);

                    var input4 = document.createElement("input");
                    input4.setAttribute("type", "hidden");
                    input4.setAttribute("name", "math_submission_id");
                    input4.setAttribute("value", split_sub_ids[element._index]);

                    // Create button element
                    var button = document.createElement("input");
                    button.setAttribute("type", "submit");
                    button.setAttribute("value", "Submit");

                    // Append hidden inputs and button to form
                    form.appendChild(input3);
                    form.appendChild(input4);

                    form.appendChild(button);

                    // Append form to document body
                    document.body.appendChild(form);

                    // Automatically submit form
                    form.submit();
                }
            },
                tooltips: {
                      // Disable the display of the color boxes
                    displayColors: false,
                      // Define a custom label callback function
                callbacks: {
                    label: function(tooltipItem) {
                    // Get the y-axis value of the data point
                    var value = tooltipItem.yLabel;
                    // Add the y-axis value to the tooltip
                    return value + '%';
                    }
                }
                },
                scales: {
                        yAxes: [{
                            scaleLabel: {
                            display: true,
                            labelString: '% achieved'
                            },
                            ticks: {
                                beginAtZero: true,
                                steps: 10,
                                stepValue: 5,
                                max: 100,
                                min: 0
                            }

                        }],
                        xAxes: [{
                            scaleLabel: {
                            display: true,
                            labelString: 'Formula Frenzy assignment'
                            }
                        }]

                    }
            }
        });
        manual_ys = line_of_best_fit(<?php echo json_encode($manual_submission_results); ?>);

        var ctx = document.getElementById('myChart2').getContext('2d');
        var chart = new Chart(ctx, {
            // The type of chart we want to create
            type: 'line',
            // The data for our dataset
            data: {
                labels: <?php echo json_encode($manual_titles_array); ?>,
                datasets: [{
                    label: 'Custom Assignment %',
                    backgroundColor: 'saddlebrown',
                    borderColor: 'saddlebrown',
                    // Change this the pass mark for each quiz

                    data: <?php echo json_encode($manual_submission_results); ?>,
                    fill: false,
                    // Set the point radius to 10
                    pointRadius: 10,
                    // Set the hover radius to 12
                    pointHoverRadius: 12,
                    pointHoverCursor: 'pointer',
                },
                {
                    label: "Line of Best Fit",
                    data: manual_ys,                    
                    fill: false,
                    backgroundColor: "rgba(54, 162, 235, 0.2)",
                    borderColor: "rgba(54, 162, 235, 1)",
                    borderWidth: 3,
                    pointRadius: 0,
                    pointHoverCursor: 'pointer',
                }]
            },

            // Configuration options go here
            options: {
                responsive: true,
                maintainAspectRatio: true,
                aspectRatio: 3, // aspect ratio of 2:1

                legend: {
                        onClick: (e) => e.stopPropagation()
                    },
                onClick: function(event, elements) {
                // Check if an element was clicked
                if (elements.length > 0) {
                    // Get the first element that was clicked
                    var element = elements[0];
                    // Get the label of the element
                    var label = this.data.labels[element._index];
                    var split_sub_ids = (document.getElementById('manual_sub_ids').value).split(",");
                    var split_assignment_ids = (document.getElementById('manual_assignment_ids').value).split(",");
                    var split_manual_ids = (document.getElementById('manual_ids').value).split(",");
                    var student_id = (document.getElementById('student_id').value);
                    var student_name = (document.getElementById('student_name').value).replace(/ /g,"_");
                    var form = document.createElement("form");
                    form.setAttribute("method", "post");
                    form.setAttribute("action", 'manual_information.php');

                    var input3 = document.createElement("input");
                    input3.setAttribute("type", "hidden");
                    input3.setAttribute("name", "manual_id");
                    input3.setAttribute("value", split_manual_ids[element._index]);

                    var input4 = document.createElement("input");
                    input4.setAttribute("type", "hidden");
                    input4.setAttribute("name", "manual_submission_id");
                    input4.setAttribute("value", split_sub_ids[element._index]);

                    // Create button element
                    var button = document.createElement("input");
                    button.setAttribute("type", "submit");
                    button.setAttribute("value", "Submit");

                    // Append hidden inputs and button to form
                    form.appendChild(input3);
                    form.appendChild(input4);

                    form.appendChild(button);

                    // Append form to document body
                    document.body.appendChild(form);

                    // Automatically submit form
                    form.submit();

                }
            },
                tooltips: {
                      // Disable the display of the color boxes
                    displayColors: false,
                      // Define a custom label callback function
                callbacks: {
                    label: function(tooltipItem) {
                    // Get the y-axis value of the data point
                    var value = tooltipItem.yLabel;
                    // Add the y-axis value to the tooltip
                    return value + '%';
                    }
                }
                },
                scales: {
                    yAxes: [{
                            scaleLabel: {
                            display: true,
                            labelString: '% achieved'
                            },
                            ticks: {
                                beginAtZero: true,
                                steps: 10,
                                stepValue: 5,
                                max: 100,
                                min: 0
                            }

                        }],
                        xAxes: [{
                            scaleLabel: {
                            display: true,
                            labelString: 'Custom Assignment'
                            }
                        }]

                    }

            }
        });
    </script>
    <script>
</script>
</div>
</body>

</html>
