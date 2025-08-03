<?php
session_start();
require_once __DIR__ . '/../../includes/db.php';

// Check if user is logged in and admin
if (empty($_SESSION['username'])) {
    header("Location: ../public/?page=login");
    exit;
}

$stmt = $pdo->prepare("SELECT role FROM users WHERE username = ?");
$stmt->execute([$_SESSION['username']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user || $user['role'] !== 'admin') {
    $_SESSION['error'] = "Access denied.";
    header("Location: ../public/?page=admin");
    exit;
}

// Validate POST data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = $_POST['price'] ?? '';
    $stock = $_POST['stock'] ?? '';

    if ($name === '' || $description === '' || !is_numeric($price) || !is_numeric($stock)) {
        $_SESSION['error'] = "Please fill all fields correctly.";
        header("Location: ../public/?page=admin");
        exit;
    }

    $price = floatval($price);
    $stock = intval($stock);

    if ($price < 0 || $stock < 0) {
        $_SESSION['error'] = "Price and stock must be non-negative.";
        header("Location: ../public/?page=admin");
        exit;
    }

    // Insert into medicines
    $stmt = $pdo->prepare("INSERT INTO medicines (name, description, price, stock) VALUES (?, ?, ?, ?)");
    $success = $stmt->execute([$name, $description, $price, $stock]);

    if ($success) {
        $_SESSION['success'] = "Medicine added successfully.";
    } else {
        $_SESSION['error'] = "Failed to add medicine.";
    }

    header("Location: ../public/?page=admin");
    exit;
} else {
    // Invalid request method
    header("Location: ../public/?page=admin");
    exit;
}
