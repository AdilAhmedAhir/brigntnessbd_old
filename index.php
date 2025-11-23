<?php

/**
 * Laravel - A PHP Framework For Web Artisans
 * 
 * This file allows you to keep your public files in the /public directory
 * while deploying to cPanel or similar hosting where the document root
 * is the main directory.
 */

// Path to the public directory
$publicPath = __DIR__ . '/public';

// Define paths as relative to the public directory
$_SERVER['DOCUMENT_ROOT'] = $publicPath;

// If the URI is directly for a file in the public directory,
// serve it directly (this is already handled by .htaccess)

// For all other requests, run the application through Laravel
// by including the public/index.php file
$_SERVER['SCRIPT_FILENAME'] = $publicPath . '/index.php';

// Handle request to the main application
require_once $publicPath . '/index.php';
