<?php
session_start();

// Only allow staff users
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header('Location: ../auth/login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Staff Panel</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
  body { min-height: 100vh; }
  .sidebar { width: 220px; }
  .sidebar a:hover { text-decoration: underline; }
</style>
</head>
<body>
<div class="d-flex">
  <!-- Sidebar -->
  <div class="bg-secondary text-white p-3 sidebar">
    <h4 class="text-center">Staff Panel</h4>
    <ul class="nav flex-column mt-4">
      <li class="nav-item"><a href="dashboard.php" class="nav-link text-white">Dashboard</a></li>
      <li class="nav-item"><a href="manage_students.php" class="nav-link text-white">Manage Students</a></li>
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
