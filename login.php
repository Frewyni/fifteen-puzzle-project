<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set("display_errors", 1);

session_start();

// Database connection
$conn = new mysqli('localhost', 'fgodifey1', 'fgodifey1', 'fgodifey1');

// Check connection
if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    // Prepare statement
    $stmt = $conn->prepare("SELECT id, password, role, is_active FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($id, $hashedPassword, $role, $is_active);
    $stmt->fetch();
    $stmt->close();

    // Check login credentials
    if (!$is_active) {
        $error = "❌ Account is deactivated.";
    } elseif ($hashedPassword && password_verify($password, $hashedPassword)) {
        // Set session
        $_SESSION["user_id"] = $id;
        $_SESSION["username"] = $username;
        $_SESSION["role"] = $role;

        // Redirect based on role
        if ($role === 'admin') {
            header("Location: admin.php");
            exit;
        } else {
            header("Location: player.php");
            exit;
        }
    } else {
        $error = "❌ Invalid login.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>

    <!-- Show error if exists -->
    <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>

    <form method="POST" action="login.php">
        <input type="text" name="username" placeholder="Username" required><br><br>
        <input type="password" name="password" placeholder="Password" required><br><br>
        <button type="submit">Login</button>
    </form>
</body>
</html>
