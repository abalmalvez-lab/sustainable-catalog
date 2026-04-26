<?php
/**
 * User Model
 */

require_once __DIR__ . '/../config/database.php';

class User {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Register a new user
     */
    public function register($fullName, $email, $password) {
        // Check if email already exists
        if ($this->findByEmail($email)) {
            return ['success' => false, 'error' => 'An account with this email already exists.'];
        }

        // Validate
        if (strlen($fullName) < 2 || strlen($fullName) > 100) {
            return ['success' => false, 'error' => 'Full name must be between 2 and 100 characters.'];
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'error' => 'Please enter a valid email address.'];
        }
        if (strlen($password) < 8) {
            return ['success' => false, 'error' => 'Password must be at least 8 characters long.'];
        }

        // Generate avatar color
        $colors = ['#2d6a4f', '#40916c', '#52b788', '#1b4332', '#8338ec', '#3a86ff', '#ff006e', '#fb5607'];
        $avatarColor = $colors[array_rand($colors)];

        $hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);

        $stmt = $this->db->prepare(
            "INSERT INTO users (full_name, email, password_hash, avatar_color) VALUES (?, ?, ?, ?)"
        );

        try {
            $stmt->execute([$fullName, $email, $hash, $avatarColor]);
            return ['success' => true, 'user_id' => $this->db->lastInsertId()];
        } catch (PDOException $e) {
            return ['success' => false, 'error' => 'Registration failed. Please try again.'];
        }
    }

    /**
     * Authenticate a user
     */
    public function login($email, $password) {
        $user = $this->findByEmail($email);

        if (!$user || !password_verify($password, $user['password_hash'])) {
            return ['success' => false, 'error' => 'Invalid email or password.'];
        }

        // Set session
        $_SESSION['user_id']     = $user['id'];
        $_SESSION['user_name']   = $user['full_name'];
        $_SESSION['user_email']  = $user['email'];
        $_SESSION['avatar_color'] = $user['avatar_color'];

        // Regenerate session ID to prevent fixation
        session_regenerate_id(true);

        return ['success' => true, 'user' => $user];
    }

    /**
     * Logout
     */
    public function logout() {
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
    }

    /**
     * Find user by email
     */
    public function findByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    /**
     * Find user by ID
     */
    public function findById($id) {
        $stmt = $this->db->prepare("SELECT id, full_name, email, avatar_color, created_at FROM users WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
}
