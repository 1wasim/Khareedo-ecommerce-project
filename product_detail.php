<?php
session_start();
$isLoggedIn = isset($_SESSION['username']);
include 'config.php';

$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch product details
$query = $conn->prepare("SELECT products.id, products.name, products.price, products.description, categories.name AS category FROM products JOIN categories ON products.category_id = categories.id WHERE products.id = ?");
$query->bind_param("i", $product_id);
$query->execute();
$result = $query->get_result();
$product = $result->fetch_assoc();

// Fetch product images
$image_query = $conn->prepare("SELECT image_url FROM product_images WHERE product_id = ?");
$image_query->bind_param("i", $product_id);
$image_query->execute();
$image_result = $image_query->get_result();
$images = $image_result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>Product Details</title>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="index.php">Khareedo.com</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <!-- Navbar content -->
            </ul>
        </div>
    </div>
</nav>
<div class="container mt-5">
    <div class="row">
        <div class="col-md-6">
            <div id="productCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <?php foreach ($images as $index => $image): ?>
                        <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                            <img src="<?= $image['image_url'] ?>" class="d-block w-100" alt="<?= $product['name'] ?>">
                        </div>
                    <?php endforeach; ?>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>
        <div class="col-md-6">
            <h1><?= $product['name'] ?></h1>
            <p class="text-muted"><?= $product['category'] ?></p>
            <h4 class="text-primary">Rs. <?= $product['price'] ?></h4>
            <pre><?= $product['description'] ?></pre>
            <?php if ($isLoggedIn): ?>
                <form class="add-to-cart-form" data-product-id="<?= $product['id'] ?>" method="POST">
                    <input type="number" name="quantity" value="1" min="1" class="form-control mb-2">
                    <button type="submit" class="btn btn-primary">Add to Cart</button>
                </form>
            <?php else: ?>
                <a href="login.php" class="btn btn-secondary">Login to Purchase</a>
            <?php endif; ?>
        </div>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script>
    // Ensure Bootstrap dropdown works
    $(document).ready(function() {
        $('.add-to-cart-form').on('submit', function(e) {
            e.preventDefault();
            const productId = $(this).data('product-id');
            const quantity = $(this).find('input[name="quantity"]').val();
            
            $.ajax({
                url: 'cart.php',
                method: 'POST',
                data: {
                    product_id: productId,
                    quantity: quantity
                },
                success: function(response) {
                    alert('Item added to cart!');
                },
                error: function() {
                    alert('Failed to add item to cart. Please try again.');
                }
            });
        });
    });
</script>
</body>
</html>
