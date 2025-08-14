<?php
// db_connection.php
header('Content-Type: application/json');

// ⚠️ استخدم متغيرات البيئة (Environment Variables) في بيئة الإنتاج
$host = 'dpg-d2f6j8fdiees7388nrgg-a.oregon-postgres.render.com';
$dbname = 'systemtravel2025';
$user = 'systemtravel2025_user';
$password = 'OoyJkHMuzTQDNl95WwFm9VZyImOfovb1';
$port = '5432';

try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;user=$user;password=$password";
    $pdo = new PDO($dsn);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed: ' . $e->getMessage()]);
    exit();
}