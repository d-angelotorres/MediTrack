<?php
require_once __DIR__ . '/../includes/db.php';

// Handle filtering
$statusFilter = $_GET['status'] ?? '';
$userFilter = $_GET['user'] ?? '';

// Build query
$query = "
    SELECT o.id, u.username, m.name AS medicine_name, o.quantity, o.status
    FROM orders o
    JOIN users u ON o.user_id = u.id
    JOIN medicines m ON o.medicine_id = m.id
    WHERE 1
";

$params = [];
if ($statusFilter !== '') {
    $query .= " AND o.status = ?";
    $params[] = $statusFilter;
}
if ($userFilter !== '') {
    $query .= " AND u.username LIKE ?";
    $params[] = '%' . $userFilter . '%';
}

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$orders = $stmt->fetchAll();
?>

<h2>Manage Orders</h2>

<form method="GET" class="mb-3 d-flex gap-2">
  <input type="hidden" name="page" value="admin_orders">
  <input type="text" name="user" placeholder="Search by user" value="<?= htmlspecialchars($userFilter) ?>" class="form-control" style="max-width: 200px;">
  <select name="status" class="form-select" style="max-width: 200px;">
    <option value="">All Statuses</option>
    <option value="Pending" <?= $statusFilter === 'Pending' ? 'selected' : '' ?>>Pending</option>
    <option value="Processing" <?= $order['status'] === 'Processing' ? 'selected' : '' ?>>Processing</option>
    <option value="Shipped" <?= $statusFilter === 'Shipped' ? 'selected' : '' ?>>Shipped</option>
    <option value="Completed" <?= $statusFilter === 'Completed' ? 'selected' : '' ?>>Completed</option>
  </select>
  <button class="btn btn-sm btn-secondary">Filter</button>
</form>

<table class="table table-bordered">
  <thead>
    <tr>
      <th>ID</th>
      <th>User</th>
      <th>Medicine</th>
      <th>Qty</th>
      <th>Status</th>
      <th>Change Status</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($orders as $order): ?>
      <tr>
        <td><?= $order['id'] ?></td>
        <td><?= htmlspecialchars($order['username']) ?></td>
        <td><?= htmlspecialchars($order['medicine_name']) ?></td>
        <td><?= $order['quantity'] ?></td>
        <td><?= $order['status'] ?></td>
        <td>
          <form method="POST" action="../actions/admin_update_status.php" class="d-flex">
            <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
            <select name="new_status" class="form-select form-select-sm me-2">
              <option value="Pending" <?= $order['status'] === 'Pending' ? 'selected' : '' ?>>Pending</option>
              <option value="Processing" <?= $order['status'] === 'Processing' ? 'selected' : '' ?>>Processing</option>
              <option value="Shipped" <?= $order['status'] === 'Shipped' ? 'selected' : '' ?>>Shipped</option>
              <option value="Completed" <?= $order['status'] === 'Completed' ? 'selected' : '' ?>>Completed</option>              
            </select>
            <button type="submit" class="btn btn-sm btn-primary">Update</button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
