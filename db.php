<?php
require __DIR__ . '/vendor/autoload.php';

// Get the entire secret as JSON
$secretJson = getenv('MYSQL_SECRET') ?: die("Error: Missing MYSQL_SECRET");
$secret = json_decode($secretJson, true);

// Extract values
$dbhost = $secret['MYSQL_HOST'] ?? die("Error: Missing DB_HOST in secret");
$dbuser = $secret['MYSQL_USER'] ?? die("Error: Missing DB_USER in secret");
$dbpass = $secret['MYSQL_PASSWORD'] ?? '';
$dbname = $secret['MYSQL_DATABASE'] ?? die("Error: Missing DB_NAME in secret");

// Establish connection
$connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

if (!$connection) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>
