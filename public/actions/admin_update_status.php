<?php
session_start();
require_once __DIR__ . '/../../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_SESSION['username'])) {
    $stmt = $pdo->prepare("SELECT role FROM users WHERE username = ?");
    $stmt->execute([$_SESSION['username']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && $user['role'] === 'admin') {
        $orderId = $_POST['order_id'] ?? null;
        $newStatus = $_POST['new_status'] ?? null;

        // Normalize and validate
        $allowedStatuses = ['Pending', 'Processing', 'Shipped', 'Completed'];
        if ($orderId && in_array($newStatus, $allowedStatuses)) {
            $update = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
            $update->execute([$newStatus, $orderId]);
        }
    }
}

header('Location: ../?page=admin_orders');
exit;
