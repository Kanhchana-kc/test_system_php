<?php
require '../config/db_conn.php';
include __DIR__ . '/admin_header.php';

$message = '';

if (isset($_POST['create'])) {
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $plain_password = $_POST['password']; // Keep plain text password
    $password = password_hash($plain_password, PASSWORD_DEFAULT);

    // Check if username or email exists
    $exists = $conn->query("SELECT * FROM users WHERE username='$username' OR email='$email'");
    if ($exists->num_rows > 0) {
        $message = '<div class="alert alert-danger">Username or Email already exists!</div>';
    } else {
        $sql = "INSERT INTO users (username, email, password, role) VALUES ('$username', '$email', '$password', 'staff')";
        if ($conn->query($sql)) {
            // Show success message with username & password
            $message = '
                <div class="alert alert-success">
                    Staff created successfully!<br>
                    <strong>Username:</strong> ' . htmlspecialchars($username) . '<br>
                    <strong>Email:</strong> ' . htmlspecialchars($email) . '<br>
                    <strong>Password:</strong> ' . htmlspecialchars($plain_password) . '
                </div>';
        } else {
            $message = '<div class="alert alert-danger">Error: ' . $conn->error . '</div>';
        }
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h2>Add New Staff</h2>
  <a href="manage_staff.php" class="btn btn-secondary">← Back</a>
</div>

<?= $message ?>

<form method="POST">
  <div class="mb-3">
    <label>Username</label>
    <input type="text" name="username" class="form-control" required>
  </div>

  <div class="mb-3">
    <label>Email</label>
    <input type="email" name="email" class="form-control" required>
  </div>

  <div class="mb-3">
    <label>Password</label>
    <input type="text" name="password" class="form-control" required>
  </div>

  <button type="submit" name="create" class="btn btn-success">Create Staff</button>
  <a href="manage_staff.php" class="btn btn-secondary">Cancel</a>
</form>

</div>
</body>
</html>
