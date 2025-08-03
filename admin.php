<?php
session_start();

// Redirect if not logged in or not admin
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit;
}

$conn = new mysqli('localhost', 'fgodifey1', 'fgodifey1', 'fgodifey1');

// Handle activate/deactivate action
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $action = $_GET['action'] === 'deactivate' ? 0 : 1;

    $stmt = $conn->prepare("UPDATE users SET is_active = ? WHERE id = ?");
    $stmt->bind_param("ii", $action, $id);
    $stmt->execute();
    $stmt->close();
}

// Fetch all users
$result = $conn->query("SELECT id, username, role, is_active, created_at FROM users ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="container">
    <h2>👑 Admin Dashboard</h2>
    <p>Welcome, <?php echo $_SESSION["username"]; ?>!</p>
    <a href="logout.php">Logout</a>
    <p><a href="puzzle_stats.php">📊 View Puzzle Statistics</a></p>


    <h3>Registered Users</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Role</th>
            <th>Status</th>
            <th>Registered</th>
            <th>Action</th>
        </tr>

        <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $row["id"]; ?></td>
            <td><?php echo htmlspecialchars($row["username"]); ?></td>
            <td><?php echo $row["role"]; ?></td>
            <td><?php echo $row["is_active"] ? "Active" : "Inactive"; ?></td>
            <td><?php echo $row["created_at"]; ?></td>
            <td>
                <?php if ($row["is_active"]) { ?>
                    <a href="admin.php?action=deactivate&id=<?php echo $row["id"]; ?>">Deactivate</a>
                <?php } else { ?>
                    <a href="admin.php?action=activate&id=<?php echo $row["id"]; ?>">Activate</a>
                <?php } ?>
            </td>
        </tr>
        <?php } ?>

    </table>
</div>
</body>
</html>
