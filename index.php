<?php 
session_start(); 
$isLoggedIn = isset($_SESSION['username']); 
$username = $isLoggedIn ? ucfirst($_SESSION['username']) : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Khareedo</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        /* Add custom styles for dropdown menu */
        .dropdown-menu {
            left: 0;
            right: auto;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <?= $isLoggedIn ? "Welcome to Khareedo  ,$username" : "Khareedo" ?>
        </a> <!-- Link to index.php -->
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
<!-- Rest of the page content -->
<header class="banner d-flex align-items-center justify-content-center text-center text-white">
    <video autoplay loop muted playsinline class="background-video">
        <source src="videos/banner-video.mp4" type="video/mp4">
        Your browser does not support the video tag.
    </video>
    <div class="banner-content">
        <h1>Find Your Perfect Appliance</h1>
        <p>Modern, Reliable, and Quality Home Appliances</p>
        <a href="products.php" class="btn btn-primary">Shop Now</a>
    </div>
</header>
<section id="products" class="container py-5">
    <h2 class="text-center mb-4">Featured Products</h2>
    <div class="row" id="product-list">
        <?php
        // Assume $featuredProducts is an array with featured products
        $featuredProducts = [
            ["id" => 7, "name" => "Air Conditioner", "price" => 35000, "image" => "images/ac.jpeg"],
            ["id" => 1, "name" => "Washing Machine", "price" => 20000, "image" => "images/washing_machine.jpeg"],
            ["id" => 8, "name" => "Refrigerator", "price" => 25000, "image" => "images/fridge.webp"],
            // Add more sample products here...
        ];
        foreach ($featuredProducts as $product): ?>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card">
                    <a href="product_detail.php?id=<?= $product['id'] ?>">
                        <img src="<?= $product['image'] ?>" class="card-img-top" alt="<?= $product['name'] ?>" style="height: 200px; object-fit: cover;">
                    </a>
                    <div class="card-body text-center">
                        <h5 class="card-title"><?= $product['name'] ?></h5>
                        <p class="card-text">Rs. <?= number_format($product['price'], 2) ?></p>
                        <a href="product_detail.php?id=<?= $product['id'] ?>" class="btn btn-primary">View Product</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    // Ensure Bootstrap dropdown works
    $(document).ready(function() {
        $('.dropdown-submenu a.dropdown-toggle').on('click', function(e) {
            $(this).next('ul').toggle();
            e.stopPropagation();
            e.preventDefault();
        });
    });
</script>
</body>
</html>
