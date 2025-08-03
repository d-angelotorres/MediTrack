<?php
// Start session and check admin like before
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['username'])) {
    header("Location: ?page=login");
    exit;
}

require_once __DIR__ . '/../includes/db.php';

// Get medicine ID from GET param
if (empty($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<p>Invalid medicine ID.</p>";
    exit;
}

$medicine_id = (int) $_GET['id'];

// Fetch medicine data
$stmt = $pdo->prepare("SELECT * FROM medicines WHERE id = ?");
$stmt->execute([$medicine_id]);
$medicine = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$medicine) {
    echo "<p>Medicine not found.</p>";
    exit;
}

// Display any flash messages
if (!empty($_SESSION['error'])) {
    echo '<div class="alert alert-danger">' . htmlspecialchars($_SESSION['error']) . '</div>';
    unset($_SESSION['error']);
}
if (!empty($_SESSION['success'])) {
    echo '<div class="alert alert-success">' . htmlspecialchars($_SESSION['success']) . '</div>';
    unset($_SESSION['success']);
}
?>

<h2>Edit Medicine</h2>

<form method="POST" action="../actions/admin_edit.php">
  <input type="hidden" name="id" value="<?= (int)$medicine['id'] ?>">
  <div class="mb-3">
    <label>Name</label>
    <input type="text" name="name" class="form-control" required value="<?= htmlspecialchars($medicine['name']) ?>">
  </div>
  <div class="mb-3">
    <label>Description</label>
    <textarea name="description" class="form-control" required><?= htmlspecialchars($medicine['description']) ?></textarea>
  </div>
  <div class="mb-3">
    <label>Price</label>
    <input type="number" step="0.01" name="price" class="form-control" required value="<?= htmlspecialchars($medicine['price']) ?>">
  </div>
  <div class="mb-3">
    <label>Stock</label>
    <input type="number" name="stock" class="form-control" required value="<?= (int)$medicine['stock'] ?>">
  </div>
  <button type="submit" class="btn btn-primary">Save Changes</button>
  <a href="?page=admin" class="btn btn-secondary">Cancel</a>
</form>
