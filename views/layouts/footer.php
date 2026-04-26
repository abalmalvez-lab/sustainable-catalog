</main>

<!-- Footer -->
<footer class="footer">
    <div class="container">
        <div class="footer-grid">
            <div class="footer-brand">
                <div class="nav-brand">
                    <span class="brand-icon"><i class="fas fa-leaf"></i></span>
                    <span class="brand-text"><?= APP_NAME ?></span>
                </div>
                <p class="footer-desc">Promoting eco-friendly consumer habits through a curated catalog of sustainable products. Every purchase makes a difference.</p>
                <div class="footer-social">
                    <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                </div>
            </div>
            <div class="footer-links">
                <h4>Quick Links</h4>
                <a href="index.php">Home</a>
                <a href="index.php?page=catalog">Catalog</a>
                <a href="index.php?page=catalog&min_rating=5">Top Rated</a>
            </div>
            <div class="footer-links">
                <h4>Categories</h4>
                <a href="index.php?page=catalog&category=kitchen-dining">Kitchen & Dining</a>
                <a href="index.php?page=catalog&category=personal-care">Personal Care</a>
                <a href="index.php?page=catalog&category=fashion-apparel">Fashion & Apparel</a>
                <a href="index.php?page=catalog&category=home-living">Home & Living</a>
            </div>
            <div class="footer-links">
                <h4>Account</h4>
                <?php if (isLoggedIn()): ?>
                    <a href="index.php?page=catalog">Browse Products</a>
                    <a href="index.php?page=logout">Sign Out</a>
                <?php else: ?>
                    <a href="index.php?page=login">Sign In</a>
                    <a href="index.php?page=register">Create Account</a>
                <?php endif; ?>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; <?= date('Y') ?> <?= APP_NAME ?>. Built with <i class="fas fa-heart" style="color: var(--accent)"></i> for the planet.</p>
        </div>
    </div>
</footer>

<script src="public/js/app.js"></script>
</body>
</html>
