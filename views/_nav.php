<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$isAdmin = false;
if (!empty($_SESSION['username'])) {
    require_once __DIR__ . '/../includes/db.php';
    $stmt = $pdo->prepare("SELECT role FROM users WHERE username = ?");
    $stmt->execute([$_SESSION['username']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user && $user['role'] === 'admin') {
        $isAdmin = true;
    }
}
?>
<nav class="mb-4">
  <?php if (!empty($_SESSION['username'])): ?>
    Hello, <?= htmlspecialchars($_SESSION['username']); ?> |
    <?php if ($isAdmin): ?>
      <a href="?page=admin">Admin</a> |
      <a href="?page=admin_orders">Manage Orders</a> |
      <a href="?page=admin_users">Manage Users</a> |
      <a href="?page=admin_reports">Reports</a> |
    <?php endif; ?>
    <a href="../actions/logout.php">Logout</a>
  <?php else: ?>
    <a href="?page=login">Login</a> | <a href="?page=register">Register</a>
  <?php endif; ?>
</nav>
