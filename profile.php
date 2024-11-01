<?php
session_start();
$isLoggedIn = isset($_SESSION['username']);
if (!$isLoggedIn) {
    header('Location: login.php');
    exit;
}
include 'config.php';
$username = $_SESSION['username'];

$query = $conn->prepare("SELECT username, email, phone, address FROM users WHERE username = ?");
$query->bind_param("s", $username);
$query->execute();
$result = $query->get_result();
$user = $result->fetch_assoc();

$username = $user['username'];
$email = $user['email'];
$phone = $user['phone'];
$address = $user['address'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Khareedo</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        .dropdown-menu {
            left: 0;
            right: auto;
        }
    </style>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            Khareedo.com
        </a>
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
    <h1 class="text-center">Your Profile</h1>
</div>
<div class="container mt-3">
    <div class="card mx-auto" style="max-width: 600px;">
        <div class="card-body">
            <h5 class="card-title">Profile Details</h5>
            <form action="update_profile.php" method="POST">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control editable" id="username" name="username" value="<?= $username ?>" readonly>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control editable" id="email" name="email" value="<?= $email ?>" readonly>
                </div>
                <div class="mb-3">
                    <label for="phone" class="form-label">Phone</label>
                    <input type="text" class="form-control editable" id="phone" name="phone" value="<?= $phone ?>" readonly>
                </div>
                <div class="mb-3">
                    <label for="address" class="form-label">Address</label>
                    <input type="text" class="form-control editable" id="address" name="address" value="<?= $address ?>" readonly>
                </div>
                <button type="button" id="editProfile" class="btn btn-primary" onclick="enableEditMode()">Edit Profile</button>
                <button type="submit" id="saveChanges" class="btn btn-success" style="display: none;">Save Changes</button>
            </form>
        
            <div class="mt-4">
    <form action="change_password.php" method="POST">
        <button type="submit" class="btn btn-warning">Change Password</button>
    </form>
</div>   

<div class="mt-4">
    <form action="delete_account.php" method="POST" onsubmit="return confirm('Are you sure you want to delete your account? This action cannot be undone.');">
        <button type="submit" class="btn btn-danger">Delete Account</button>
    </form>
    </div>

    </div>
</div>
</div>
<script>
    function enableEditMode() {
        document.querySelectorAll('.editable').forEach(el => el.removeAttribute('readonly'));
        document.getElementById('saveChanges').style.display = 'block';
        document.getElementById('editProfile').style.display = 'none';
    }
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</body>
</html>

