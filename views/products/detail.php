<?php include __DIR__ . '/../layouts/header.php'; ?>

<section class="detail-section">
    <div class="container">
        <!-- Breadcrumb -->
        <nav class="breadcrumb">
            <a href="index.php"><i class="fas fa-home"></i> Home</a>
            <i class="fas fa-chevron-right"></i>
            <a href="index.php?page=catalog">Catalog</a>
            <i class="fas fa-chevron-right"></i>
            <a href="index.php?page=catalog&category=<?= e($product['category_slug']) ?>"><?= e($product['category_name']) ?></a>
            <i class="fas fa-chevron-right"></i>
            <span><?= e($product['name']) ?></span>
        </nav>

        <!-- Product Detail Card -->
        <div class="detail-card">
            <div class="detail-grid">
                <!-- Product Image -->
                <div class="detail-image">
                    <?php if (!empty($product['image_url'])): ?>
                        <img src="<?= e(str_replace(['w=600', 'h=400'], ['w=800', 'h=600'], $product['image_url'])) ?>" alt="<?= e($product['name']) ?>" class="detail-img" loading="lazy"
                             onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                        <div class="detail-image-placeholder" style="display:none">
                            <i class="fas <?= e($product['category_icon']) ?>"></i>
                        </div>
                    <?php else: ?>
                        <div class="detail-image-placeholder">
                            <i class="fas <?= e($product['category_icon']) ?>"></i>
                        </div>
                    <?php endif; ?>
                    <div class="detail-badges">
                        <?php if ($product['is_featured']): ?>
                            <span class="product-badge badge-featured"><i class="fas fa-star"></i> Featured</span>
                        <?php endif; ?>
                        <?= renderStockBadge($product['stock_status']) ?>
                    </div>
                </div>

                <!-- Product Info -->
                <div class="detail-info">
                    <span class="product-category">
                        <i class="fas <?= e($product['category_icon']) ?>"></i>
                        <?= e($product['category_name']) ?>
                    </span>

                    <h1 class="detail-title"><?= e($product['name']) ?></h1>

                    <?php if ($product['brand']): ?>
                        <p class="detail-brand">by <strong><?= e($product['brand']) ?></strong></p>
                    <?php endif; ?>

                    <div class="detail-rating-row">
                        <div class="detail-sustainability">
                            <span class="detail-rating-label">Sustainability</span>
                            <?= renderLeaves($product['sustainability_rating']) ?>
                            <span class="rating-number"><?= $product['sustainability_rating'] ?>/5</span>
                        </div>
                        <?php if ($ratingInfo['review_count'] > 0): ?>
                        <div class="detail-user-rating">
                            <span class="detail-rating-label">User Rating</span>
                            <span class="stars-display">
                                <?php
                                $avg = round($ratingInfo['avg_rating'], 1);
                                for ($s = 1; $s <= 5; $s++):
                                    echo $s <= round($avg) ? '<i class="fas fa-star"></i>' : '<i class="far fa-star"></i>';
                                endfor;
                                ?>
                            </span>
                            <span class="rating-number"><?= $avg ?> (<?= $ratingInfo['review_count'] ?> review<?= $ratingInfo['review_count'] != 1 ? 's' : '' ?>)</span>
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="detail-price">$<?= number_format($product['price'], 2) ?></div>

                    <p class="detail-description"><?= nl2br(e($product['description'])) ?></p>

                    <!-- Product Attributes -->
                    <div class="detail-attributes">
                        <?php if ($product['carbon_footprint']): ?>
                        <div class="attr-item">
                            <div class="attr-icon"><i class="fas fa-cloud"></i></div>
                            <div>
                                <span class="attr-label">Carbon Footprint</span>
                                <span class="attr-value"><?= e($product['carbon_footprint']) ?></span>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php if ($product['materials']): ?>
                        <div class="attr-item">
                            <div class="attr-icon"><i class="fas fa-cubes"></i></div>
                            <div>
                                <span class="attr-label">Materials</span>
                                <span class="attr-value"><?= e($product['materials']) ?></span>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php if ($product['certifications']): ?>
                        <div class="attr-item">
                            <div class="attr-icon"><i class="fas fa-certificate"></i></div>
                            <div>
                                <span class="attr-label">Certifications</span>
                                <div class="cert-tags">
                                    <?php foreach (explode(',', $product['certifications']) as $cert): ?>
                                        <span class="cert-tag"><?= e(trim($cert)) ?></span>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reviews Section -->
        <div class="reviews-section">
            <h2 class="section-title"><i class="fas fa-comments"></i> Reviews</h2>

            <?php if (isLoggedIn()): ?>
                <!-- Review Form -->
                <div class="review-form-card">
                    <h3>Leave a Review</h3>
                    <form action="index.php?page=review" method="POST" class="review-form">
                        <?= csrf_field() ?>
                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">

                        <div class="form-group">
                            <label>Your Rating</label>
                            <div class="star-input" id="starInput">
                                <?php for ($s = 1; $s <= 5; $s++): ?>
                                    <label class="star-label">
                                        <input type="radio" name="rating" value="<?= $s ?>" required>
                                        <i class="far fa-star" data-star="<?= $s ?>"></i>
                                    </label>
                                <?php endfor; ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="comment">Your Comment</label>
                            <textarea id="comment" name="comment" class="form-control" rows="3"
                                      placeholder="Share your experience with this product..."></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane"></i> Submit Review
                        </button>
                    </form>
                </div>
            <?php else: ?>
                <div class="review-login-prompt">
                    <i class="fas fa-sign-in-alt"></i>
                    <a href="index.php?page=login">Sign in</a> to leave a review.
                </div>
            <?php endif; ?>

            <!-- Reviews List -->
            <?php if (!empty($reviews)): ?>
                <div class="reviews-list">
                    <?php foreach ($reviews as $review): ?>
                    <div class="review-card">
                        <div class="review-header">
                            <div class="review-author">
                                <span class="avatar-circle avatar-sm" style="background-color: <?= e($review['avatar_color']) ?>">
                                    <?= getInitials($review['full_name']) ?>
                                </span>
                                <div>
                                    <strong><?= e($review['full_name']) ?></strong>
                                    <small><?= timeAgo($review['created_at']) ?></small>
                                </div>
                            </div>
                            <div class="review-stars">
                                <?php for ($s = 1; $s <= 5; $s++):
                                    echo $s <= $review['rating'] ? '<i class="fas fa-star"></i>' : '<i class="far fa-star"></i>';
                                endfor; ?>
                            </div>
                        </div>
                        <?php if ($review['comment']): ?>
                            <p class="review-text"><?= nl2br(e($review['comment'])) ?></p>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="empty-state empty-state-sm">
                    <i class="fas fa-comment-slash"></i>
                    <p>No reviews yet. Be the first to review this product!</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Related Products -->
        <?php if (!empty($related)): ?>
        <div class="related-section">
            <h2 class="section-title"><i class="fas fa-link"></i> Related Products</h2>
            <div class="product-grid product-grid-sm">
                <?php foreach ($related as $rel): ?>
                <a href="index.php?page=detail&slug=<?= e($rel['slug']) ?>" class="product-card">
                    <div class="product-image product-image-sm">
                        <?php if (!empty($rel['image_url'])): ?>
                            <img src="<?= e($rel['image_url']) ?>" alt="<?= e($rel['name']) ?>" class="product-img" loading="lazy"
                                 onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                            <div class="product-image-placeholder" style="display:none">
                                <i class="fas <?= e($rel['category_slug'] ? $product['category_icon'] : 'fa-leaf') ?>"></i>
                            </div>
                        <?php else: ?>
                            <div class="product-image-placeholder">
                                <i class="fas <?= e($rel['category_slug'] ? $product['category_icon'] : 'fa-leaf') ?>"></i>
                            </div>
                        <?php endif; ?>
                        <span class="product-badge badge-rating">
                            <?= renderLeaves($rel['sustainability_rating']) ?>
                        </span>
                    </div>
                    <div class="product-info">
                        <h3 class="product-name"><?= e($rel['name']) ?></h3>
                        <div class="product-footer">
                            <span class="product-price">$<?= number_format($rel['price'], 2) ?></span>
                        </div>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
