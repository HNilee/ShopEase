<?php
$file = __DIR__ . '/vendor/laravel/framework/config/database.php';
if (file_exists($file)) {
    $content = file_get_contents($file);
    $content = str_replace(
        'PDO::MYSQL_ATTR_SSL_CA',
        "(defined('Pdo\\\\Mysql::ATTR_SSL_CA') ? Pdo\\\\Mysql::ATTR_SSL_CA : \\PDO::MYSQL_ATTR_SSL_CA)",
        $content
    );
    file_put_contents($file, $content);
}