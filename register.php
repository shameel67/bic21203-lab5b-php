<?php
require_once "db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $matric = trim($_POST["matric"] ?? "");
    $name = trim($_POST["name"] ?? "");
    $rawPassword = $_POST["password"] ?? "";
    $role = trim($_POST["role"] ?? "");

    if ($matric === "" || $name === "" || $rawPassword === "" || $role === "") {
        $message = "Please fill in all fields and select a role.";
    } else {
        $hashedPassword = password_hash($rawPassword, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (matric, name, password, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $matric, $name, $hashedPassword, $role);

        if ($stmt->execute()) {
            $message = "Registration successful! You can now <a href='login.php'>login here</a>.";
        } else {
            if ($conn->errno === 1062) {
                $message = "Error: Matric number already exists.";
            } else {
                $message = "Error registering user: " . htmlspecialchars($stmt->error);
            }
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register User</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; }
        .form-box { border: 1px solid #333; padding: 15px; width: 360px; }
        .row { margin-bottom: 10px; }
        label { display: inline-block; width: 95px; }
        .msg { margin-top: 12px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="form-box">
        <form method="POST" action="">
            <div class="row">
                <label for="matric">Matric:</label>
                <input type="text" id="matric" name="matric" maxlength="10" required>
            </div>
            <div class="row">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" maxlength="100" required>
            </div>
            <div class="row">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="row">
                <label for="role">Role:</label>
                <select id="role" name="role" required>
                    <option value="">Please select</option>
                    <option value="student">student</option>
                    <option value="lecturer">lecturer</option>
                </select>
            </div>
            <button type="submit">Submit</button>
        </form>
        <?php if ($message !== ""): ?>
            <div class="msg"><?php echo $message; ?></div>
        <?php endif; ?>
    </div>
</body>
</html>