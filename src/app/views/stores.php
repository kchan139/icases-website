<div class="breadcrumb">
    <div class="container">
        <a href="/">Home</a> / <span>Stores</span>
    </div>
</div>

<section class="stores-section">
    <div class="container">
        <h1>Our Store Locations</h1>

        <?php if (empty($stores)): ?>
            <p class="no-stores">No stores available</p>
        <?php else: ?>
        <div class="stores-grid">
            <?php foreach ($stores as $store): ?>
                <div class="store-card">
                    <?php if ($store["image_url"]): ?>
                        <div class="store-image">
                            <img src="/assets<?= htmlspecialchars(
                                $store["image_url"],
                            ) ?>"
                                 alt="<?= htmlspecialchars($store["name"]) ?>"
                                 onerror="this.src='/assets/images/placeholder.jpg'">
                        </div>
                    <?php endif; ?>
                    <div class="store-content">
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
                        <?php if ($store["latitude"] && $store["longitude"]): ?>
                            <a href="https://www.google.com/maps?q=<?= $store[
                                "latitude"
                            ] ?>,<?= $store["longitude"] ?>"
                               target="_blank"
                               class="btn-map">
                                📍 View on Google Maps
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>
