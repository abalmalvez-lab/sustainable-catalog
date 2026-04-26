<?php include __DIR__ . '/../layouts/header.php'; ?>

<section class="catalog-section">
    <div class="container">
        <!-- Catalog Header -->
        <div class="catalog-header">
            <div>
                <h1 class="catalog-title"><i class="fas fa-store"></i> Product Catalog</h1>
                <p class="catalog-subtitle">
                    Showing <?= $result['total'] ?> sustainable product<?= $result['total'] != 1 ? 's' : '' ?>
                    <?php if (!empty($filters['search'])): ?>
                        for "<strong><?= e($filters['search']) ?></strong>"
                    <?php endif; ?>
                    <?php if (!empty($filters['category'])):
                        foreach ($categories as $c) {
                            if ($c['slug'] === $filters['category']) {
                                echo ' in <strong>' . e($c['name']) . '</strong>';
                                break;
                            }
                        }
                    endif; ?>
                </p>
            </div>
            <button class="btn btn-outline btn-sm filter-toggle-btn" id="filterToggle">
                <i class="fas fa-sliders-h"></i> Filters
            </button>
        </div>

        <div class="catalog-layout">
            <!-- Filter Sidebar -->
            <aside class="filter-sidebar" id="filterSidebar">
                <form action="index.php" method="GET" class="filter-form" id="filterForm">
                    <input type="hidden" name="page" value="catalog">

                    <!-- Search -->
                    <div class="filter-group">
                        <label class="filter-label"><i class="fas fa-search"></i> Search</label>
                        <input type="text" name="search" class="form-control" placeholder="Search products..."
                               value="<?= e($filters['search']) ?>">
                    </div>

                    <!-- Category -->
                    <div class="filter-group">
                        <label class="filter-label"><i class="fas fa-th-large"></i> Category</label>
                        <select name="category" class="form-control">
                            <option value="">All Categories</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= e($cat['slug']) ?>"
                                    <?= $filters['category'] === $cat['slug'] ? 'selected' : '' ?>>
                                    <?= e($cat['name']) ?> (<?= $cat['product_count'] ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Sustainability Rating -->
                    <div class="filter-group">
                        <label class="filter-label"><i class="fas fa-leaf"></i> Min. Sustainability</label>
                        <div class="rating-filter">
                            <?php for ($r = 1; $r <= 5; $r++): ?>
                                <label class="rating-option <?= (int)$filters['min_rating'] === $r ? 'active' : '' ?>">
                                    <input type="radio" name="min_rating" value="<?= $r ?>"
                                        <?= (int)$filters['min_rating'] === $r ? 'checked' : '' ?>>
                                    <span><?= $r ?> <i class="fas fa-leaf"></i></span>
                                </label>
                            <?php endfor; ?>
                            <label class="rating-option <?= empty($filters['min_rating']) ? 'active' : '' ?>">
                                <input type="radio" name="min_rating" value="" <?= empty($filters['min_rating']) ? 'checked' : '' ?>>
                                <span>Any</span>
                            </label>
                        </div>
                    </div>

                    <!-- Price Range -->
                    <div class="filter-group">
                        <label class="filter-label"><i class="fas fa-dollar-sign"></i> Price Range</label>
                        <div class="price-range">
                            <input type="number" name="min_price" class="form-control form-control-sm" placeholder="Min"
                                   min="0" step="0.01" value="<?= e($filters['min_price']) ?>">
                            <span class="price-sep">—</span>
                            <input type="number" name="max_price" class="form-control form-control-sm" placeholder="Max"
                                   min="0" step="0.01" value="<?= e($filters['max_price']) ?>">
                        </div>
                    </div>

                    <!-- Certification -->
                    <div class="filter-group">
                        <label class="filter-label"><i class="fas fa-certificate"></i> Certification</label>
                        <select name="certification" class="form-control">
                            <option value="">Any Certification</option>
                            <?php foreach ($certifications as $cert): ?>
                                <option value="<?= e($cert) ?>"
                                    <?= ($filters['certification'] ?? '') === $cert ? 'selected' : '' ?>>
                                    <?= e($cert) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Stock Status -->
                    <div class="filter-group">
                        <label class="filter-label"><i class="fas fa-warehouse"></i> Availability</label>
                        <select name="stock_status" class="form-control">
                            <option value="">Any</option>
                            <option value="in_stock" <?= $filters['stock_status'] === 'in_stock' ? 'selected' : '' ?>>In Stock</option>
                            <option value="low_stock" <?= $filters['stock_status'] === 'low_stock' ? 'selected' : '' ?>>Low Stock</option>
                        </select>
                    </div>

                    <!-- Sort -->
                    <div class="filter-group">
                        <label class="filter-label"><i class="fas fa-sort"></i> Sort By</label>
                        <select name="sort" class="form-control">
                            <option value="rating_desc" <?= $filters['sort'] === 'rating_desc' ? 'selected' : '' ?>>Highest Rated</option>
                            <option value="rating_asc" <?= $filters['sort'] === 'rating_asc' ? 'selected' : '' ?>>Lowest Rated</option>
                            <option value="price_asc" <?= $filters['sort'] === 'price_asc' ? 'selected' : '' ?>>Price: Low to High</option>
                            <option value="price_desc" <?= $filters['sort'] === 'price_desc' ? 'selected' : '' ?>>Price: High to Low</option>
                            <option value="name_asc" <?= $filters['sort'] === 'name_asc' ? 'selected' : '' ?>>Name: A-Z</option>
                            <option value="newest" <?= $filters['sort'] === 'newest' ? 'selected' : '' ?>>Newest First</option>
                        </select>
                    </div>

                    <div class="filter-actions">
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-search"></i> Apply Filters
                        </button>
                        <a href="index.php?page=catalog" class="btn btn-outline btn-block">
                            <i class="fas fa-times"></i> Clear All
                        </a>
                    </div>
                </form>
            </aside>

            <!-- Product Grid -->
            <div class="catalog-content">
                <?php if (empty($result['products'])): ?>
                    <div class="empty-state">
                        <div class="empty-icon"><i class="fas fa-search"></i></div>
                        <h3>No products found</h3>
                        <p>Try adjusting your filters or search terms.</p>
                        <a href="index.php?page=catalog" class="btn btn-primary">
                            <i class="fas fa-redo"></i> Reset Filters
                        </a>
                    </div>
                <?php else: ?>
                    <div class="product-grid">
                        <?php foreach ($result['products'] as $prod): ?>
                        <a href="index.php?page=detail&slug=<?= e($prod['slug']) ?>" class="product-card">
                            <div class="product-image">
                                <?php if (!empty($prod['image_url'])): ?>
                                    <img src="<?= e($prod['image_url']) ?>" alt="<?= e($prod['name']) ?>" class="product-img" loading="lazy"
                                         onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                                    <div class="product-image-placeholder" style="display:none">
                                        <i class="fas <?= e($prod['category_icon']) ?>"></i>
                                    </div>
                                <?php else: ?>
                                    <div class="product-image-placeholder">
                                        <i class="fas <?= e($prod['category_icon']) ?>"></i>
                                    </div>
                                <?php endif; ?>
                                <?php if ($prod['is_featured']): ?>
                                    <span class="product-badge badge-featured"><i class="fas fa-star"></i> Featured</span>
                                <?php endif; ?>
                                <span class="product-badge badge-rating">
                                    <?= renderLeaves($prod['sustainability_rating']) ?>
                                </span>
                            </div>
                            <div class="product-info">
                                <span class="product-category">
                                    <i class="fas <?= e($prod['category_icon']) ?>"></i>
                                    <?= e($prod['category_name']) ?>
                                </span>
                                <h3 class="product-name"><?= e($prod['name']) ?></h3>
                                <p class="product-excerpt"><?= e(mb_substr($prod['description'], 0, 90)) ?>...</p>
                                <div class="product-meta-tags">
                                    <?php if ($prod['carbon_footprint']): ?>
                                        <span class="meta-tag"><i class="fas fa-cloud"></i> <?= e($prod['carbon_footprint']) ?></span>
                                    <?php endif; ?>
                                    <?php if ($prod['brand']): ?>
                                        <span class="meta-tag"><i class="fas fa-tag"></i> <?= e($prod['brand']) ?></span>
                                    <?php endif; ?>
                                </div>
                                <div class="product-footer">
                                    <span class="product-price">$<?= number_format($prod['price'], 2) ?></span>
                                    <?= renderStockBadge($prod['stock_status']) ?>
                                </div>
                            </div>
                        </a>
                        <?php endforeach; ?>
                    </div>

                    <!-- Pagination -->
                    <?php if ($result['total_pages'] > 1): ?>
                    <nav class="pagination">
                        <?php
                        $baseUrl = 'index.php?page=catalog';
                        foreach ($filters as $k => $v) {
                            if ($k !== 'page' && $v !== '') {
                                $baseUrl .= '&' . $k . '=' . urlencode($v);
                            }
                        }
                        ?>
                        <?php if ($result['page'] > 1): ?>
                            <a href="<?= $baseUrl ?>&pg=<?= $result['page'] - 1 ?>" class="page-link">
                                <i class="fas fa-chevron-left"></i> Prev
                            </a>
                        <?php endif; ?>

                        <?php for ($p = 1; $p <= $result['total_pages']; $p++): ?>
                            <a href="<?= $baseUrl ?>&pg=<?= $p ?>"
                               class="page-link <?= $p === $result['page'] ? 'active' : '' ?>">
                                <?= $p ?>
                            </a>
                        <?php endfor; ?>

                        <?php if ($result['page'] < $result['total_pages']): ?>
                            <a href="<?= $baseUrl ?>&pg=<?= $result['page'] + 1 ?>" class="page-link">
                                Next <i class="fas fa-chevron-right"></i>
                            </a>
                        <?php endif; ?>
                    </nav>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
