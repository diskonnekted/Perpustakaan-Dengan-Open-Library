<?php
$host = 'localhost';
$dbname = 'library_db';
$username = 'root';
$password = '';

// Define Base URL dynamically
// If running on localhost/127.0.0.1, assume /lib/ path
// If running on live server (smartdesa.net), assume root path /
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https://" : "http://";
$domain = $_SERVER['HTTP_HOST'];

// Logic sederhana: jika localhost, pakai /lib/. Jika bukan (live server), pakai /.
if ($domain === 'localhost' || $domain === '127.0.0.1') {
    define('BASE_URL', '/lib/');
} else {
    define('BASE_URL', '/');
}

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // If database not found, try connecting without dbname to create it
    try {
        $pdo = new PDO("mysql:host=$host;charset=utf8", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname`");
        $pdo->exec("USE `$dbname`");
    } catch (PDOException $ex) {
        die("Koneksi database gagal: " . $ex->getMessage());
    }
}
?>