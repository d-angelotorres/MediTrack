<?php
// Admin Dashboard - medicine management

// Handle flash messages
if (!empty($_SESSION['success'])) {
    echo '<div class="alert alert-success">' . htmlspecialchars($_SESSION['success']) . '</div>';
    unset($_SESSION['success']);
}
if (!empty($_SESSION['error'])) {
    echo '<div class="alert alert-danger">' . htmlspecialchars($_SESSION['error']) . '</div>';
    unset($_SESSION['error']);
}

// Fetch medicines
$stmt = $pdo->query("SELECT * FROM medicines ORDER BY name ASC");
$medicines = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Admin Dashboard</h2>

<h3>Current Medicines</h3>
<table class="table table-striped">
  <thead>
    <tr>
      <th>Name</th><th>Description</th><th>Price</th><th>Stock</th><th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($medicines as $med): ?>
    <tr>
      <td><?= htmlspecialchars($med['name']) ?></td>
      <td><?= htmlspecialchars($med['description']) ?></td>
      <td>$<?= number_format($med['price'], 2) ?></td>
      <td><?= (int)$med['stock'] ?></td>
      <td>
        <a href="?page=admin_edit&id=<?= (int)$med['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
        <a href="../actions/admin_delete.php?id=<?= (int)$med['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this medicine?')">Delete</a>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<h3>Add New Medicine</h3>
<form method="POST" action="../actions/admin_add.php">
  <div class="mb-3">
    <label>Name</label>
    <input type="text" name="name" class="form-control" required>
  </div>
  <div class="mb-3">
    <label>Description</label>
    <textarea name="description" class="form-control" required></textarea>
  </div>
  <div class="mb-3">
    <label>Price</label>
    <input type="number" name="price" step="0.01" class="form-control" required>
  </div>
  <div class="mb-3">
    <label>Stock</label>
    <input type="number" name="stock" class="form-control" required>
  </div>
  <button type="submit" class="btn btn-primary">Add Medicine</button>
</form>
