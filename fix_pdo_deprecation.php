<?php
// Script ini otomatis memperbaiki file vendor Laravel yang menggunakan konstanta PDO yang deprecated di PHP 8.4+
// Digunakan untuk mencegah Error 500 di lingkungan Vercel.

$files = [
    __DIR__ . '/vendor/laravel/framework/config/database.php',
];

foreach ($files as $file) {
    if (file_exists($file)) {
        $content = file_get_contents($file);
        
        // Ganti konstanta yang deprecated dengan fallback aman
        $old = 'PDO::MYSQL_ATTR_SSL_CA';
        $new = '(defined(\'Pdo\\\\Mysql::ATTR_SSL_CA\') ? \Pdo\Mysql::ATTR_SSL_CA : 1012)';
        
        if (strpos($content, $old) !== false) {
            $newContent = str_replace($old, $new, $content);
            file_put_contents($file, $newContent);
            echo "Fixed deprecation in: $file\n";
        }
    }
}
