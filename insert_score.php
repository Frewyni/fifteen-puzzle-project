<?php

$conn = new mysqli('localhost', 'fgodifey1', 'fgodifey1', 'fgodifey1');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$player_name = $_POST['player_name'];
$score = $_POST['score'];

$stmt = $conn->prepare("INSERT INTO puzzle_scores (player_name, score) VALUES (?, ?)");
$stmt->bind_param("si", $player_name, $score);

if ($stmt->execute()) {
    echo "Score inserted successfully!";
} else {
    echo "Error: " . $conn->error;
}

$stmt->close();
$conn->close();
?>
