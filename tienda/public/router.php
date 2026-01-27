<?php

/**
 * Laravel - PHP Development Server Router
 */

$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/'
);

// Serve static files directly
if ($uri !== '/' && file_exists(__DIR__.$uri) && is_file(__DIR__.$uri)) {
    return false;
}

// Handle all other requests through Laravel
require_once __DIR__.'/index.php';
