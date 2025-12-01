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
        <div class="search-bar-wrapper">
            <input 
                type="text" 
                id="productSearch" 
                placeholder="Search products..." 
                autocomplete="off"
            >
            <div id="searchDropdown" class="search-dropdown"></div>
        </div>
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
    <script>
        let searchTimeout;
        const searchInput = document.getElementById('productSearch');
        const searchDropdown = document.getElementById('searchDropdown');

        searchInput.addEventListener('input', function() {
            const query = this.value.trim();
            clearTimeout(searchTimeout);
    
            if (query.length < 2) {
                searchDropdown.innerHTML = '';
                searchDropdown.classList.remove('active');
                return;
            }
    
            searchTimeout = setTimeout(() => {
                fetch('/api/search.php?q=' + encodeURIComponent(query))
                    .then(response => response.json())
                    .then(data => {
                        if (data.products && data.products.length > 0) {
                            const html = data.products.slice(0, 5).map(product => `
                                <a href="/product/${encodeURIComponent(product.slug)}" class="search-dropdown-item">
                                    <img src="/assets${escapeHtml(product.image_url)}" 
                                         alt="${escapeHtml(product.name)}"
                                         onerror="this.src='/assets/images/placeholder.jpg'">
                                    <div>
                                        <div class="search-name">${escapeHtml(product.name)}</div>
                                        <div class="search-model">${escapeHtml(product.iphone_model)}</div>
                                        <div class="search-price">${formatPrice(product.price)}₫</div>
                                    </div>
                                </a>
                            `).join('');
                            searchDropdown.innerHTML = html;
                            searchDropdown.classList.add('active');
                        } else {
                            searchDropdown.innerHTML = '<div class="no-results">No products found</div>';
                            searchDropdown.classList.add('active');
                        }
                    });
            }, 300);
        });

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        function formatPrice(price) {
            return parseFloat(price).toLocaleString('vi-VN');
        }

        document.addEventListener('click', function(e) {
            if (!searchInput.contains(e.target) && !searchDropdown.contains(e.target)) {
                searchDropdown.classList.remove('active');
            }
        });
    </script>
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
