<?php
session_start();
if (!isset($_SESSION['admin_username'])) {
    header('Location: admin_login.php');
    exit;
}
include 'config.php';

$user_id = $_GET['id'];

$query = $conn->prepare("DELETE FROM users WHERE id = ?");
$query->bind_param("i", $user_id);
if ($query->execute()) {
    header('Location: admin_users.php');
    exit;
} else {
    echo "Error: " . $conn->error;
}
?>
