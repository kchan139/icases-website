<div class="breadcrumb">
    <div class="container">
        <a href="/">Home</a> /
        <a href="/products">Products</a> /
        <?php if ($product["category_slug"]): ?>
            <a href="/products?category=<?= htmlspecialchars(
                $product["category_slug"],
            ) ?>">
                <?= htmlspecialchars($product["category_name"]) ?>
            </a> /
        <?php endif; ?>
        <span><?= htmlspecialchars($product["name"]) ?></span>
    </div>
</div>

<section class="product-detail">
    <div class="container">
        <div class="product-detail-grid">
            <div class="product-detail-image">
                <img src="/assets<?= htmlspecialchars($product["image_url"]) ?>"
                     alt="<?= htmlspecialchars($product["name"]) ?>"
                     onerror="this.src='/assets/images/placeholder.jpg'">
            </div>

            <div class="product-detail-info">
                <h1><?= htmlspecialchars($product["name"]) ?></h1>
                <p class="product-detail-model"><?= htmlspecialchars($product["iphone_model"]) ?></p>
                <p class="product-detail-price"><?= number_format($product["price"], 0, ",", ".") ?>₫</p>

                <?php if ($product["description"]): ?>
                    <div class="product-description">
                        <h3>Description</h3>
                        <p><?= htmlspecialchars($product["description"]) ?></p>
                    </div>
                <?php endif; ?>

                <div class="product-specs">
                    <h3>Specifications</h3>
                    <ul>
                        <?php if ($product["material"]): ?>
                            <li><strong>Material:</strong> <?= htmlspecialchars($product["material"]) ?></li>
                        <?php endif; ?>
                        <?php if ($product["color"]): ?>
                            <li><strong>Color:</strong> <?= htmlspecialchars($product["color"]) ?></li>
                        <?php endif; ?>
                        <li><strong>Model:</strong> <?= htmlspecialchars($product["iphone_model"]) ?></li>
                    </ul>
                </div>

                <div class="product-actions">
                    <form method="POST" action="/cart/add" style="display: inline;">
                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                        <input type="hidden" name="redirect" value="<?= $_SERVER['REQUEST_URI'] ?>">
                        <button type="submit" name="action" value="add" class="btn btn-secondary">Add to Cart</button>
                        <button type="submit" name="action" value="buy" class="btn">Buy Now</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="store-availability">
            <h2>Store Availability</h2>
            <?php if (empty($stores)): ?>
                <p class="no-availability">Currently not available in stores</p>
            <?php else: ?>
                <div class="stores-grid">
                    <?php foreach ($stores as $store): ?>
                        <div class="store-card">
                            <h3><?= htmlspecialchars($store["name"]) ?></h3>
                            <p class="store-address"><?= htmlspecialchars(
                                $store["address"],
                            ) ?></p>
                            <p class="store-city"><?= htmlspecialchars(
                                $store["city"],
                            ) ?></p>
                            <?php if ($store["phone"]): ?>
                                <p class="store-phone">📞 <?= htmlspecialchars(
                                    $store["phone"],
                                ) ?></p>
                            <?php endif; ?>
                            <p class="store-quantity">
                                <?php if ($store["quantity"] > 0): ?>
                                    <span class="in-stock">✓ In Stock (<?= $store[
                                        "quantity"
                                    ] ?> available)</span>
                                <?php else: ?>
                                    <span class="out-of-stock">Out of Stock</span>
                                <?php endif; ?>
                            </p>
                            <?php if (
                                $store["latitude"] &&
                                $store["longitude"]
                            ): ?>
                                <a href="https://www.google.com/maps?q=<?= $store[
                                    "latitude"
                                ] ?>,<?= $store["longitude"] ?>"
                                   target="_blank"
                                   class="btn-map">
                                    📍 View on Google Maps
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
