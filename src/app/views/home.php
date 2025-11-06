<section class="hero">
    <div class="container">
        <h1>Premium iPhone Cases</h1>
        <p>Protect your device with style</p>
        <a href="/products" class="btn">Shop Now</a>
    </div>
</section>

<section class="featured-products">
    <div class="container">
        <h2>Featured Products</h2>
        <div class="product-grid">
            <?php foreach ($featuredProducts as $product): ?>
                <div class="product-card">
                    <div class="product-image">
                        <img src="/assets<?= htmlspecialchars(
                            $product["image_url"],
                        ) ?>"
                             alt="<?= htmlspecialchars($product["name"]) ?>"
                             onerror="this.src='/assets/images/placeholder.jpg'">
                    </div>
                    <div class="product-info">
                        <h3><?= htmlspecialchars($product["name"]) ?></h3>
                        <p class="product-model"><?= htmlspecialchars(
                            $product["iphone_model"],
                        ) ?></p>
                        <p class="product-price"><?= number_format(
                            $product["price"],
                            0,
                            ",",
                            ".",
                        ) ?>đ</p>
                        <a href="/product/<?= htmlspecialchars(
                            $product["slug"],
                        ) ?>" class="btn-small">View Details</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
