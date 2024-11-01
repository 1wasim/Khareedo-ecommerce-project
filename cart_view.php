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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Cart</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">Khareedo</a> <!-- Link to index.php -->
        </div>
    </nav>
    <div class="container mt-5">
        <h1>My Cart</h1>
        <div class="row">
            <?php if (!empty($cart_items)): ?>
                <?php foreach ($cart_items as $item): ?>
                    <div class="col-md-3">
                        <div class="card">
                            <img src="<?= $item['image'] ?>" class="card-img-top" alt="<?= $item['name'] ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?= $item['name'] ?></h5>
                                <p class="card-text">Rs. <?= $item['price'] ?> x <span class="item-quantity"><?= $item['quantity'] ?></span></p>
                                <p class="card-text">Total: Rs. <span class="item-total"><?= $item['price'] * $item['quantity'] ?></span></p>
                                <?php $total_amount += $item['price'] * $item['quantity']; ?>
                                <form class="update-cart-form" data-product-id="<?= $item['product_id'] ?>" method="POST">
                                    <input type="number" name="quantity" value="<?= $item['quantity'] ?>" min="0" class="form-control mb-2">
                                    <button type="submit" class="btn btn-secondary">Update Quantity</button>
                                </form>
                                <a href="remove_from_cart.php?product_id=<?= $item['product_id'] ?>" class="btn btn-danger">Remove</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                <div class="col-md-12">
                    <h3 class="text-right">Total Amount: Rs. <?= $total_amount ?></h3>
                    <a href="checkout.php" class="btn btn-primary">Proceed to Checkout</a>
                </div>
            <?php else: ?>
                <p>Your cart is empty.</p>
            <?php endif; ?>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.update-cart-form').on('submit', function(e) {
                e.preventDefault();
                const productId = $(this).data('product-id');
                const quantity = $(this).find('input[name="quantity"]').val();
                
                $.ajax({
                    url: 'update_cart.php',
                    method: 'POST',
                    data: {
                        product_id: productId,
                        quantity: quantity
                    },
                    success: function(response) {
                        location.reload();
                    },
                    error: function() {
                        alert('Failed to update item quantity. Please try again.');
                    }
                });
            });
        });
    </script>
</body>
</html>
