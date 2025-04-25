<?php

// Load environment variables
$servername = getenv('DB_HOST');
$username   = getenv('DB_USERNAME'); 
$password   = getenv('DB_PASSWORD');
$dbname     = getenv('DB_NAME');

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check if connection works
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if it doesn't exist
$sql = "CREATE DATABASE IF NOT EXISTS lampdatabase";
if (!$conn->query($sql)) {
    die("Error creating database: " . $conn->error);
}

// Select database
$conn->select_db("lampdatabase");

// Create users table if it doesn't exist
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL UNIQUE, 
    password VARCHAR(255) NOT NULL
)";
if (!$conn->query($sql)) {
    die("Error creating table: " . $conn->error);
}

// Initialize DB only if empty
$result = $conn->query("SHOW TABLES");
if ($result && $result->num_rows === 0) {
    $sql = file_get_contents("database.sql");
    if (!$conn->multi_query($sql)) {
        error_log("Error initializing database: " . $conn->error);
    }
}
