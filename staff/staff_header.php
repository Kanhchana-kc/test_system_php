<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
  <div class="container">
    <a class="navbar-brand" href="manage_students.php">Staff Dashboard</a>
    <div class="d-flex">
      <span class="text-white me-3">Hello, <?= htmlspecialchars($_SESSION['username']) ?></span>
      <a href="../auth/logout.php" class="btn btn-danger btn-sm">Logout</a>
    </div>
  </div>
</nav>
