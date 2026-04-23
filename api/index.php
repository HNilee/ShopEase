<?php
/**
 * Vercel Serverless Entry Point
 * Disederhanakan untuk menghindari konflik inisialisasi framework.
 */

// Supresi warning deprecation PHP 8.4+ agar tidak merusak header HTTP
error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);
ini_set('display_errors', '0');
ini_set('log_errors', '1');

$configCache = '/tmp/config.php';
if (file_exists($configCache)) {
    unlink($configCache);
}

// Forward request ke Laravel public index.php
require __DIR__ . '/../public/index.php';
