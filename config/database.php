
<?php

// Database Configuration

$host = "localhost";
$dbname = "archivio_db";
$username = "root";
$password = "";

// Connect PHP to MySQL using PDO

try {

    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password
    );

    // Set PDO error mode
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    
} catch (PDOException $e) {

    echo "Database connection failed: " . $e->getMessage();

}

?>
