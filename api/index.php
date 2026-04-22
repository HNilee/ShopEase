<?php
/**
 * Vercel Serverless Entry Point
 * Menangani routing dan menekan warning deprecation agar tidak merusak header HTTP.
 */

// 1. Matikan semua output error ke browser (PENTING untuk Vercel)
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);

// 2. Gunakan output buffering untuk menangkap output liar sebelum header dikirim
ob_start();

// 3. Load aplikasi Laravel
require __DIR__ . '/../public/index.php';
