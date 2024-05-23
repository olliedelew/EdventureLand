<?php
try {
    $pdo = new PDO("mysql:host=localhost", 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "CREATE DATABASE IF NOT EXISTS edventure_land;
    USE edventure_land;
    CREATE USER IF NOT EXISTS 'n09319od'@'localhost' IDENTIFIED BY 'SuperLearner123';
    GRANT ALL PRIVILEGES ON edventure_land.* TO 'n09319od'@'localhost'";
    $pdo->exec($sql);
    $pdo = null;
} catch(PDOException $e) {
    echo "Error creating database: " . $e->getMessage();
}
?>