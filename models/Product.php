<?php
/**
 * Product Model
 */

require_once __DIR__ . '/../config/database.php';

class Product {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Get products with optional filters
     */
    public function getProducts($filters = []) {
        $where  = [];
        $params = [];

        // Search keyword
        if (!empty($filters['search'])) {
            $where[]  = "(p.name LIKE ? OR p.description LIKE ? OR p.brand LIKE ? OR p.materials LIKE ?)";
            $keyword  = '%' . $filters['search'] . '%';
            $params[] = $keyword;
            $params[] = $keyword;
            $params[] = $keyword;
            $params[] = $keyword;
        }

        // Category filter
        if (!empty($filters['category'])) {
            $where[]  = "c.slug = ?";
            $params[] = $filters['category'];
        }

        // Sustainability rating filter
        if (!empty($filters['min_rating'])) {
            $where[]  = "p.sustainability_rating >= ?";
            $params[] = (int)$filters['min_rating'];
        }

        // Price range
        if (!empty($filters['min_price'])) {
            $where[]  = "p.price >= ?";
            $params[] = (float)$filters['min_price'];
        }
        if (!empty($filters['max_price'])) {
            $where[]  = "p.price <= ?";
            $params[] = (float)$filters['max_price'];
        }

        // Stock status
        if (!empty($filters['stock_status'])) {
            $where[]  = "p.stock_status = ?";
            $params[] = $filters['stock_status'];
        }

        // Certification filter
        if (!empty($filters['certification'])) {
            $where[]  = "p.certifications LIKE ?";
            $params[] = '%' . $filters['certification'] . '%';
        }

        $sql = "SELECT p.*, c.name AS category_name, c.slug AS category_slug, c.icon AS category_icon
                FROM products p
                JOIN categories c ON p.category_id = c.id";

        if (!empty($where)) {
            $sql .= " WHERE " . implode(" AND ", $where);
        }

        // Sorting
        $sortOptions = [
            'name_asc'    => 'p.name ASC',
            'name_desc'   => 'p.name DESC',
            'price_asc'   => 'p.price ASC',
            'price_desc'  => 'p.price DESC',
            'rating_desc' => 'p.sustainability_rating DESC, p.name ASC',
            'rating_asc'  => 'p.sustainability_rating ASC, p.name ASC',
            'newest'      => 'p.created_at DESC',
        ];
        $sort = $filters['sort'] ?? 'rating_desc';
        $orderBy = $sortOptions[$sort] ?? $sortOptions['rating_desc'];
        $sql .= " ORDER BY " . $orderBy;

        // Pagination
        $page    = max(1, (int)($filters['page'] ?? 1));
        $perPage = 12;
        $offset  = ($page - 1) * $perPage;

        // Get total count
        $countSql  = "SELECT COUNT(*) FROM products p JOIN categories c ON p.category_id = c.id";
        if (!empty($where)) {
            $countSql .= " WHERE " . implode(" AND ", $where);
        }
        $countStmt = $this->db->prepare($countSql);
        $countStmt->execute($params);
        $total = (int)$countStmt->fetchColumn();

        $sql .= " LIMIT $perPage OFFSET $offset";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $products = $stmt->fetchAll();

        return [
            'products'    => $products,
            'total'       => $total,
            'page'        => $page,
            'per_page'    => $perPage,
            'total_pages' => ceil($total / $perPage),
        ];
    }

    /**
     * Get a single product by slug
     */
    public function getBySlug($slug) {
        $stmt = $this->db->prepare(
            "SELECT p.*, c.name AS category_name, c.slug AS category_slug, c.icon AS category_icon
             FROM products p
             JOIN categories c ON p.category_id = c.id
             WHERE p.slug = ? LIMIT 1"
        );
        $stmt->execute([$slug]);
        return $stmt->fetch();
    }

