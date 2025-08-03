<?php
// Retrieve top puzzle scores from the database
$conn = new mysqli('localhost', 'fgodifey1', 'fgodifey1', 'fgodifey1');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$result = $conn->query("SELECT player_name, score FROM puzzle_scores ORDER BY score DESC LIMIT 10");

echo "<h2>Leaderboard:</h2>";
while ($row = $result->fetch_assoc()) {
    echo htmlspecialchars($row['player_name']) . ": " . $row['score'] . "<br>";
}

$conn->close();
?>
