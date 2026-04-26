<?php include __DIR__ . '/../layouts/header.php'; ?>

<section class="auth-section">
    <div class="container">
        <div class="auth-wrapper">
            <div class="auth-illustration">
                <div class="auth-illus-content">
                    <div class="auth-illus-icon">
                        <i class="fas fa-globe-americas"></i>
                    </div>
                    <h2>Welcome Back</h2>
                    <p>Sign in to continue exploring eco-friendly products and making sustainable choices for our planet.</p>
                    <div class="auth-stats-row">
                        <div class="auth-stat">
                            <i class="fas fa-recycle"></i>
                            <span>Reduce Waste</span>
                        </div>
                        <div class="auth-stat">
                            <i class="fas fa-hand-holding-heart"></i>
                            <span>Fair Trade</span>
                        </div>
                        <div class="auth-stat">
                            <i class="fas fa-tree"></i>
                            <span>Save Forests</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="auth-form-panel">
                <div class="auth-form-header">
                    <h1>Sign In</h1>
                    <p>Access your sustainable account</p>
                </div>

                <form action="index.php?page=login&action=submit" method="POST" class="auth-form">
                    <?= csrf_field() ?>

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
                                placeholder="Enter your password"
                                required
                            >
                            <button type="button" class="input-icon-btn toggle-password" data-target="password">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block btn-lg">
                        <i class="fas fa-sign-in-alt"></i> Sign In
                    </button>
                </form>

                <div class="auth-footer">
                    <p>Don't have an account? <a href="index.php?page=register">Create one now</a></p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
