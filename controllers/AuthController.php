<?php
/**
 * Auth Controller
 */

require_once __DIR__ . '/../models/User.php';

class AuthController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    /**
     * Show registration form
     */
    public function showRegister() {
        if (isLoggedIn()) {
            redirect('index.php?page=catalog');
        }
        $pageTitle = 'Create Account';
        include __DIR__ . '/../views/auth/register.php';
    }

    /**
     * Process registration
     */
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('index.php?page=register');
        }

        // CSRF check
        if (!verify_csrf($_POST['csrf_token'] ?? '')) {
            flash('danger', 'Invalid form submission. Please try again.');
            redirect('index.php?page=register');
        }

        $fullName = trim($_POST['full_name'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm  = $_POST['password_confirm'] ?? '';

        // Validate passwords match
        if ($password !== $confirm) {
            flash('danger', 'Passwords do not match.');
            redirect('index.php?page=register');
        }

        $result = $this->userModel->register($fullName, $email, $password);

        if ($result['success']) {
            // Auto-login after registration
            $this->userModel->login($email, $password);
            flash('success', 'Welcome to ' . APP_NAME . '! Your account has been created.');
            redirect('index.php?page=catalog');
        } else {
            flash('danger', $result['error']);
            redirect('index.php?page=register');
        }
    }

    /**
     * Show login form
     */
    public function showLogin() {
        if (isLoggedIn()) {
            redirect('index.php?page=catalog');
        }
        $pageTitle = 'Sign In';
        include __DIR__ . '/../views/auth/login.php';
    }

    /**
     * Process login
     */
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('index.php?page=login');
        }

        if (!verify_csrf($_POST['csrf_token'] ?? '')) {
            flash('danger', 'Invalid form submission. Please try again.');
            redirect('index.php?page=login');
        }

        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        $result = $this->userModel->login($email, $password);

        if ($result['success']) {
            flash('success', 'Welcome back, ' . e($_SESSION['user_name']) . '!');
            redirect('index.php?page=catalog');
        } else {
            flash('danger', $result['error']);
            redirect('index.php?page=login');
        }
    }

    /**
     * Logout
     */
    public function logout() {
        $this->userModel->logout();
        session_start();
        flash('success', 'You have been signed out.');
        redirect('index.php?page=login');
    }
}
