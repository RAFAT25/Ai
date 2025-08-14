<?php
// fetch_notifications.php
include 'db_connection.php';

// التأكد من أن الطلب من نوع GET
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['error' => 'Invalid request method.']);
    exit();
}

// ⚠️ هنا يجب أن يكون لديك آلية للتحقق من هوية المستخدم (Authentication)
// على سبيل المثال، عبر توكن (JWT) أو معرف مستخدم مرسل مع الطلب.
// هذا مجرد مثال على كيفية تمرير معرف المستخدم.
$user_id = $_GET['user_id'] ?? null;

if (empty($user_id)) {
    http_response_code(400);
    echo json_encode(['error' => 'User ID is required.']);
    exit();
}

try {
    // 1. استخدام استعلام JOIN لجلب الإشعارات مع اسم المستخدم
    $stmt = $pdo->prepare(
        "SELECT n.id, n.title, n.body, n.is_read, n.created_at, u.username
         FROM notifications n
         JOIN users u ON n.user_id = u.id
         WHERE n.user_id = :user_id
         ORDER BY n.created_at DESC"
    );
    $stmt->execute(['user_id' => $user_id]);
    
    // 2. جلب جميع الصفوف ككائن (Object)
    $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 3. إرجاع النتائج كـ JSON
    echo json_encode(['notifications' => $notifications]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to fetch notifications.']);
}