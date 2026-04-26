<?php include __DIR__ . '/../layouts/header.php'; ?>

<section class="auth-section">
    <div class="container">
        <div class="auth-wrapper">
            <div class="auth-illustration">
                <div class="auth-illus-content">
                    <div class="auth-illus-icon">
                        <i class="fas fa-seedling"></i>
                    </div>
                    <h2>Join the Green Movement</h2>
                    <p>Create an account to explore sustainable products, leave reviews, and track your eco-friendly journey.</p>
                    <div class="auth-stats-row">
                        <div class="auth-stat">
                            <i class="fas fa-box-open"></i>
                            <span>25+ Products</span>
                        </div>
                        <div class="auth-stat">
                            <i class="fas fa-certificate"></i>
                            <span>Certified Eco</span>
                        </div>
                        <div class="auth-stat">
                            <i class="fas fa-leaf"></i>
                            <span>100% Green</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="auth-form-panel">
                <div class="auth-form-header">
                    <h1>Create Account</h1>
                    <p>Start your sustainable journey today</p>
                </div>

                <form action="index.php?page=register&action=submit" method="POST" class="auth-form" id="registerForm">
                    <?= csrf_field() ?>

                    <div class="form-group">
                        <label for="full_name">
                            <i class="fas fa-user"></i> Full Name
                        </label>
                        <input
                            type="text"
                            id="full_name"
                            name="full_name"
                            class="form-control"
                            placeholder="e.g. Maria Santos"
                            required
                            minlength="2"
                            maxlength="100"
                            value="<?= e($_POST['full_name'] ?? '') ?>"
                        >
                    </div>

                    <div class="form-group">
                        <label for="email">
                            <i class="fas fa-envelope"></i> Email Address
                        </label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            class="form-control"
                            placeholder="you@example.com"
                            required
                            value="<?= e($_POST['email'] ?? '') ?>"
                        >
                    </div>

                    <div class="form-group">
                        <label for="password">
                            <i class="fas fa-lock"></i> Password
                        </label>
                        <div class="input-with-icon">
                            <input
                                type="password"
                                id="password"
                                name="password"
                                class="form-control"
                                placeholder="Min. 8 characters"
                                required
                                minlength="8"
                            >
                            <button type="button" class="input-icon-btn toggle-password" data-target="password">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div class="password-strength" id="passwordStrength">
                            <div class="strength-bar"><div class="strength-fill" id="strengthFill"></div></div>
                            <span class="strength-text" id="strengthText"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password_confirm">
                            <i class="fas fa-lock"></i> Confirm Password
                        </label>
                        <div class="input-with-icon">
                            <input
                                type="password"
                                id="password_confirm"
                                name="password_confirm"
                                class="form-control"
                                placeholder="Re-enter your password"
                                required
                                minlength="8"
                            >
                            <button type="button" class="input-icon-btn toggle-password" data-target="password_confirm">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="checkbox-label">
                            <input type="checkbox" required>
                            <span>I agree to the <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a></span>
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block btn-lg">
                        <i class="fas fa-user-plus"></i> Create Account
                    </button>
                </form>

                <div class="auth-footer">
                    <p>Already have an account? <a href="index.php?page=login">Sign in here</a></p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
