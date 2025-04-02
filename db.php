<?php
$host = "proxy-1743583471687-lampdatabase.proxy-cxcosy6qkohl.eu-west-1.rds.amazonaws.com";
$dbname = "lampdatabase";
$username = "root";
$password = "sylvester7890&";

try {
    // Connect to MySQL
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if the 'users' table exists using INFORMATION_SCHEMA
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = :dbname AND TABLE_NAME = 'users'");
    $stmt->execute([':dbname' => $dbname]);
    $tableExists = $stmt->fetchColumn();

    if (!$tableExists) {
        // Create the 'users' table if it doesn't exist
        $sql = "
        CREATE TABLE users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(255) NOT NULL UNIQUE,
            email VARCHAR(255) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL
        );
        ";
        $pdo->exec($sql);
        echo "Table 'users' created successfully.\n";
    }
} catch (PDOException $e) {
    error_log("Database connection failed: " . $e->getMessage());
    die("Database error. Please check logs.");
}
?>
