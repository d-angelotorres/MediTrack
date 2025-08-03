<?php
session_start();
$page = $_GET['page'] ?? 'home';
include_once __DIR__ . '/../includes/db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>MediTrack</title>
  <link href="../assets/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-..." crossorigin="anonymous">
</head>
<body class="p-4">

<?php include __DIR__ . '/../views/_nav.php'; ?>

<?php
switch ($page) {
  case 'login':
    include __DIR__ . '/../views/login.php';
    break;
  case 'register':
    include __DIR__ . '/../views/register.php';
    break;
  case 'order':
    include __DIR__ . '/../views/order.php';
    break;
  case 'admin':
    if (!empty($_SESSION['username'])) {
        // Fetch user role
        $stmt = $pdo->prepare("SELECT role FROM users WHERE username = ?");
        $stmt->execute([$_SESSION['username']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user && $user['role'] === 'admin') {
            include __DIR__ . '/../views/admin.php';
        } else {
            echo "<p>Access denied.</p>";
        }
    } else {
        header("Location: ?page=login");
        exit;
    }
    break;
  case 'admin_edit':
    if (!empty($_SESSION['username'])) {
        $stmt = $pdo->prepare("SELECT role FROM users WHERE username = ?");
        $stmt->execute([$_SESSION['username']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user && $user['role'] === 'admin') {
            include __DIR__ . '/../views/admin_edit.php';
        } else {
            echo "<p>Access denied.</p>";
        }
    } else {
        header("Location: ?page=login");
        exit;
    }
    break;
  case 'admin_orders':
  include __DIR__ . '/../views/admin_orders.php';
  break;
  case 'admin_users':
    if (!empty($_SESSION['username'])) {
        $stmt = $pdo->prepare("SELECT role FROM users WHERE username = ?");
        $stmt->execute([$_SESSION['username']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user && $user['role'] === 'admin') {
            include __DIR__ . '/../views/admin_users.php';
        } else {
            echo "<p>Access denied.</p>";
        }
    } else {
        header("Location: ?page=login");
        exit;
    }
    break;
  case 'admin_reports':
    if (!empty($_SESSION['username'])) {
        $stmt = $pdo->prepare("SELECT role FROM users WHERE username = ?");
        $stmt->execute([$_SESSION['username']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user && $user['role'] === 'admin') {
            include __DIR__ . '/../views/admin_reports.php';
        } else {
            echo "<p>Access denied.</p>";
        }
    } else {
        header("Location: ?page=login");
        exit;
    }
    break;  
  default:
    include __DIR__ . '/../views/home.php';
}
?>

</body>
</html>
