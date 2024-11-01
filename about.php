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
<div class="containerr mt-5" style="background-image: url('images/background.jpg'); background-size: cover; padding: 50px; border-radius: 10px; box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);">
    <h1 class="text-center text-white">About Us</h1>
    <p class="text-center text-white">Welcome to Khareedo, your number one source for all things home appliances. We're dedicated to giving you the very best of products, with a focus on dependability, customer service, and uniqueness.</p>
    <h2 class="text-center text-white mt-4">Our Story</h2>
    <p class="text-white">Founded in 2023, Khareedo has come a long way from its beginnings in a home office. When we first started out, our passion for eco-friendly cleaning products drove us to do tons of research so that Khareedo can offer you the world's most advanced home appliances. We now serve customers all over the country and are thrilled that we're able to turn our passion into our own website.</p>
    <h2 class="text-center text-white mt-4">Our Mission</h2>
    <p class="text-white">We believe passionately in great bargains and excellent service, which is why we commit ourselves to giving you the best of both.</p>
    <h2 class="text-center text-white mt-4">Our Team</h2>
    <div class="row justify-content-center">
        <div class="col-md-4 text-center">
            <div class="team-member">
                <img src="images/wasim.jpg" class="rounded-circle mb-3" alt="Wasim Akram" style="width: 150px; height: 150px;">
                <h5 class="text-primary">Wasim Akram</h5>
                <p class="text-white" style="font-style: italic;">CEO & Founder</p>
            </div>
        </div>
    </div>
    <div class="row justify-content-center mt-4">
        <div class="col-md-3 text-center">
            <div class="team-member">
                <img src="images/sumit1.jpg" class="rounded-circle mb-3" alt="Sumit Dutta" style="width: 150px; height: 150px;">
                <h5 class="text-primary">Sumit Dutta</h5>
                <p class="text-white" style="font-style: italic;">Chief Operating Officer</p>
            </div>
        </div>
        <div class="col-md-3 text-center">
            <div class="team-member">
                <img src="images/sudip1.jpg" class="rounded-circle mb-3" alt="Sudip Dutta" style="width: 150px; height: 150px;">
                <h5 class="text-primary">Sudip Dutta</h5>
                <p class="text-white" style="font-style: italic;">Head of Marketing</p>
            </div>
        </div>
    </div>
    <div class="row justify-content-center mt-4">
        <div class="col-md-3 text-center">
            <div class="team-member">
                <img src="images/amir1.jpg" class="rounded-circle mb-3" alt="Amir Mallick" style="width: 150px; height: 150px;">
                <h5 class="text-primary">Amir Mallick</h5>
                <p class="text-white" style="font-style: italic;">Chief Financial Officer</p>
            </div>
        </div>
        <div class="col-md-3 text-center">
            <div class="team-member">
                <img src="images/arko.png" class="rounded-circle mb-3" alt="Arko Biswas" style="width: 150px; height: 150px;">
                <h5 class="text-primary">Arko Biswas</h5>
                <p class="text-white" style="font-style: italic;">Head of Customer Support</p>
            </div>
        </div>
    </div>
</div>


<style>
    .team-member img {
        transition: transform 0.3s, box-shadow 0.3s;
    }
    .team-member img:hover {
        transform: scale(1.1);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
    }
    .team-member h5 {
        color: #ffc107;
    }
    .team-member p {
        font-style: italic;
        color: #e0e0e0;
    }
    .containerr h1, .container h2 {
        color: #f8f9fa;
    }
    .containerr p {
        font-size: 1.1em;
        color: #e0e0e0;
    }
    .containerr {
        background-color: rgba(0, 0, 0, 0.7);
    }
    .team-member img {
        transition: transform 0.3s, box-shadow 0.3s;
    }
    .team-member img:hover {
        transform: scale(1.1);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
    }
    .team-member h5 {
        color: #ffc107;
    }
    .team-member p {
        font-style: italic;
        color: #e0e0e0;
    }
    .containerr h1, .container h2 {
        color: #f8f9fa;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
    }
    .containerr p {
        font-size: 1.1em;
        color: #e0e0e0;
    }
    .containerr {
        background-color: rgba(0, 0, 0, 0.7);
        border-radius: 10px;
        padding: 50px;
    }
    
</style>


<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</body>
</html>
