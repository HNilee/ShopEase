<?php
/**
 * Vercel Serverless Entry Point
 * This forwards requests to the standard Laravel public index.php
 */

// Supresi warning deprecation secara agresif untuk PHP 8.4+ di Vercel
// Hal ini mencegah warning muncul sebelum header HTTP dikirim (Penyebab Error 500)
error_reporting(E_ALL & ~E_DEPRECATED);
use Illuminate\Http\Request;

ini_set('display_errors', '0');
ini_set('log_errors', '1');

require __DIR__ . '/../public/index.php';
