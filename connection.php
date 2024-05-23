<?php
$servername = "localhost";
$username = "n09319od";
$password = "SuperLearner123";
$dbname = "edventure_land";
try {
  $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  // set the PDO error mode to exception
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}
?>
