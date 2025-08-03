<?php
require_once __DIR__ . '/../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $orderId = $_POST['order_id'];

    $stmt = $pdo->prepare("UPDATE orders SET status = 'Confirmed' WHERE id = ?");
    $stmt->execute([$orderId]);

    header("Location: ../public/index.php?page=admin_orders");
    exit;
}
