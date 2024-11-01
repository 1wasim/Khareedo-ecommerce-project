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

// Fetch the cart items
$query = $conn->prepare(
    "SELECT cart_items.product_id, cart_items.quantity, products.name, products.price, products.image
     FROM cart_items
     JOIN carts ON cart_items.cart_id = carts.id
     JOIN products ON cart_items.product_id = products.id
     WHERE carts.user_id = ?"
);
$query->bind_param("i", $user_id);
$query->execute();
$cart_items_result = $query->get_result();
$cart_items = $cart_items_result->fetch_all(MYSQLI_ASSOC);

$total_amount = 0;
foreach ($cart_items as $item) {
    $total_amount += $item['price'] * $item['quantity'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">Khareedo</a> <!-- Link to index.php -->
        </div>
    </nav>
    <div class="container mt-5">
        <h1>Checkout</h1>
        <p>Total Amount: Rs. <?= $total_amount ?></p>
        <form id="checkout-form" action="process_payment.php" method="POST">
            <div class="mb-3">
                <label for="address" class="form-label">Shipping Address</label>
                <input type="text" class="form-control" id="address" name="address" required>
            </div>
            <div class="mb-3">
                <label for="city" class="form-label">City</label>
                <input type="text" class="form-control" id="city" name="city" required>
            </div>
            <div class="mb-3">
                <label for="state" class="form-label">State</label>
                <input type="text" class="form-control" id="state" name="state" required>
            </div>
            <div class="mb-3">
                <label for="zip" class="form-label">Zip Code</label>
                <input type="text" class="form-control" id="zip" name="zip" required>
            </div>
            <input type="hidden" name="total_amount" value="<?= $total_amount ?>">
            <button type="submit" class="btn btn-primary">Proceed to Payment</button>
        </form>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
