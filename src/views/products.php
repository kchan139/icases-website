<div class="breadcrumb">
    <div class="container">
        <a href="/">Home</a> / <span>Products</span>
        <?php if (isset($category) && $category): ?>
            / <span><?= htmlspecialchars($category) ?></span>
        <?php endif; ?>
    </div>
</div>

<section class="products-section">
    <div class="container">
        <div class="products-header">
            <h1>All Products</h1>
            <div class="products-controls">
                <div class="filter-group">
                    <select id="categoryFilter" onchange="applyFilters()">
                        <option value="">All Categories</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= htmlspecialchars($cat['slug']) ?>" 
                                <?= isset($category) && $category === $cat['slug'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="filter-group">
                    <select id="modelFilter" onchange="applyFilters()">
                        <option value="">All Models</option>
                        <?php foreach ($iphoneModels as $m): ?>
                            <option value="<?= htmlspecialchars($m['iphone_model']) ?>"
                                <?= isset($model) && $model === $m['iphone_model'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($m['iphone_model']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="filter-group">
                    <select id="sortFilter" onchange="applyFilters()">
                        <option value="newest" <?= $sort === 'newest' ? 'selected' : '' ?>>Newest</option>
                        <option value="price-asc" <?= $sort === 'price-asc' ? 'selected' : '' ?>>Price: Low to High</option>
                        <option value="price-desc" <?= $sort === 'price-desc' ? 'selected' : '' ?>>Price: High to Low</option>
                        <option value="name" <?= $sort === 'name' ? 'selected' : '' ?>>Name A-Z</option>
                    </select>
                </div>
            </div>
        </div>

        <?php if (empty($products)): ?>
            <p class="no-products">No products found.</p>
        <?php else: ?>
            <div class="product-grid">
                <?php foreach ($products as $product): ?>
                    <div class="product-card">
                        <div class="product-image">
                            <img src="/assets<?= htmlspecialchars($product['image_url']) ?>" 
                                 alt="<?= htmlspecialchars($product['name']) ?>"
                                 onerror="this.src='/assets/images/placeholder.jpg'">
                        </div>
                        <div class="product-info">
                            <h3><?= htmlspecialchars($product['name']) ?></h3>
                            <p class="product-model"><?= htmlspecialchars($product['iphone_model']) ?></p>
                            <p class="product-price"><?= number_format($product['price'], 0, ',', '.') ?>đ</p>
                            <a href="/product/<?= htmlspecialchars($product['slug']) ?>" class="btn-small">View Details</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php if ($totalPages > 1): ?>
                <div class="pagination">
                    <?php if ($pageNum > 1): ?>
                        <a href="/products?page=<?= $pageNum - 1 ?>&sort=<?= $sort ?><?= $category ? '&category='.$category : '' ?><?= $model ? '&model='.urlencode($model) : '' ?>" class="page-link">← Previous</a>
                    <?php endif; ?>
                    
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <?php if ($i === $pageNum): ?>
                            <span class="page-link active"><?= $i ?></span>
                        <?php else: ?>
                            <a href="/products?page=<?= $i ?>&sort=<?= $sort ?><?= $category ? '&category='.$category : '' ?><?= $model ? '&model='.urlencode($model) : '' ?>" class="page-link"><?= $i ?></a>
                        <?php endif; ?>
                    <?php endfor; ?>
                    
                    <?php if ($pageNum < $totalPages): ?>
                        <a href="/products?page=<?= $pageNum + 1 ?>&sort=<?= $sort ?><?= $category ? '&category='.$category : '' ?><?= $model ? '&model='.urlencode($model) : '' ?>" class="page-link">Next →</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</section>

<script>
    function applyFilters() {
        const category = document.getElementById('categoryFilter').value;
        const model = document.getElementById('modelFilter').value;
        const sort = document.getElementById('sortFilter').value;
        
        let url = '/products?page=1';
        if (sort) url += '&sort=' + sort;
        if (category) url += '&category=' + category;
        if (model) url += '&model=' + encodeURIComponent(model);
        
        window.location.href = url;
    }
</script>
