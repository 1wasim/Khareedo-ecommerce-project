<?php
session_start();
if (!isset($_SESSION['admin_username'])) {
    header('Location: admin_login.php');
    exit;
}
include 'config.php';

$category_id = $_GET['id'];

$query = $conn->prepare("DELETE FROM categories WHERE id = ?");
$query->bind_param("i", $category_id);
if ($query->execute()) {
    header('Location: admin_categories.php');
    exit;
} else {
    echo "Error: " . $conn->error;
}
?>
