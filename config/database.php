<?php
$host = 'localhost';
$dbname = 'techstore_db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("SET NAMES 'utf8'");
} catch(PDOException $e) {
    die("Kết nối CSDL thất bại: " . $e->getMessage());
}
?>
