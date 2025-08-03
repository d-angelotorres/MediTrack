<?php
session_start();
require_once __DIR__ . '/../../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_SESSION['username'])) {
    // Check admin
    $stmt = $pdo->prepare("SELECT role FROM users WHERE username = ?");
    $stmt->execute([$_SESSION['username']]);
    $currentUser = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($currentUser && $currentUser['role'] === 'admin') {
        $userId = $_POST['user_id'] ?? null;
        $newRole = $_POST['new_role'] ?? null;

        // Validate role
        $allowedRoles = ['customer', 'admin'];
        if ($userId && in_array($newRole, $allowedRoles)) {
            // Prevent changing own role
            $check = $pdo->prepare("SELECT username FROM users WHERE id = ?");
            $check->execute([$userId]);
            $targetUser = $check->fetch(PDO::FETCH_ASSOC);

            if ($targetUser && $targetUser['username'] !== $_SESSION['username']) {
                $update = $pdo->prepare("UPDATE users SET role = ? WHERE id = ?");
                $update->execute([$newRole, $userId]);
            }
        }
    }
}

header('Location: ../?page=admin_users');
exit;
