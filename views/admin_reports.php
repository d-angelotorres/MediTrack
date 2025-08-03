<?php
require_once __DIR__ . '/../includes/db.php';

// Sales summary: total sold & orders per medicine
$salesStmt = $pdo->prepare("
    SELECT
      m.name,
      SUM(o.quantity) AS total_sold,
      COUNT(o.id) AS order_count
    FROM orders o
    JOIN medicines m ON o.medicine_id = m.id
    WHERE o.status IN ('Processing', 'Shipped', 'Completed')
    GROUP BY m.id, m.name
    ORDER BY total_sold DESC
");
$salesStmt->execute();
$sales = $salesStmt->fetchAll();

// Stock alerts: medicines with stock < 10
$stockStmt = $pdo->prepare("
    SELECT name, stock
    FROM medicines
    WHERE stock < 10
    ORDER BY stock ASC
");
$stockStmt->execute();
$lowStock = $stockStmt->fetchAll();

// Top 5 best-selling medicines by quantity sold
$topStmt = $pdo->prepare("
    SELECT
      m.name,
      SUM(o.quantity) AS total_sold
    FROM orders o
    JOIN medicines m ON o.medicine_id = m.id
    WHERE o.status IN ('Processing', 'Shipped', 'Completed')
    GROUP BY m.id, m.name
    ORDER BY total_sold DESC
    LIMIT 5
");
$topStmt->execute();
$topSelling = $topStmt->fetchAll();
?>

<h2>Admin Reports & Analytics</h2>

<section>
  <h3>Sales Summary (All Time)</h3>
  <?php if ($sales): ?>
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Medicine</th>
          <th>Total Quantity Sold</th>
          <th>Number of Orders</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($sales as $row): ?>
          <tr>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= $row['total_sold'] ?></td>
            <td><?= $row['order_count'] ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php else: ?>
    <p>No sales data available.</p>
  <?php endif; ?>
</section>

<section>
  <h3>Stock Alerts (Low Inventory)</h3>
  <?php if ($lowStock): ?>
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Medicine</th>
          <th>Current Stock</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($lowStock as $row): ?>
          <tr>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><strong style="color: red;"><?= $row['stock'] ?></strong></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php else: ?>
    <p>All medicines have sufficient stock.</p>
  <?php endif; ?>
</section>

<section>
  <h3>Top 5 Best-Selling Medicines</h3>
  <?php if ($topSelling): ?>
    <ol>
      <?php foreach ($topSelling as $row): ?>
        <li><?= htmlspecialchars($row['name']) ?> — <?= $row['total_sold'] ?> units sold</li>
      <?php endforeach; ?>
    </ol>
  <?php else: ?>
    <p>No sales data available.</p>
  <?php endif; ?>
</section>
