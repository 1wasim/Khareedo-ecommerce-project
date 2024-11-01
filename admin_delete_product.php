<?php
session_start();
if (!isset($_SESSION['admin_username'])) {
    header('Location: admin_login.php');
    exit;
}
include 'config.php';

$product_id = $_GET['id'];

$query = $conn->prepare("DELETE FROM products WHERE id = ?");
$query->bind_param("i", $product_id);
if ($query->execute()) {
    header('Location: admin_products.php');
    exit;
} else {
    echo "Error: " . $conn->error;
}
?>
