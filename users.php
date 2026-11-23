<?php
session_start();
if (!isset($_SESSION["matric"])) {
    header("Location: login.php");
    exit;
}

require_once "db.php";

$sql = "SELECT matric, name, role AS accessLevel FROM users";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Users List</title>
    <style>
        body { font-family: "Times New Roman", Times, serif; margin: 30px; }
        table { border-collapse: collapse; width: 65%; }
        th, td { border: 1px solid #000; padding: 6px 10px; text-align: left; }
        .top-bar { margin-bottom: 14px; font-family: Arial, sans-serif; font-size: 14px; }
    </style>
</head>
<body>
    <div class="top-bar">
        Logged in as: <strong><?php echo htmlspecialchars($_SESSION["matric"]); ?></strong> |
        <a href="register.php">Add User</a> |
        <a href="logout.php">Logout</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>Matric</th>
                <th>Name</th>
                <th>Level</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row["matric"]); ?></td>
                        <td><?php echo htmlspecialchars($row["name"]); ?></td>
                        <td><?php echo htmlspecialchars($row["accessLevel"]); ?></td>
                        <td>
                            <a href="update_user.php?matric=<?php echo urlencode($row["matric"]); ?>">Update</a>
                            <a href="delete_user.php?matric=<?php echo urlencode($row["matric"]); ?>" onclick="
                            return confirm('Delete this user?');">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">No users found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>