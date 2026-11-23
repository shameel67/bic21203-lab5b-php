<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["matric"]) || $_SESSION["matric"] === "") {
    header("Location: login.php");
    exit;
}
?>