<?php
session_start();
include 'config.php';

if (!isset($_SESSION['username']) || !isset($_SESSION['razorpay_order_id'])) {
    header('Location: login.php');
    exit;
}

// Payment verification and order update logic

// Clear session variables
unset($_SESSION['razorpay_order_id']);
unset($_SESSION['total_amount']);

// Redirect to order success page
header('Location: success.php');
exit;
?>
