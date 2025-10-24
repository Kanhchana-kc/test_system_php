<?php
session_start();

// Only allow admin access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../auth/login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Panel</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
  body { min-height: 100vh; }
  .sidebar { width: 220px; min-height: 100vh; }
  .sidebar .nav-link.active { background-color: #0d6efd; color: #fff; }
</style>
</head>
<body>
<div class="d-flex">
  <!-- Sidebar -->
  <div class="bg-dark text-white p-3 sidebar">
    <h4 class="text-center">Admin Panel</h4>
    <ul class="nav flex-column mt-4">
      <li class="nav-item"><a href="dashboard.php" class="nav-link text-white <?= basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : '' ?>">Dashboard</a></li>
      <li class="nav-item"><a href="manage_users.php" class="nav-link text-white <?= basename($_SERVER['PHP_SELF']) == 'manage_users.php' ? 'active' : '' ?>">Manage Users</a></li>
      <li class="nav-item"><a href="../auth/logout.php" class="nav-link text-white">Logout</a></li>
    </ul>
  </div>

  <!-- Main content -->
  <div class="flex-grow-1 p-4">
    <nav class="navbar navbar-expand navbar-light bg-light mb-4">
      <div class="container-fluid">
        <span class="navbar-text ms-auto">Hello, <?= htmlspecialchars($_SESSION['username']) ?></span>
      </div>
    </nav>
