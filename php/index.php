<?php
// Start session before any output
session_start();
include 'db.php';

// Add error handling for database connection
try {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $usernameInput = $_POST["username"];
        $passwordInput = $_POST["password"];

        $stmt = $pdo->prepare("SELECT id, username, password FROM users WHERE username = ?");
        $stmt->execute([$usernameInput]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($passwordInput, $user['password'])) {
            $_SESSION["username"] = $user['username'];
            header("Location: welcome.php");
            exit;
        } else {
            $error = "Invalid credentials.";
        }
    }
} catch(PDOException $e) {
    $error = "Connection failed: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login | PHP App</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <?php if(isset($error)): ?>
            <p class='error'><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <p>Don't have an account? <a href="register.php">Sign up</a></p>
    </div>
</body>
</html>
