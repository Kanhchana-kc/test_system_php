<?php
session_start();
require '../config/db_conn.php';

// Only allow admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../auth/login.php');
    exit;
}

// Count total users
$totalUsers = $conn->query("SELECT COUNT(*) AS total FROM users")->fetch_assoc()['total'];

// Count total staff
$totalStaff = $conn->query("SELECT COUNT(*) AS total FROM users WHERE role='staff'")->fetch_assoc()['total'];

// Count total students
$totalStudents = $conn->query("SELECT COUNT(*) AS total FROM students")->fetch_assoc()['total'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <a class="navbar-brand" href="#">Admin Dashboard</a>
  <div class="ms-auto">
    <span class="navbar-text text-white me-3">Hello, <?= $_SESSION['username'] ?></span>
    <a href="../auth/logout.php" class="btn btn-outline-light btn-sm">Logout</a>
  </div>
</nav>

<div class="container mt-4">
    <div class="row g-3">
        <!-- Users Card -->
        <div class="col-md-4">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h5 class="card-title">Total Users</h5>
                    <p class="card-text fs-2"><?= $totalUsers ?></p>
                    <a href="manage_users.php" class="btn btn-light btn-sm">Manage Users</a>
                </div>
            </div>
        </div>
        <!-- Staff Card -->
        <div class="col-md-4">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5 class="card-title">Total Staff</h5>
                    <p class="card-text fs-2"><?= $totalStaff ?></p>
                    <a href="manage_staff.php" class="btn btn-light btn-sm">Manage Staff</a>
                </div>
            </div>
        </div>
        <!-- Students Card -->
        <div class="col-md-4">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <h5 class="card-title">Total Students</h5>
                    <p class="card-text fs-2"><?= $totalStudents ?></p>
                    <a href="manage_students.php" class="btn btn-light btn-sm">Manage Students</a>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
