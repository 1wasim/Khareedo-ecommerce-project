<?php
session_start();
include 'config.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Fetch the user's ID
$username = $_SESSION['username'];
$query = $conn->prepare("SELECT id FROM users WHERE username = ?");
$query->bind_param("s", $username);
$query->execute();
$user_result = $query->get_result();
$user = $user_result->fetch_assoc();
$user_id = $user['id'];

// Fetch or create the user's cart
$query = $conn->prepare("SELECT id FROM carts WHERE user_id = ?");
$query->bind_param("i", $user_id);
$query->execute();
$cart_result = $query->get_result();
$cart = $cart_result->fetch_assoc();

if (!$cart) {
    $query = $conn->prepare("INSERT INTO carts (user_id) VALUES (?)");
    $query->bind_param("i", $user_id);
    $query->execute();
    $cart_id = $query->insert_id;
} else {
    $cart_id = $cart['id'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    $query = $conn->prepare(
        "INSERT INTO cart_items (cart_id, product_id, quantity) VALUES (?, ?, ?)
         ON DUPLICATE KEY UPDATE quantity = quantity + VALUES(quantity)"
    );
    $query->bind_param("iii", $cart_id, $product_id, $quantity);
    $query->execute();
}

header('Location: cart_view.php');
exit;
?>
