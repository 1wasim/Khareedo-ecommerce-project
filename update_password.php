<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

include 'config.php';

$username = $_SESSION['username'];
$current_password = $_POST['current_password'];
$new_password = $_POST['new_password'];
$confirm_password = $_POST['confirm_password'];

// Fetch the current password hash from the database
$query = $conn->prepare("SELECT password FROM users WHERE username = ?");
$query->bind_param("s", $username);
$query->execute();
$result = $query->get_result();
$user = $result->fetch_assoc();

if (password_verify($current_password, $user['password'])) {
    if ($new_password === $confirm_password) {
        $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);
        $update_query = $conn->prepare("UPDATE users SET password = ? WHERE username = ?");
        $update_query->bind_param("ss", $new_password_hash, $username);
        if ($update_query->execute()) {
            header('Location: profile.php');
            exit;
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        echo "New passwords do not match.";
    }
} else {
    echo "Current password is incorrect.";
}
?>
