<?php
session_start();
require '../config/db_conn.php';

// ✅ Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: ../auth/login.php');

    exit;
}


// ✅ Get total counts
$total = $conn->query("SELECT COUNT(*) AS total FROM people")->fetch_assoc()['total'];

// ✅ Gender counts
$genderCounts = $conn->query("SELECT gender, COUNT(*) AS total FROM people GROUP BY gender");
$male = $female = 0;
while ($row = $genderCounts->fetch_assoc()) {
    if ($row['gender'] == 'Male') $male = $row['total'];
    if ($row['gender'] == 'Female') $female = $row['total'];
}

// ✅ Role counts
$roleCounts = $conn->query("SELECT role, COUNT(*) AS total FROM people GROUP BY role");
$students = $teachers = $staffs = 0;
while ($row = $roleCounts->fetch_assoc()) {
    if ($row['role'] == 'Student') $students = $row['total'];
    if ($row['role'] == 'Teacher') $teachers = $row['total'];
    if ($row['role'] == 'Staff') $staffs = $row['total'];
}

// ✅ Grade counts
$gradeCounts = $conn->query("SELECT grade, COUNT(*) AS total FROM people GROUP BY grade");
$grade10 = $grade11 = $grade12 = 0;
while ($row = $gradeCounts->fetch_assoc()) {
    if ($row['grade'] == '10') $grade10 = $row['total'];
    if ($row['grade'] == '11') $grade11 = $row['total'];
    if ($row['grade'] == '12') $grade12 = $row['total'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
  <!-- ✅ Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<!-- ✅ Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="#">People Management</a>
    <div class="d-flex">
      <span class="text-white me-3">Welcome, <?= htmlspecialchars($_SESSION['username']) ?> 👋</span>
      <a href="../auth/logout.php" class="btn btn-outline-light btn-sm">Logout</a>
    </div>
  </div>
</nav>

<div class="container">
  <h2 class="text-center mb-4">📊 Dashboard Overview</h2>

  <div class="row g-3 text-center">
    <div class="col-md-3 col-sm-6">
      <div class="card bg-info text-white shadow">
        <div class="card-body">
          <h5>Total People</h5>
          <h2><?= $total ?></h2>
        </div>
      </div>
    </div>
    <div class="col-md-3 col-sm-6">
      <div class="card bg-primary text-white shadow">
        <div class="card-body">
          <h5>Male</h5>
          <h2><?= $male ?></h2>
        </div>
      </div>
    </div>
    <div class="col-md-3 col-sm-6">
      <div class="card bg-danger text-white shadow">
        <div class="card-body">
          <h5>Female</h5>
          <h2><?= $female ?></h2>
        </div>
      </div>
    </div>
    <div class="col-md-3 col-sm-6">
      <div class="card bg-success text-white shadow">
        <div class="card-body">
          <h5>Students</h5>
          <h2><?= $students ?></h2>
        </div>
      </div>
    </div>
    <div class="col-md-3 col-sm-6">
      <div class="card bg-warning text-dark shadow">
        <div class="card-body">
          <h5>Teachers</h5>
          <h2><?= $teachers ?></h2>
        </div>
      </div>
    </div>
    <div class="col-md-3 col-sm-6">
      <div class="card bg-secondary text-white shadow">
        <div class="card-body">
          <h5>Staff</h5>
          <h2><?= $staffs ?></h2>
        </div>
      </div>
    </div>
    <div class="col-md-3 col-sm-6">
      <div class="card bg-light border shadow">
        <div class="card-body">
          <h5>Grade 10</h5>
          <h2><?= $grade10 ?></h2>
        </div>
      </div>
    </div>
    <div class="col-md-3 col-sm-6">
      <div class="card bg-light border shadow">
        <div class="card-body">
          <h5>Grade 11</h5>
          <h2><?= $grade11 ?></h2>
        </div>
      </div>
    </div>
    <div class="col-md-3 col-sm-6">
      <div class="card bg-light border shadow">
        <div class="card-body">
          <h5>Grade 12</h5>
          <h2><?= $grade12 ?></h2>
        </div>
      </div>
    </div>
  </div>

  <div class="text-center mt-4">
    <a href="../pages/person-list.php" class="btn btn-primary">👥 Manage People</a>
  </div>
</div>

<!-- ✅ Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
