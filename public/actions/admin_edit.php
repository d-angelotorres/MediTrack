<?php
session_start();
require_once __DIR__ . '/../../includes/db.php';

// Check user and admin role (same as before)
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
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../public/?page=admin");
    exit;
}

$id = (int)($_POST['id'] ?? 0);
$name = trim($_POST['name'] ?? '');
$description = trim($_POST['description'] ?? '');
$price = $_POST['price'] ?? '';
$stock = $_POST['stock'] ?? '';

if ($id <= 0 || $name === '' || $description === '' || !is_numeric($price) || !is_numeric($stock)) {
    $_SESSION['error'] = "Invalid input data.";
    header("Location: ../public/?page=admin_edit&id=$id");
    exit;
}

$price = floatval($price);
$stock = intval($stock);

if ($price < 0 || $stock < 0) {
    $_SESSION['error'] = "Price and stock must be non-negative.";
    header("Location: ../public/?page=admin_edit&id=$id");
    exit;
}

// Update the medicine
$stmt = $pdo->prepare("UPDATE medicines SET name = ?, description = ?, price = ?, stock = ? WHERE id = ?");
$success = $stmt->execute([$name, $description, $price, $stock, $id]);

if ($success) {
    $_SESSION['success'] = "Medicine updated successfully.";
    header("Location: ../public/?page=admin");
} else {
    $_SESSION['error'] = "Failed to update medicine.";
    header("Location: ../public/?page=admin_edit&id=$id");
}
exit;
