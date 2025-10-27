<?php
require '../config/db_conn.php';
include __DIR__ . '/admin_header.php';

$id = intval($_GET['id']);
$staff = $conn->query("SELECT * FROM users WHERE id=$id AND role='staff'")->fetch_assoc();

if (!$staff) {
    echo "<div class='alert alert-danger'>Staff not found!</div>";
    exit;
}

if (isset($_POST['update'])) {
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = !empty($_POST['password'])
        ? password_hash($_POST['password'], PASSWORD_DEFAULT)
        : $staff['password'];

    // Check for duplicate username or email (excluding this user)
    $check = $conn->query("SELECT * FROM users WHERE (username='$username' OR email='$email') AND id!=$id");
    if ($check->num_rows > 0) {
        echo "<div class='alert alert-danger'>Username or Email already exists!</div>";
    } else {
        $sql = "UPDATE users SET username='$username', email='$email', password='$password' WHERE id=$id";
        if ($conn->query($sql)) {
            header("Location: manage_staff.php");
            exit;
        } else {
            echo "<div class='alert alert-danger'>Error updating: " . $conn->error . "</div>";
        }
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h2>Edit Staff</h2>
  <a href="manage_staff.php" class="btn btn-secondary">← Back</a>
</div>

<form method="POST" class="border p-4 rounded bg-light shadow-sm">
  <div class="mb-3">
    <label class="form-label">Username</label>
    <input type="text" name="username" value="<?= htmlspecialchars($staff['username']) ?>" class="form-control" required>
  </div>

  <div class="mb-3">
    <label class="form-label">Email</label>
    <input type="email" name="email" value="<?= htmlspecialchars($staff['email']) ?>" class="form-control" required>
  </div>

  <div class="mb-3">
    <label class="form-label">New Password <small class="text-muted">(leave blank to keep current)</small></label>
    <input type="password" name="password" class="form-control">
  </div>

  <button type="submit" name="update" class="btn btn-primary">Update</button>
  <a href="manage_staff.php" class="btn btn-secondary">Cancel</a>
</form>

</div>
</body>
</html>
