<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Welcome | PHP App</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>🎉 Welcome to My PHP App 🎉</h2>
        <h3>Hello, <?php echo $_SESSION["username"]; ?>!</h3>
        <canvas id="confetti"></canvas>
        <br><br>
        <a href="index.php" class="logout-btn">Logout</a>
    </div>
    <script src="confetti.js"></script>
</body>
</html>
