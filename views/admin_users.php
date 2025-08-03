<?php
require_once __DIR__ . '/../includes/db.php';

// Handle filtering/search
$search = $_GET['search'] ?? '';

// Fetch users
$query = "SELECT id, username, role FROM users WHERE 1";
$params = [];

if ($search !== '') {
    $query .= " AND username LIKE ?";
    $params[] = '%' . $search . '%';
}

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$users = $stmt->fetchAll();
?>

<h2>Manage Users</h2>

<form method="GET" class="mb-3 d-flex gap-2" style="max-width: 400px;">
  <input type="hidden" name="page" value="admin_users">
  <input type="text" name="search" placeholder="Search users" value="<?= htmlspecialchars($search) ?>" class="form-control">
  <button class="btn btn-sm btn-secondary">Search</button>
</form>

<table class="table table-bordered">
  <thead>
    <tr>
      <th>ID</th>
      <th>Username</th>
      <th>Role</th>
      <th>Change Role</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($users as $user): ?>
    <tr>
      <td><?= $user['id'] ?></td>
      <td><?= htmlspecialchars($user['username']) ?></td>
      <td><?= $user['role'] ?></td>
      <td>
        <?php if ($user['username'] !== $_SESSION['username']): ?>
          <form method="POST" action="../actions/admin_update_user.php" class="d-flex gap-2">
            <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
            <select name="new_role" class="form-select form-select-sm">
              <option value="customer" <?= $user['role'] === 'customer' ? 'selected' : '' ?>>Customer</option>
              <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
            </select>
            <button type="submit" class="btn btn-sm btn-primary">Update</button>
          </form>
        <?php else: ?>
          <em>Current User</em>
        <?php endif; ?>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
