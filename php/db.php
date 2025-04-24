<?php
$host = getenv('DB_HOST');
$dbname = getenv('DB_NAME');
$username = getenv('DB_USER'); 
$password = getenv('DB_PASS');

// Add error logging
error_log("Attempting to connect to database: host=$host, dbname=$dbname, user=$username");

try {
    $dsn = "mysql:host={$host};port=3306;dbname={$dbname};charset=utf8mb4";
    
    // Increase timeout for connection
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_TIMEOUT => 15, // 15-second timeout
    ];
    
    $pdo = new PDO($dsn, $username, $password, $options);

    error_log("Database connection successful");
    
    // Check if users table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'users'");
    if($stmt->rowCount() == 0) {
        error_log("Creating users table");
        // Create users table if it doesn't exist
        $sql = "CREATE TABLE users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            email VARCHAR(100) NOT NULL UNIQUE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        $pdo->exec($sql);
    }
} catch(PDOException $e) {
    error_log("Database connection failed: " . $e->getMessage());
    echo "Connection failed: " . $e->getMessage();
}