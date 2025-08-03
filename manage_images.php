<?php
session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit;
}

$conn = new mysqli('localhost', 'fgodifey1', 'fgodifey1', 'fgodifey1');

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["bg_image"])) {
    $upload_dir = "uploads/";
    $filename = basename($_FILES["bg_image"]["name"]);
    $target_path = __DIR__ . "/uploads/" . $filename;



    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }
// echo "<pre>";
// print_r($_FILES["bg_image"]);
// echo "</pre>";


    if (move_uploaded_file($_FILES["bg_image"]["tmp_name"], $target_path)) {
    $web_path = "uploads/" . $filename;
    $stmt = $conn->prepare("INSERT INTO background_images (image_name, file_path) VALUES (?, ?)");
    $stmt->bind_param("ss", $filename, $web_path);
    $stmt->execute();
    echo "✅ Image uploaded!";
    $stmt->close();
} else {
    echo "❌ Upload failed. Could not move to: " . $target_path;
}

}

// Fetch all images
$result = $conn->query("SELECT * FROM background_images ORDER BY uploaded_at DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Backgrounds</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="container">
    <h2>🖼️ Manage Background Images</h2>
    <form method="POST" enctype="multipart/form-data">
        <input type="file" name="bg_image" required>
        <button type="submit">Upload</button>
    </form>

    <h3>Uploaded Images</h3>
    <table>
        <tr>
            <th>Image</th>
            <th>Status</th>
            <th>Uploaded</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><img src="<?php echo $row["file_path"]; ?>" width="100"></td>
            <td><?php echo $row["is_active"] ? "Active" : "Inactive"; ?></td>
            <td><?php echo $row["uploaded_at"]; ?></td>
        </tr>
        <?php } ?>
    </table>

    <br><a href="admin.php">← Back to Dashboard</a>
</div>
</body>
</html>
