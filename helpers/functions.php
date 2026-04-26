<?php
/**
 * Helper Functions
 */

function redirect($path) {
    header("Location: " . BASE_URL . ltrim($path, '/'));
    exit;
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        $_SESSION['flash'] = ['type' => 'warning', 'message' => 'Please log in to access that page.'];
        redirect('index.php?page=login');
    }
}

function currentUser() {
    if (!isLoggedIn()) return null;
    return [
        'id'           => $_SESSION['user_id'],
        'full_name'    => $_SESSION['user_name'],
        'email'        => $_SESSION['user_email'],
        'avatar_color' => $_SESSION['avatar_color'] ?? '#2d6a4f',
    ];
}

function e($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

function csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_field() {
    return '<input type="hidden" name="csrf_token" value="' . csrf_token() . '">';
}

function verify_csrf($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

function flash($type, $message) {
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function get_flash() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

function getInitials($name) {
    $parts = explode(' ', trim($name));
    $initials = '';
    foreach (array_slice($parts, 0, 2) as $part) {
        $initials .= strtoupper(mb_substr($part, 0, 1));
    }
    return $initials;
}

function renderLeaves($rating, $max = 5) {
    $html = '<span class="leaf-rating" title="' . $rating . ' out of ' . $max . ' sustainability rating">';
    for ($i = 1; $i <= $max; $i++) {
        if ($i <= $rating) {
            $html .= '<i class="fas fa-leaf leaf-filled"></i>';
        } else {
            $html .= '<i class="fas fa-leaf leaf-empty"></i>';
        }
    }
    $html .= '</span>';
    return $html;
}

function renderStockBadge($status) {
    $map = [
        'in_stock'     => ['In Stock', 'badge-success'],
        'low_stock'    => ['Low Stock', 'badge-warning'],
        'out_of_stock' => ['Out of Stock', 'badge-danger'],
    ];
    $info = $map[$status] ?? $map['in_stock'];
    return '<span class="badge ' . $info[1] . '">' . $info[0] . '</span>';
}

function timeAgo($datetime) {
    $now = new DateTime();
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    if ($diff->y > 0) return $diff->y . ' year' . ($diff->y > 1 ? 's' : '') . ' ago';
    if ($diff->m > 0) return $diff->m . ' month' . ($diff->m > 1 ? 's' : '') . ' ago';
    if ($diff->d > 0) return $diff->d . ' day' . ($diff->d > 1 ? 's' : '') . ' ago';
    if ($diff->h > 0) return $diff->h . ' hour' . ($diff->h > 1 ? 's' : '') . ' ago';
    if ($diff->i > 0) return $diff->i . ' minute' . ($diff->i > 1 ? 's' : '') . ' ago';
    return 'Just now';
}
