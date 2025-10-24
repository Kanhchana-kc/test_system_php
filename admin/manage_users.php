<?php
require __DIR__ . '/../config/db_conn.php';
include __DIR__ . '/admin_header.php';

$result = $conn->query("SELECT * FROM users ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Management</title>

  <!-- ✅ Add Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2>User Management</h2>
    <a href="add_user.php" class="btn btn-primary">Add User</a>
  </div>

  <table class="table table-bordered table-hover text-center shadow-sm bg-white">
    <thead class="table-dark">
      <tr>
        <th>ID</th>
        <th>Username</th>
        <th>Full Name</th>
        <th>Email</th>
        <th>Role</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php while($user = $result->fetch_assoc()): ?>
      <tr>
        <td><?= $user['id'] ?></td>
        <td><?= htmlspecialchars($user['username']) ?></td>
        <td><?= htmlspecialchars($user['full_name']) ?></td>
        <td><?= htmlspecialchars($user['email']) ?></td>
        <td><span class="badge bg-secondary"><?= ucfirst($user['role']) ?></span></td>
        <td>
          <a href="edit_user.php?id=<?= $user['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
          <a href="delete_user.php?id=<?= $user['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this user?');">Delete</a>
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>

<!-- ✅ Add Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
