<?php
// save_score.php
header("Content-Type: application/json"); // tell the browser this is JSON

$conn = new mysqli("localhost", "fgodifey1", "fgodifey1", "fgodifey1");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = json_decode(file_get_contents("php://input"), true);

    if (!$data || !isset($data["name"]) || !isset($data["score"])) {
        echo json_encode(["status" => "error", "message" => "Invalid input data"]);
        exit;
    }

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
} else {
    // Block other methods (like GET)
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Method not allowed"]);
}
?>
