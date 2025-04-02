<?php
/*require __DIR__ . '/vendor/autoload.php'; // Load Composer dependencies
use Dotenv\Dotenv;

// Load .env file
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();
*/
// Retrieve database credentials
$dbhost = "lampdatabase.cxcosy6qkohl.eu-west-1.rds.amazonaws.com";
$dbuser = "root";
$dbpass = "sylvester7890&";
$dbname = "lampdatabase";

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
