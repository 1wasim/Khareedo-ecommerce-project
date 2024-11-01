<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

include 'config.php';

$username = $_SESSION['username'];

$query = $conn->prepare("DELETE FROM users WHERE username = ?");
$query->bind_param("s", $username);
if ($query->execute()) {
    session_destroy();
    header('Location: index.php');
    exit;
} else {
    echo "Error: " . $conn->error;
}
?>
