<?php
// login.php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Invalid request method']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);

$email = $data['email'] ?? '';
$password = $data['password'] ?? '';

if (empty($email) || empty($password)) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing data']);
    exit();
}

try {
    // 1. البحث عن المستخدم باستخدام البريد الإلكتروني
    $stmt = $pdo->prepare("SELECT id, password_hash FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        http_response_code(401);
        echo json_encode(['error' => 'Invalid credentials']);
        exit();
    }

    // ⚠️ 2. مقارنة كلمة المرور المدخلة مع الكلمة المشفرة
    if (password_verify($password, $user['password_hash'])) {
        // كلمة المرور صحيحة
        echo json_encode(['message' => 'Login successful', 'user_id' => $user['id']]);
        
        // في تطبيق حقيقي، ستقوم هنا بإنشاء توكن JWT وإرساله
    } else {
        // كلمة المرور خاطئة
        http_response_code(401);
        echo json_encode(['error' => 'Invalid credentials']);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error.']);
}