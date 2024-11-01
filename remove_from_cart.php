<?php
session_start();
include 'config.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

if (isset($_GET['product_id'])) {
    // Fetch the user's ID
    $username = $_SESSION['username'];
    $query = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $query->bind_param("s", $username);
    $query->execute();
    $user_result = $query->get_result();
    $user = $user_result->fetch_assoc();
    $user_id = $user['id'];

    // Fetch the cart ID
    $query = $conn->prepare("SELECT id FROM carts WHERE user_id = ?");
    $query->bind_param("i", $user_id);
    $query->execute();
    $cart_result = $query->get_result();
    $cart = $cart_result->fetch_assoc();
    $cart_id = $cart['id'];

    // Remove the item from the cart
    $product_id = $_GET['product_id'];
    $query = $conn->prepare("DELETE FROM cart_items WHERE cart_id = ? AND product_id = ?");
    $query->bind_param("ii", $cart_id, $product_id);
    $query->execute();
}

header('Location: cart_view.php');
exit;
?>
