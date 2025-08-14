<?php
// register.php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Invalid request method']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);

$username = $data['username'] ?? '';
$email = $data['email'] ?? '';
$password = $data['password'] ?? '';

if (empty($username) || empty($email) || empty($password)) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing data']);
    exit();
}

try {
    // ⚠️ 1. تشفير كلمة المرور بشكل آمن باستخدام password_hash()
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // 2. استخدام PreparedStatement لمنع حقن SQL
    $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash) VALUES (:username, :email, :password_hash)");
    $stmt->execute([
        'username' => $username,
        'email' => $email,
        'password_hash' => $hashed_password
    ]);

    echo json_encode(['message' => 'User created successfully']);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to create user.']);
}