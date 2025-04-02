<?php
require __DIR__ . '/vendor/autoload.php'; // Load Composer dependencies
use Dotenv\Dotenv;
// Load .env file
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();
// Retrieve database credentials
$dbhost = $_ENV['MYSQL_HOST'] ?? getenv('MYSQL_HOST');
$dbuser = $_ENV['MYSQL_USER'] ?? getenv('MYSQL_USER');
$dbpass = $_ENV['MYSQL_PASSWORD'] ?? getenv('MYSQL_PASSWORD');
$dbname = $_ENV['MYSQL_DATABASE'] ?? getenv('MYSQL_DATABASE');
// Ensure all required variables are set
if (!$dbhost || !$dbuser || !$dbname) {
    die("Error: Missing required database environment variables.");
}
// Establish database connection
$connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
// Check connection
if (!$connection) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>