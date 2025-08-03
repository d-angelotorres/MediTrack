<?php
session_start();
include_once __DIR__ . '/../../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Check if username exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute([$username]);
    if ($stmt->fetch()) {
        $_SESSION['error'] = "Username already taken.";
        header("Location: ../public/?page=register");
        exit;
    }

    // Hash the password
    $hash = password_hash($password, PASSWORD_DEFAULT);

    // Insert user
    $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    if ($stmt->execute([$username, $hash])) {
        $_SESSION['success'] = "Registration successful! Please log in.";
        header("Location: ../public/?page=login");
    } else {
        $_SESSION['error'] = "Registration failed, try again.";
        header("Location: ../public/?page=register");
    }
}
