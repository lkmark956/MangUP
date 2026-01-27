<?php

/**
 * Laravel - PHP Development Server Router
 */

$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/'
);

// Serve static files directly from public folder
$publicPath = __DIR__.'/public'.$uri;
if ($uri !== '/' && file_exists($publicPath) && is_file($publicPath)) {
    return false;
}

// Set the public path for Laravel
chdir(__DIR__.'/public');

// Handle all other requests through Laravel
require __DIR__.'/public/index.php';
