<?php

// Load environment variables
$servername = getenv('DB_HOST');
$username   = getenv('DB_USERNAME');
$password   = getenv('DB_PASSWORD');
$dbname     = getenv('DB_NAME');

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if connection works
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize DB only if empty
$result = $conn->query("SHOW TABLES");
if ($result && $result->num_rows === 0) {
    $sql = file_get_contents("database.sql");
    if (!$conn->multi_query($sql)) {
        error_log("Error initializing database: " . $conn->error);
    }
}
