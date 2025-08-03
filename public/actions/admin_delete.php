<?php
session_start();
require_once __DIR__ . '/../../includes/db.php';

// Check if logged in and admin
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

// Validate medicine ID
if (empty($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error'] = "Invalid medicine ID.";
    header("Location: ../public/?page=admin");
    exit;
}

$medicine_id = (int) $_GET['id'];

// Delete medicine
$stmt = $pdo->prepare("DELETE FROM medicines WHERE id = ?");
$success = $stmt->execute([$medicine_id]);

if ($success) {
    $_SESSION['success'] = "Medicine deleted successfully.";
} else {
    $_SESSION['error'] = "Failed to delete medicine.";
}

header("Location: ../public/?page=admin");
exit;
