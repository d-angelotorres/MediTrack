<h2>Register</h2>
<form method="POST" action="/actions/register.php">
  <div class="mb-3">
    <label>Username</label>
    <input type="text" name="username" class="form-control" required>
  </div>
  <div class="mb-3">
    <label>Password</label>
    <input type="password" name="password" class="form-control" required>
  </div>
  <button type="submit" class="btn btn-primary">Register</button>
  <a href="?page=login" class="btn btn-link">Already have an account? Login</a>
</form>
