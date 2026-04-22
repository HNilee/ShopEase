<?php
// Script ini otomatis memperbaiki file vendor Laravel yang menggunakan konstanta PDO yang deprecated di PHP 8.4+
// Digunakan untuk mencegah Error 500 di lingkungan Vercel.

$vendorDir = __DIR__ . '/vendor/laravel/framework';
if (!is_dir($vendorDir)) {
    echo "Vendor directory not found. Skipping patch.\n";
    exit(0);
}

// Cari semua file PHP di folder framework
$directory = new RecursiveDirectoryIterator($vendorDir);
$iterator = new RecursiveIteratorIterator($directory);
$regex = new RegexIterator($iterator, '/^.+\.php$/i', RecursiveRegexIterator::GET_MATCH);

foreach ($regex as $file) {
    $filePath = $file[0];
    $content = file_get_contents($filePath);
    
    // Ganti konstanta yang deprecated dengan fallback aman (integer 1012 adalah nilai asli MYSQL_ATTR_SSL_CA)
    $old = 'PDO::MYSQL_ATTR_SSL_CA';
    $new = '(defined(\'Pdo\\\\Mysql::ATTR_SSL_CA\') ? \Pdo\Mysql::ATTR_SSL_CA : 1012)';
    
    if (strpos($content, $old) !== false) {
        $newContent = str_replace($old, $new, $content);
        file_put_contents($filePath, $newContent);
        echo "Fixed deprecation in: " . basename($filePath) . "\n";
    }
}
