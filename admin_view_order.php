<?php
session_start();
if (!isset($_SESSION['admin_username'])) {
    header('Location: admin_login.php');
    exit;
}
include 'config.php';

$order_id = $_GET['id'];

// Fetch order details
$query = $conn->prepare("SELECT orders.id, users.username, orders.total, orders.created_at FROM orders JOIN users ON orders.user_id = users.id WHERE orders.id = ?");
$query->bind_param("i", $order_id);
$query->execute();
$result = $query->get_result();
$order = $result->fetch_assoc();

// Fetch order items
$query = $conn->prepare("SELECT order_items.product_id, order_items.quantity, order_items.price, products.name FROM order_items JOIN products ON order_items.product_id = products.id WHERE order_items.order_id = ?");
$query->bind_param("i", $order_id);
$query->execute();
$result = $query->get_result();
$order_items = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Order</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="admin_dashboard.php">Admin Panel</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="admin_products.php">Manage Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin_categories.php">Manage Categories</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin_users.php">Manage Users</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin_orders.php">Manage Orders</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin_logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-5">
        <h1>View Order</h1>
        <h3>Order Details</h3>
        <p>Order ID: <?= $order['id'] ?></p>
        <p>Username: <?= $order['username'] ?></p>
        <p>Total: Rs. <?= $order['total'] ?></p>
        <p>Date: <?= $order['created_at'] ?></p>
        <h3>Order Items</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($order_items as $item): ?>
                    <tr>
                        <td><?= $item['name'] ?></td>
                        <td><?= $item['quantity'] ?></td>
                        <td>Rs. <?= $item['price'] ?></td>
                        <td>Rs. <?= $item['price'] * $item['quantity'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a href="admin_orders.php" class="btn btn-primary">Back to Orders</a>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
