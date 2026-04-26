<?php
/**
 * EcoShelf - Sustainable Product Catalog
 * Main Entry Point / Front Controller
 */

session_start();

// Load configuration and helpers
require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/helpers/functions.php';

// Load controllers
require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/controllers/ProductController.php';

// Initialize controllers
$auth    = new AuthController();
$product = new ProductController();

// Route the request
$page   = $_GET['page'] ?? 'home';
$action = $_GET['action'] ?? '';

switch ($page) {
    // ── Auth Routes ──
    case 'register':
        if ($action === 'submit') {
            $auth->register();
        } else {
            $auth->showRegister();
        }
        break;

    case 'login':
        if ($action === 'submit') {
            $auth->login();
        } else {
            $auth->showLogin();
        }
        break;

    case 'logout':
        $auth->logout();
        break;

    // ── Product Routes ──
    case 'catalog':
        $product->catalog();
        break;

    case 'detail':
        $product->detail();
        break;

    case 'review':
        $product->submitReview();
        break;

    // ── Home / Default ──
    case 'home':
    default:
        $product->home();
        break;
}
