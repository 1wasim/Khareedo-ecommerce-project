<?php
session_start();
if (!isset($_SESSION['admin_username'])) {
    header('Location: admin_login.php');
    exit;
}
include 'config.php';

$order_id = $_GET['id'];

// Delete order items first to maintain referential integrity
$query = $conn->prepare("DELETE FROM order_items WHERE order_id = ?");
$query->bind_param("i", $order_id);
$query->execute();

// Delete the order
$query = $conn->prepare("DELETE FROM orders WHERE id = ?");
$query->bind_param("i", $order_id);
if ($query->execute()) {
    header('Location: admin_orders.php');
    exit;
} else {
    echo "Error: " . $conn->error;
}
?>
