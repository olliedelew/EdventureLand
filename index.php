<!-- 
File: index.php
Description: This file contains the index page of the website showing the login and register buttons and the name of the VLE.
Author: Oliver Delew
Date: March 8, 2023
-->
<?php
include 'create_db.php';
include 'connection.php';
try {
    // Read the SQL file
    $sql_file = file_get_contents('edventure_land.sql');
    // Execute the SQL queries to create database
    $pdo->exec($sql_file);
    // echo "Database created successfully";  
    $pdo = null;
} catch(PDOException $e) {
  // echo "Error creating database: " . $e->getMessage();
  $pdo = null;
}
?>
<!DOCTYPE html>
<html>

<head>
  <title>Index</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <style>
    /* Have a common background for all pages which is lightblue */
    body {
      background-color: lightblue;
    }

    /* Set a style for the register and login buttons */
    #login_reg_button {
      background-color: orange;
      color: white;
      width: 100%;
      padding: 14px 20px;
      margin-top: 70%;
      margin-left: 15%;
      border: 5px solid black;
      padding-top: 30px;
      padding-bottom: 30px;
      font-size: 40px;
      border-radius: 50%;
    }
    
    /* if those buttons are hovered then change them to dark orange */
    #login_reg_button:hover {
      background-color: darkorange;
    }
  </style>
</head>

<body>

<!-- Push everything down a bit -->
    <br>
    <br>
    <!-- Here we produce the name of the VLE for anyone trying to access it -->
    <h1 style="text-align:center; font-size:70px;"><u>EdVenture Land</u></h1>
  </div>
  <!-- Create a row -->
  <div class="row">
    <!-- Have the login button indented a bit -->
    <div class="col-md-2"></div>
    <!-- Here we have the login button which also contains an onclick to the login page and has the id for CSS stuff -->
    <div class="col-md-3">
          <button onclick="location.href='login.php'" id="login_reg_button">Login</button>
    </div>
    <!-- A little space between both buttons -->
    <div class="col-md-1">
    </div>
    <!-- Here we have the register button which also contains an onclick to the register page and has the id for CSS stuff -->
    <div class="col-md-3">
          <button onclick="location.href='registration.php'" id="login_reg_button">Register</button>
    </div>
    <!-- Have the register button outdented a bit -->
    <div class="col-md-2">
    </div>
</body>

</html>