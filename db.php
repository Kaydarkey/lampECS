<?php
/*require __DIR__ . '/vendor/autoload.php'; // Load Composer dependencies
use Dotenv\Dotenv;

// Load .env file
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();
*/
// Retrieve database credentials from AWS Secrets Manager
require 'vendor/autoload.php';

use Aws\SecretsManager\SecretsManagerClient;
use Aws\Exception\AwsException;

$client = new SecretsManagerClient([
    'version' => 'latest',
    'region'  => 'eu-west-1'
]);

try {
    $result = $client->getSecretValue([
        'SecretId' => 'dr-project-secret-key-eu-west-1'
    ]);
    
    if (isset($result['SecretString'])) {
        $secret = json_decode($result['SecretString'], true);
        $dbhost = $secret['host'];
        $dbuser = $secret['username']; 
        $dbpass = $secret['password'];
        $dbname = $secret['dbname'];
    }
} catch (AwsException $e) {
    die("Error retrieving database credentials from Secrets Manager: " . $e->getMessage());
}
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
