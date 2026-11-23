<?php
session_start();
require_once "db.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $matric = trim($_POST["matric"] ?? "");
    $password = $_POST["password"] ?? "";

    $stmt = $conn->prepare("SELECT matric, name, password, role FROM users WHERE matric = ?");
    $stmt->bind_param("s", $matric);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $isValid = password_verify($password, $row["password"]) || $password === $row["password"];
        if ($isValid) {
            $_SESSION["matric"] = $row["matric"];
            $_SESSION["name"] = $row["name"];
            $_SESSION["role"] = $row["role"];
            header("Location: users.php");
            exit;
        }
    }

    $error = "Invalid username or password, try login again.";
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; }
        .form-box { border: 1px solid #333; padding: 15px; width: 340px; }
        .row { margin-bottom: 10px; }
        label { display: inline-block; width: 85px; }
        .error { color: #b00020; margin-top: 10px; }
        .register-link { margin-top: 18px; }
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
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Login</button>
        </form>

        <?php if ($error !== ""): ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <p class="register-link">
            <a href="register.php">Register</a> here if you have not.
        </p>
    </div>
</body>
</html>