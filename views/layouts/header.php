<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle ?? APP_NAME) ?></title>

    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=Nunito:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- App Stylesheet -->
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body>

<!-- Navigation -->
<nav class="navbar">
    <div class="container nav-container">
        <a href="index.php" class="nav-brand">
            <span class="brand-icon"><i class="fas fa-leaf"></i></span>
            <span class="brand-text"><?= APP_NAME ?></span>
        </a>

        <button class="nav-toggle" id="navToggle" aria-label="Toggle navigation">
            <i class="fas fa-bars"></i>
        </button>

        <div class="nav-menu" id="navMenu">
            <a href="index.php" class="nav-link <?= ($page ?? '') === 'home' ? 'active' : '' ?>">
                <i class="fas fa-home"></i> Home
            </a>
            <a href="index.php?page=catalog" class="nav-link <?= ($page ?? '') === 'catalog' ? 'active' : '' ?>">
                <i class="fas fa-store"></i> Catalog
            </a>

            <?php if (isLoggedIn()): ?>
                <?php $user = currentUser(); ?>
                <div class="nav-user-menu">
                    <button class="nav-user-btn" id="userMenuBtn">
                        <span class="avatar-circle" style="background-color: <?= e($user['avatar_color']) ?>">
                            <?= getInitials($user['full_name']) ?>
                        </span>
                        <span class="nav-user-name"><?= e($user['full_name']) ?></span>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="dropdown-menu" id="userDropdown">
                        <div class="dropdown-header">
                            <span class="avatar-circle avatar-lg" style="background-color: <?= e($user['avatar_color']) ?>">
                                <?= getInitials($user['full_name']) ?>
                            </span>
                            <div>
                                <strong><?= e($user['full_name']) ?></strong>
                                <small><?= e($user['email']) ?></small>
                            </div>
                        </div>
                        <div class="dropdown-divider"></div>
                        <a href="index.php?page=logout" class="dropdown-item text-danger">
                            <i class="fas fa-sign-out-alt"></i> Sign Out
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <a href="index.php?page=login" class="nav-link <?= ($page ?? '') === 'login' ? 'active' : '' ?>">
                    <i class="fas fa-sign-in-alt"></i> Sign In
                </a>
                <a href="index.php?page=register" class="btn btn-primary btn-sm nav-cta">
                    <i class="fas fa-user-plus"></i> Register
                </a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<!-- Flash Messages -->
<?php $flash = get_flash(); ?>
<?php if ($flash): ?>
<div class="container">
    <div class="alert alert-<?= e($flash['type']) ?>" id="flashAlert">
        <div class="alert-content">
            <?php
            $icons = ['success' => 'fa-check-circle', 'danger' => 'fa-exclamation-circle', 'warning' => 'fa-exclamation-triangle', 'info' => 'fa-info-circle'];
            $icon = $icons[$flash['type']] ?? 'fa-info-circle';
            ?>
            <i class="fas <?= $icon ?>"></i>
            <span><?= e($flash['message']) ?></span>
        </div>
        <button class="alert-close" onclick="this.parentElement.remove()">
            <i class="fas fa-times"></i>
        </button>
    </div>
</div>
<?php endif; ?>

<main>
