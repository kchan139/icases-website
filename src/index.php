<?php
session_start();
require_once 'config/database.php';
require_once 'models/Product.php';

$db = new Database();
$conn = $db->getConnection();
$productModel = new Product($conn);

// Get featured products
$featuredProducts = $productModel->getFeatured(8);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Premium iPhone cases - Clear, Leather, Silicone, Rugged, and Wallet cases for all iPhone models">
    <meta name="keywords" content="iPhone cases, phone cases, iPhone 15 cases, protective cases">
    <title>iPhone Cases - Premium Protection for Your Device</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header>
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <a href="/">iCases</a>
                </div>
                <nav>
                    <ul>
                        <li><a href="/">Home</a></li>
                        <li><a href="/products.php">Products</a></li>
                        <li><a href="/search.php">Search</a></li>
                        <li><a href="/stores.php">Stores</a></li>
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <li><a href="/logout.php">Logout</a></li>
                        <?php else: ?>
                            <li><a href="/login.php">Login</a></li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <main>
        <section class="hero">
            <div class="container">
                <h1>Premium iPhone Cases</h1>
                <p>Protect your device with style</p>
                <a href="/products.php" class="btn">Shop Now</a>
            </div>
        </section>

        <section class="featured-products">
            <div class="container">
                <h2>Featured Products</h2>
                <div class="product-grid">
                    <?php foreach ($featuredProducts as $product): ?>
                        <div class="product-card">
                            <div class="product-image">
                                <img src="assets<?= htmlspecialchars($product['image_url']) ?>" 
                                     alt="<?= htmlspecialchars($product['name']) ?>"
                                     onerror="this.src='assets/images/placeholder.jpg'">
                            </div>
                            <div class="product-info">
                                <h3><?= htmlspecialchars($product['name']) ?></h3>
                                <p class="product-model"><?= htmlspecialchars($product['iphone_model']) ?></p>
                                <p class="product-price"><?= number_format($product['price'], 0, ',', '.') ?>đ</p>
                                <a href="/product.php?slug=<?= htmlspecialchars($product['slug']) ?>" class="btn-small">View Details</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <div class="container">
            <p>&copy; <?= date('Y') ?> iCases. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
