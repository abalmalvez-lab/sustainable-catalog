<?php
/**
 * Product Controller
 */

require_once __DIR__ . '/../models/Product.php';

class ProductController {
    private $productModel;

    public function __construct() {
        $this->productModel = new Product();
    }

    /**
     * Home / Landing page
     */
    public function home() {
        $featured   = $this->productModel->getFeatured(6);
        $categories = $this->productModel->getCategories();
        $stats      = $this->productModel->getStats();
        $pageTitle  = APP_NAME . ' - ' . APP_TAGLINE;
        include __DIR__ . '/../views/products/home.php';
    }

    /**
     * Product catalog with filters
     */
    public function catalog() {
        $filters = [
            'search'        => $_GET['search'] ?? '',
            'category'      => $_GET['category'] ?? '',
            'min_rating'    => $_GET['min_rating'] ?? '',
            'min_price'     => $_GET['min_price'] ?? '',
            'max_price'     => $_GET['max_price'] ?? '',
            'stock_status'  => $_GET['stock_status'] ?? '',
            'certification' => $_GET['certification'] ?? '',
            'sort'          => $_GET['sort'] ?? 'rating_desc',
            'page'          => $_GET['pg'] ?? 1,
        ];

        $result         = $this->productModel->getProducts($filters);
        $categories     = $this->productModel->getCategories();
        $certifications = $this->productModel->getCertifications();
        $pageTitle      = 'Product Catalog';
        include __DIR__ . '/../views/products/catalog.php';
    }

    /**
     * Single product detail page
     */
    public function detail() {
        $slug = $_GET['slug'] ?? '';
        if (empty($slug)) {
            flash('warning', 'Product not found.');
            redirect('index.php?page=catalog');
        }

        $product = $this->productModel->getBySlug($slug);
        if (!$product) {
            flash('warning', 'Product not found.');
            redirect('index.php?page=catalog');
        }

        $reviews     = $this->productModel->getReviews($product['id']);
        $ratingInfo  = $this->productModel->getAverageRating($product['id']);
        $related     = $this->productModel->getRelated($product['id'], $product['category_id']);
        $pageTitle   = $product['name'];
        include __DIR__ . '/../views/products/detail.php';
    }

    /**
     * Submit a review
     */
    public function submitReview() {
        requireLogin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('index.php?page=catalog');
        }

        if (!verify_csrf($_POST['csrf_token'] ?? '')) {
            flash('danger', 'Invalid form submission.');
            redirect('index.php?page=catalog');
        }

        $productId = (int)($_POST['product_id'] ?? 0);
        $rating    = (int)($_POST['rating'] ?? 0);
        $comment   = trim($_POST['comment'] ?? '');

        if ($rating < 1 || $rating > 5) {
            flash('danger', 'Please select a valid rating.');
            redirect('index.php?page=catalog');
        }

        $product = $this->productModel->getById($productId);
        if (!$product) {
            flash('danger', 'Product not found.');
            redirect('index.php?page=catalog');
        }

        $result = $this->productModel->addReview($productId, $_SESSION['user_id'], $rating, $comment);

        if ($result['success']) {
            flash('success', 'Your review has been submitted!');
        } else {
            flash('warning', $result['error']);
        }

        redirect('index.php?page=detail&slug=' . $product['slug']);
    }
}
