<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION["username"]) || $_SESSION["role"] !== "player") {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Welcome Player</title>
</head>
<body>
    <h2>🎮 Welcome, <?php echo htmlspecialchars($_SESSION["username"]); ?>!</h2>
    <p>This is your player dashboard.</p>

    <p><a href="puzzle.html">▶️ Play the Fifteen Puzzle</a></p>
    <p><a href="leaderboard.php">🏆 View Leaderboard</a></p>
    <p><a href="logout.php">🚪 Logout</a></p>
</body>
</html>
