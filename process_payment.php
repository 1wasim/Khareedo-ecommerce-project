<?php
session_start();
include 'config.php';
require 'vendor/autoload.php';

use Razorpay\Api\Api;

$api = new Api('YOUR_RAZORPAY_KEY_ID', 'YOUR_RAZORPAY_SECRET');

// Fetch the total amount from the form
$total_amount = $_POST['total_amount'] * 100; // Convert to paise

// Create a new order in Razorpay
$order = $api->order->create([
    'receipt' => 'order_rcptid_11',
    'amount' => $total_amount,
    'currency' => 'INR'
]);

// Save order details to session
$_SESSION['razorpay_order_id'] = $order['id'];
$_SESSION['total_amount'] = $_POST['total_amount'];

// Redirect to Razorpay payment page
header('Location: confirm_order.php');
exit;
?>
