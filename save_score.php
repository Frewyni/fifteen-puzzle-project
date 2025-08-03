<?php
// save_score.php
$conn = new mysqli("localhost", "fgodifey1", "fgodifey1", "fgodifey1");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = json_decode(file_get_contents("php://input"), true);

    $player_name = $data["name"];
    $score = intval($data["score"]);

    $stmt = $conn->prepare("INSERT INTO puzzle_scores (player_name, score, played_at) VALUES (?, ?, NOW())");
    $stmt->bind_param("si", $player_name, $score);
    
    if ($stmt->execute()) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => $stmt->error]);
    }

    $stmt->close();
}
?>
