<?php
session_start();
$isLoggedIn = isset($_SESSION['username']);
include 'config.php';

// Get the selected category from the URL, if it exists
$categoryFilter = isset($_GET['category']) ? $_GET['category'] : '';
$minPrice = isset($_GET['min_price']) ? $_GET['min_price'] : 0;
$maxPrice = isset($_GET['max_price']) ? $_GET['max_price'] : 1000000;
$order = isset($_GET['order']) ? $_GET['order'] : '';

// Fetch products from the database
$query = "SELECT products.id, products.name, products.price, products.image, categories.name AS category 
          FROM products 
          JOIN categories ON products.category_id = categories.id";

$params = [];
$types = '';
if ($categoryFilter) {
    $query .= " WHERE categories.name = ?";
    $params[] = $categoryFilter;
    $types .= 's';
}

if ($minPrice || $maxPrice) {
    $query .= $categoryFilter ? " AND" : " WHERE";
    $query .= " products.price BETWEEN ? AND ?";
    $params[] = $minPrice;
    $params[] = $maxPrice;
    $types .= 'ii';
}

if ($order) {
    $query .= " ORDER BY products.price " . ($order === 'asc' ? 'ASC' : 'DESC');
}

$stmt = $conn->prepare($query);
if ($types) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
$products = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>Products</title>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="index.php">Khareedo.com</a> <!-- Link to index.php -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex flex-column align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fa-solid fa-list"></i>
                    <div style="font-size: 13px;">Categories</div>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="products.php?category=cleaning">Cleaning</a></li>
                        <li><a class="dropdown-item" href="products.php?category=cooling">Cooling</a></li>
                        <li><a class="dropdown-item" href="products.php?category=cooking">Cooking</a></li>
                        <li><a class="dropdown-item" href="products.php?category=other">Other</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li class="dropdown-submenu">
                            <a class="dropdown-item dropdown-toggle" href="#">Filter by Price</a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="products.php?filter=price&order=asc">Ascending Order</a></li>
                                <li><a class="dropdown-item" href="products.php?filter=price&order=desc">Descending Order</a></li>
                                <li class="dropdown-item">
                                    <form action="products.php" method="GET" class="p-3">
                                        <div class="mb-3">
                                            <label for="min_price" class="form-label">Min Price</label>
                                            <input type="number" id="min_price" name="min_price" min="0" class="form-control">
                                        </div>
                                        <div class="mb-3">
                                            <label for="max_price" class="form-label">Max Price</label>
                                            <input type="number" id="max_price" name="max_price" min="0" class="form-control">
                                        </div>
                                        <button type="submit" class="btn btn-primary">Apply</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link d-flex flex-column align-items-center" href="about.php">
                    <i class="fa-solid fa-address-card"></i>
                    <div style="font-size: 13px;">About Us</div>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link d-flex flex-column align-items-center" href="profile.php">
                    <i class="fa-solid fa-user"></i>
                    <div style="font-size: 13px;">Profile</div>
                    </a>
                </li>
                <?php if ($isLoggedIn): ?>
                    <li class="nav-item">
                        <a class="nav-link d-flex flex-column align-items-center" href="logout.php">
                        <i class="fa-solid fa-arrow-left"></i>
                           <div style="font-size: 13px;">Logout</div>
                        </a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link d-flex flex-column align-items-center" href="cart_view.php"> 
                        <i class="fas fa-shopping-cart"></i> 
                        <div style="font-size: 13px;">Cart</div> 
                    </a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link d-flex flex-column align-items-center" href="login.php">
                        <i class="fa-solid fa-right-to-bracket"></i>
                        <div style="font-size: 13px;">Login</div>
                    </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex flex-column align-items-center" href="signup.php">
                        <i class="fa-solid fa-user-plus"></i>
                        <div style="font-size: 13px;">Sign Up</div>
                    </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
<div class="container mt-5">
    <h1>Products</h1>
    <div class="row">
    <?php foreach ($products as $product): ?>
        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
            <div class="card">
                <a href="product_detail.php?id=<?= $product['id'] ?>">
                    <img src="<?= $product['image'] ?>" class="card-img-top" alt="<?= $product['name'] ?>">
                </a>
                <div class="card-body">
                    <h5 class="card-title"><?= $product['name'] ?></h5>
                    <p class="card-text">Rs. <?= $product['price'] ?></p>
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
    <?php endforeach; ?>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script>
    // Ensure Bootstrap dropdown works
    $(document).ready(function() {
        $('.dropdown-submenu a.dropdown-toggle').on('click', function(e) {
            $(this).next('ul').toggle();
            e.stopPropagation();
            e.preventDefault();
        });

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
