<?php
// Show flash messages and then clear them
if (!empty($_SESSION['success'])) {
    echo '<div class="alert alert-success">' . htmlspecialchars($_SESSION['success']) . '</div>';
    unset($_SESSION['success']);
}
if (!empty($_SESSION['error'])) {
    echo '<div class="alert alert-danger">' . htmlspecialchars($_SESSION['error']) . '</div>';
    unset($_SESSION['error']);
}

// Check if logged in
if (empty($_SESSION['username'])) {
    echo "<p>Please <a href='?page=login'>log in</a> to place orders.</p>";
    return;
}

// Fetch medicines from DB
$stmt = $pdo->query("SELECT * FROM medicines WHERE stock > 0 ORDER BY name ASC");
$medicines = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Order Medicines</h2>

<?php if (count($medicines) === 0): ?>
  <p>No medicines available at the moment.</p>
<?php else: ?>
  <table class="table table-striped">
    <thead>
      <tr>
        <th>Name</th><th>Description</th><th>Price</th><th>Stock</th><th>Order</th>
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
          <form method="POST" action="../actions/place_order.php" style="display:inline;">
            <input type="hidden" name="medicine_id" value="<?= (int)$med['id'] ?>">
            <input type="number" name="quantity" min="1" max="<?= (int)$med['stock'] ?>" value="1" style="width:60px;">
            <button type="submit" class="btn btn-sm btn-primary">Order</button>
          </form>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
<?php endif; ?>
