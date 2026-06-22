<?php
// Start session globally
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. URL Constants (For the browser - CSS, JS, Images, Links)
define('BASE_URL', 'http://localhost/bim4projects/g0/news/public/');

// 2. Filesystem Path Constants (For PHP internal includes/requires)
// __DIR__ pointing to the config folder, so dirname(__DIR__) gets the project root
define('ROOT_PATH', dirname(__DIR__) . '/');
define('TEMPLATE_PATH', ROOT_PATH . 'templates/');
define('SRC_PATH', ROOT_PATH . 'src/');

// Error Reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);