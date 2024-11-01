<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

include 'config.php';

$username = $_SESSION['username'];
$new_username = (trim($_POST['username']));
$email = trim($_POST['email']);
$phone = trim($_POST['phone']);
$address = trim($_POST['address']);

$query = $conn->prepare("UPDATE users SET username = ?, email = ?, phone = ?, address = ? WHERE username = ?");
$query->bind_param("sssss", $new_username, $email, $phone, $address, $username);
if ($query->execute()) {
    $_SESSION['username'] = $new_username;
    header('Location: profile.php');
    exit;
} else {
    echo "Error: " . $conn->error;
}
?>
