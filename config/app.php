<?php
/**
 * Application Configuration
 */

define('BASE_URL', '/');  // e.g., '/' or '/sustainable-catalog/'

// App Info
define('APP_NAME', 'EcoShelf');
define('APP_TAGLINE', 'Discover Sustainable Living');
define('APP_VERSION', '1.0.0');

// Session config
ini_set('session.cookie_httponly', 1);
ini_set('session.use_strict_mode', 1);

// Timezone
date_default_timezone_set('UTC');

// Error reporting (set to 0 in production)
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
