<?php
session_start();
if (!isset($_SESSION["matric"])) {
    header("Location: login.php");
    exit;
}

require_once "db.php";

$matric = $_GET["matric"] ?? "";

if ($matric !== "") {
    $stmt = $conn->prepare("DELETE FROM users WHERE matric = ?");
    $stmt->bind_param("s", $matric);
    $stmt->execute();
    $stmt->close();
}

header("Location: users.php");
exit;