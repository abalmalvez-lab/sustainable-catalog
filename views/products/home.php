<?php include __DIR__ . '/../layouts/header.php'; ?>

<!-- Hero Section -->
<section class="hero">
    <div class="hero-bg-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
    </div>
    <div class="container">
        <div class="hero-content">
            <span class="hero-badge"><i class="fas fa-leaf"></i> Sustainable Living Made Simple</span>
            <h1>Shop Products That <span class="text-accent">Protect</span> Our Planet</h1>
            <p class="hero-subtitle">
                Discover a curated collection of eco-friendly products rated for sustainability.
                Every choice you make helps build a greener future.
            </p>
            <div class="hero-actions">
                <a href="index.php?page=catalog" class="btn btn-primary btn-lg">
                    <i class="fas fa-store"></i> Browse Catalog
                </a>
                <?php if (!isLoggedIn()): ?>
                <a href="index.php?page=register" class="btn btn-outline btn-lg">
                    <i class="fas fa-user-plus"></i> Join Free
                </a>
                <?php endif; ?>
            </div>
            <div class="hero-trust">
                <div class="trust-item">
                    <i class="fas fa-check-circle"></i>
                    <span><?= $stats['total_products'] ?>+ Products</span>
                </div>
                <div class="trust-item">
                    <i class="fas fa-check-circle"></i>
                    <span><?= $stats['brands'] ?>+ Eco Brands</span>
                </div>
                <div class="trust-item">
                    <i class="fas fa-check-circle"></i>
                    <span><?= $stats['categories'] ?> Categories</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Banner -->
<section class="stats-banner">
    <div class="container">
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-box-open"></i></div>
                <div class="stat-number"><?= $stats['total_products'] ?>+</div>
                <div class="stat-label">Eco Products</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-leaf"></i></div>
                <div class="stat-number"><?= $stats['high_rated'] ?></div>
                <div class="stat-label">Highly Rated</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-tags"></i></div>
                <div class="stat-number"><?= $stats['brands'] ?></div>
                <div class="stat-label">Green Brands</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-th-large"></i></div>
                <div class="stat-number"><?= $stats['categories'] ?></div>
                <div class="stat-label">Categories</div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Products -->
<?php if (!empty($featured)): ?>
<section class="section" id="featured">
    <div class="container">
        <div class="section-header">
            <span class="section-badge"><i class="fas fa-star"></i> Hand-Picked</span>
            <h2 class="section-title">Featured Sustainable Products</h2>
            <p class="section-subtitle">Our top picks for eco-conscious living, rated by sustainability impact.</p>
        </div>
        <div class="product-grid">
            <?php foreach ($featured as $prod): ?>
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
                    <p class="product-excerpt"><?= e(mb_substr($prod['description'], 0, 100)) ?>...</p>
                    <div class="product-footer">
                        <span class="product-price">$<?= number_format($prod['price'], 2) ?></span>
                        <?= renderStockBadge($prod['stock_status']) ?>
                    </div>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
        <div class="section-cta">
            <a href="index.php?page=catalog" class="btn btn-primary btn-lg">
                View All Products <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Categories Grid -->
<section class="section section-alt">
    <div class="container">
        <div class="section-header">
            <span class="section-badge"><i class="fas fa-th-large"></i> Explore</span>
            <h2 class="section-title">Shop by Category</h2>
            <p class="section-subtitle">Find sustainable alternatives in every area of your life.</p>
        </div>
        <div class="category-grid">
            <?php foreach ($categories as $cat): ?>
            <a href="index.php?page=catalog&category=<?= e($cat['slug']) ?>" class="category-card">
                <div class="category-icon">
                    <i class="fas <?= e($cat['icon']) ?>"></i>
                </div>
                <h3><?= e($cat['name']) ?></h3>
                <span class="category-count"><?= $cat['product_count'] ?> product<?= $cat['product_count'] != 1 ? 's' : '' ?></span>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Why Sustainable CTA -->
<section class="section cta-section">
    <div class="container">
        <div class="cta-card">
            <div class="cta-content">
                <h2><i class="fas fa-hand-holding-heart"></i> Why Shop Sustainable?</h2>
                <div class="cta-features">
                    <div class="cta-feature">
                        <div class="cta-feature-icon"><i class="fas fa-globe-americas"></i></div>
                        <h3>Reduce Carbon Footprint</h3>
                        <p>Every product includes carbon footprint data so you can make informed choices.</p>
                    </div>
                    <div class="cta-feature">
                        <div class="cta-feature-icon"><i class="fas fa-recycle"></i></div>
                        <h3>Less Waste, More Life</h3>
                        <p>Reusable and biodegradable products that keep waste out of landfills and oceans.</p>
                    </div>
                    <div class="cta-feature">
                        <div class="cta-feature-icon"><i class="fas fa-certificate"></i></div>
                        <h3>Certified & Verified</h3>
                        <p>Products with trusted certifications like FSC, Fair Trade, GOTS, and B Corp.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
