<?php
// Show all PHP errors for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require __DIR__ . '/../config/db_conn.php';

// Ensure only staff can access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'staff') {
    header('Location: ../auth/login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

// Initialize total students
$total_students = 0;

// Fetch total students safely
try {
    $stmt = $conn->prepare("SELECT COUNT(*) AS total FROM people WHERE created_by = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $total_students = $result->fetch_assoc()['total'] ?? 0;
    $stmt->close();
} catch (Exception $e) {
    $error_message = $e->getMessage();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Staff Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Welcome, <?= htmlspecialchars($username) ?> 👋</h2>
        <a href="../auth/logout.php" class="btn btn-danger btn-sm">Logout</a>
    </div>

    <?php if (!empty($error_message)): ?>
        <div class="alert alert-danger">Error: <?= htmlspecialchars($error_message) ?></div>
    <?php endif; ?>

    <div class="row g-3">
        <div class="col-md-4">
            <div class="card text-white bg-primary shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title">My Students</h5>
                    <p class="card-text fs-2"><?= $total_students ?></p>
                    <a href="manage_students.php" class="btn btn-light btn-sm">Manage Students</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-white bg-success shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title">Add Student</h5>
                    <p class="card-text fs-2">+</p>
                    <a href="add_student.php" class="btn btn-light btn-sm">Add</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-white bg-warning shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title">Profile</h5>
                    <p class="card-text fs-2"><i class="bi bi-person-circle"></i></p>
                    <a href="profile.php" class="btn btn-light btn-sm">View Profile</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
