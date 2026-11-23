<?php
session_start();
if (!isset($_SESSION["matric"])) {
    header("Location: login.php");
    exit;
}

require_once "db.php";

$matricParam = $_GET["matric"] ?? "";
if ($matricParam === "") {
    header("Location: users.php");
    exit;
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $newMatric = trim($_POST["matric"] ?? "");
    $name = trim($_POST["name"] ?? "");
    $accessLevel = trim($_POST["access_level"] ?? "");

    if ($newMatric === "" || $name === "" || $accessLevel === "") {
        $error = "All fields are required.";
    } else {
        $stmt = $conn->prepare("UPDATE users SET matric = ?, name = ?, role = ? WHERE matric = ?");
        $stmt->bind_param("ssss", $newMatric, $name, $accessLevel, $matricParam);

        if ($stmt->execute()) {
            if ($_SESSION["matric"] === $matricParam) {
                $_SESSION["matric"] = $newMatric;
            }
            header("Location: users.php");
            exit;
        } else {
            $error = "Failed to update user: " . htmlspecialchars($stmt->error);
        }
        $stmt->close();
    }
}

$stmt = $conn->prepare("SELECT matric, name, role FROM users WHERE matric = ?");
$stmt->bind_param("s", $matricParam);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user) {
    header("Location: users.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update User</title>
    <style>
        body { font-family: "Times New Roman", Times, serif; margin: 30px; }
        h1 { margin-bottom: 16px; }
        .row { margin-bottom: 10px; }
        label { display: inline-block; width: 105px; }
        a { margin-left: 8px; }
    </style>
</head>
<body>
    <h1>Update User</h1>

    <?php if ($error !== ""): ?>
        <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="row">
            <label for="matric">Matric</label>
            <input type="text" id="matric" name="matric" maxlength="10" value="<?php 
            echo htmlspecialchars($user["matric"]); ?>" required>
        </div>
        <div class="row">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" maxlength="100" value="<?php 
            echo htmlspecialchars($user["name"]); ?>" required>
        </div>
        <div class="row">
            <label for="access_level">Access Level</label>
            <input type="text" id="access_level" name="access_level" maxlength="10" value="<?php 
            echo htmlspecialchars($user["role"]); ?>" required>
        </div>
        <button type="submit">Update</button>
        <a href="users.php">Cancel</a>
    </form>
</body>
</html>