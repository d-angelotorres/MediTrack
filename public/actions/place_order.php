<?php
session_start();

if (empty($_SESSION['username'])) {
    // Not logged in, redirect to login page
    header("Location: ../public/?page=login");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['medicine_id']) || empty($_POST['quantity'])) {
    // Invalid access or missing data
    header("Location: ../public/?page=order");
    exit;
}

require_once __DIR__ . '/../../includes/db.php';

$medicine_id = (int) $_POST['medicine_id'];
$quantity = (int) $_POST['quantity'];

if ($quantity < 1) {
    $_SESSION['error'] = "Quantity must be at least 1.";
    header("Location: ../public/?page=order");
    exit;
}

// Get user ID from session username
$stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
$stmt->execute([$_SESSION['username']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    // User not found (should not happen)
    $_SESSION['error'] = "User not found.";
    header("Location: ../public/?page=login");
    exit;
}

// Check medicine stock
$stmt = $pdo->prepare("SELECT stock FROM medicines WHERE id = ?");
$stmt->execute([$medicine_id]);
$medicine = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$medicine) {
    $_SESSION['error'] = "Medicine not found.";
    header("Location: ../public/?page=order");
    exit;
}

if ($medicine['stock'] < $quantity) {
    $_SESSION['error'] = "Insufficient stock for the requested quantity.";
    header("Location: ../public/?page=order");
    exit;
}

// Insert order
$stmt = $pdo->prepare("INSERT INTO orders (user_id, medicine_id, quantity, status) VALUES (?, ?, ?, 'Pending')");
$success = $stmt->execute([$user['id'], $medicine_id, $quantity]);

if ($success) {
    // Update stock
    $stmt = $pdo->prepare("UPDATE medicines SET stock = stock - ? WHERE id = ?");
    $stmt->execute([$quantity, $medicine_id]);

    $_SESSION['success'] = "Order placed successfully!";
} else {
    $_SESSION['error'] = "Failed to place order.";
}

header("Location: ../public/?page=order");
exit;
