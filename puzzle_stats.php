<?php
session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit;
}

require_once "db.php";

echo "<h1>📊 Puzzle Statistics</h1>";
echo "<a href='admin.php'>← Back to Dashboard</a><br><br>";

$sql = "SELECT u.username, 
               COUNT(g.id) AS attempts,
               SUM(CASE WHEN g.completed = 1 THEN 1 ELSE 0 END) AS solved,
               AVG(g.score) AS avg_score,
               MAX(g.played_at) AS last_played
        FROM games g
        JOIN users u ON g.user_id = u.id
        GROUP BY u.username";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // 🛠️ This was the line causing the syntax error
    echo '<table border="1" cellpadding="6">
            <tr>
                <th>Username</th>
                <th>Puzzles Attempted</th>
                <th>Solved</th>
                <th>Average Score</th>
                <th>Last Played</th>
            </tr>';
    while($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['username']}</td>
                <td>{$row['attempts']}</td>
                <td>{$row['solved']}</td>
                <td>" . round($row['avg_score'], 2) . "</td>
                <td>{$row['last_played']}</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "No puzzle data available.";
}

$conn->close();
?>