    /**
     * Get a single product by ID
     */
    public function getById($id) {
        $stmt = $this->db->prepare(
            "SELECT p.*, c.name AS category_name, c.slug AS category_slug, c.icon AS category_icon
             FROM products p
             JOIN categories c ON p.category_id = c.id
             WHERE p.id = ? LIMIT 1"
        );
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
     * Get featured products
     */
    public function getFeatured($limit = 6) {
        $stmt = $this->db->prepare(
            "SELECT p.*, c.name AS category_name, c.slug AS category_slug, c.icon AS category_icon
             FROM products p
             JOIN categories c ON p.category_id = c.id
             WHERE p.is_featured = 1
             ORDER BY p.sustainability_rating DESC
             LIMIT ?"
        );
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }

    /**
     * Get related products (same category, excluding current)
     */
    public function getRelated($productId, $categoryId, $limit = 4) {
        $stmt = $this->db->prepare(
            "SELECT p.*, c.name AS category_name, c.slug AS category_slug
             FROM products p
             JOIN categories c ON p.category_id = c.id
             WHERE p.category_id = ? AND p.id != ?
             ORDER BY p.sustainability_rating DESC
             LIMIT ?"
        );
        $stmt->execute([$categoryId, $productId, $limit]);
        return $stmt->fetchAll();
    }

    /**
     * Get all categories with product counts
     */
    public function getCategories() {
        $stmt = $this->db->query(
            "SELECT c.*, COUNT(p.id) AS product_count
             FROM categories c
             LEFT JOIN products p ON p.category_id = c.id
             GROUP BY c.id
             ORDER BY c.name ASC"
        );
        return $stmt->fetchAll();
    }

    /**
     * Get all unique certifications
     */
    public function getCertifications() {
        $stmt = $this->db->query("SELECT DISTINCT certifications FROM products WHERE certifications IS NOT NULL");
        $rows = $stmt->fetchAll();
        $certs = [];
        foreach ($rows as $row) {
            foreach (explode(',', $row['certifications']) as $cert) {
                $cert = trim($cert);
                if ($cert && !in_array($cert, $certs)) {
                    $certs[] = $cert;
                }
            }
        }
        sort($certs);
        return $certs;
    }

    /**
     * Get product statistics
     */
    public function getStats() {
        $stats = [];
        $stmt = $this->db->query("SELECT COUNT(*) FROM products");
        $stats['total_products'] = $stmt->fetchColumn();

        $stmt = $this->db->query("SELECT COUNT(*) FROM products WHERE sustainability_rating >= 4");
        $stats['high_rated'] = $stmt->fetchColumn();

        $stmt = $this->db->query("SELECT COUNT(DISTINCT brand) FROM products WHERE brand IS NOT NULL");
        $stats['brands'] = $stmt->fetchColumn();

        $stmt = $this->db->query("SELECT COUNT(*) FROM categories");
        $stats['categories'] = $stmt->fetchColumn();

        return $stats;
    }

    /**
     * Get reviews for a product
     */
    public function getReviews($productId) {
        $stmt = $this->db->prepare(
            "SELECT r.*, u.full_name, u.avatar_color
             FROM product_reviews r
             JOIN users u ON r.user_id = u.id
             WHERE r.product_id = ?
             ORDER BY r.created_at DESC"
        );
        $stmt->execute([$productId]);
        return $stmt->fetchAll();
    }

    /**
     * Add a review
     */
    public function addReview($productId, $userId, $rating, $comment) {
        // Check for existing review
        $stmt = $this->db->prepare(
            "SELECT id FROM product_reviews WHERE product_id = ? AND user_id = ?"
        );
        $stmt->execute([$productId, $userId]);
        if ($stmt->fetch()) {
            return ['success' => false, 'error' => 'You have already reviewed this product.'];
        }

        $stmt = $this->db->prepare(
            "INSERT INTO product_reviews (product_id, user_id, rating, comment) VALUES (?, ?, ?, ?)"
        );
        $stmt->execute([$productId, $userId, $rating, $comment]);
        return ['success' => true];
    }

    /**
     * Get average rating for a product
     */
    public function getAverageRating($productId) {
        $stmt = $this->db->prepare(
            "SELECT AVG(rating) as avg_rating, COUNT(*) as review_count
             FROM product_reviews WHERE product_id = ?"
        );
        $stmt->execute([$productId]);
        return $stmt->fetch();
    }
}
