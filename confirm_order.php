<?php
session_start();
include 'config.php';

if (!isset($_SESSION['username']) || !isset($_SESSION['razorpay_order_id'])) {
    header('Location: login.php');
    exit;
}

// Fetch order details from session
$order_id = $_SESSION['razorpay_order_id'];
$total_amount = $_SESSION['total_amount'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Confirmation</title>
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
        <h1>Order Confirmation</h1>
        <p>Order ID: <?= $order_id ?></p>
        <p>Total Amount: Rs. <?= $total_amount ?></p>
        <form action="payment_complete.php" method="POST">
            <script
                src="https://checkout.razorpay.com/v1/checkout.js"
                data-key="YOUR_RAZORPAY_KEY_ID"
                data-amount="<?= $total_amount * 100 ?>"
                data-currency="INR"
                data-order_id="<?= $order_id ?>"
                data-buttontext="Pay with Razorpay"
                data-name="Khareedo"
                data-description="Order Payment"
                data-image="https://your-logo-url.com"
                data-prefill.name="<?= $_SESSION['username'] ?>"
                data-prefill.email="user@example.com"
                data-prefill.contact="9999999999"
                data-theme.color="#F37254"
            ></script>
            <input type="hidden" custom="Hidden Element" name="hidden">
        </form>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
