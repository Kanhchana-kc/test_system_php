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
$error_message = "";

// Initialize totals
$total_students = $male_count = $female_count = 0;
$grade_counts = [];
$recent_students = [];

// ✅ Total students
try {
    $stmt = $conn->prepare("SELECT COUNT(*) AS total FROM people WHERE created_by = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $total_students = $result->fetch_assoc()['total'] ?? 0;
    $stmt->close();

    // ✅ Gender summary
    $stmt = $conn->prepare("SELECT gender, COUNT(*) AS total FROM people WHERE created_by = ? GROUP BY gender");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        if ($row['gender'] === 'Male') $male_count = $row['total'];
        if ($row['gender'] === 'Female') $female_count = $row['total'];
    }
    $stmt->close();

    // ✅ Grade summary
    $stmt = $conn->prepare("SELECT grade, COUNT(*) AS total FROM people WHERE created_by = ? GROUP BY grade");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $grade_counts[$row['grade']] = $row['total'];
    }
    $stmt->close();

    // ✅ Recent students
    $stmt = $conn->prepare("SELECT student_id, first_name, last_name, gender, grade, image, created_at 
                            FROM people WHERE created_by = ? ORDER BY created_at DESC LIMIT 5");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $recent_students = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
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
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            transition: all 0.2s ease;
            border-radius: 10px;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        .student-img {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            object-fit: cover;
        }
    </style>
</head>
<body>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>👋 Welcome, <?= htmlspecialchars($username) ?></h2>
        <a href="../auth/logout.php" class="btn btn-danger btn-sm">Logout</a>
    </div>

    <?php if (!empty($error_message)): ?>
        <div class="alert alert-danger">Error: <?= htmlspecialchars($error_message) ?></div>
    <?php endif; ?>

    <!-- Summary Cards -->
    <div class="row g-3">
        <div class="col-md-4">
            <div class="card text-white bg-primary shadow-sm text-center">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-people-fill"></i> My Students</h5>
                    <p class="card-text fs-2"><?= $total_students ?></p>
                    <a href="manage_students.php" class="btn btn-light btn-sm">Manage</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-white bg-success shadow-sm text-center">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-person-plus-fill"></i> Add Student</h5>
                    <p class="card-text fs-2">+</p>
                    <a href="add_student.php" class="btn btn-light btn-sm">Add</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-white bg-warning shadow-sm text-center">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-person-circle"></i> Profile</h5>
                    <p class="card-text fs-2"><i class="bi bi-person"></i></p>
                    <a href="profile.php" class="btn btn-light btn-sm">View</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Gender & Grade Summary -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card border-info">
                <div class="card-body">
                    <h5 class="card-title text-info">Student Gender Summary</h5>
                    <p>👦 Male: <strong><?= $male_count ?></strong></p>
                    <p>👧 Female: <strong><?= $female_count ?></strong></p>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-secondary">
                <div class="card-body">
                    <h5 class="card-title text-secondary">Students by Grade</h5>
                    <?php if ($grade_counts): ?>
                        <?php foreach ($grade_counts as $grade => $count): ?>
                            <p>Grade <?= htmlspecialchars($grade) ?>: <strong><?= $count ?></strong></p>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No students yet.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Students Table -->
    <div class="card mt-4 shadow-sm">
        <div class="card-body">
            <h5 class="card-title text-primary"><i class="bi bi-clock-history"></i> Recent Students</h5>
            <?php if ($recent_students): ?>
                <div class="table-responsive mt-3">
                    <table class="table table-striped align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>Photo</th>
                                <th>Student ID</th>
                                <th>Name</th>
                                <th>Gender</th>
                                <th>Grade</th>
                                <th>Added On</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($recent_students as $student): ?>
                            <tr>
                                <td>
                                    <?php if (!empty($student['image'])): ?>
                                        <img src="../uploads/<?= htmlspecialchars($student['image']) ?>" class="student-img" alt="Student">
                                    <?php else: ?>
                                        <img src="../assets/default-avatar.png" class="student-img" alt="Default">
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($student['student_id'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($student['first_name'] . ' ' . $student['last_name']) ?></td>
                                <td><?= htmlspecialchars($student['gender']) ?></td>
                                <td><?= htmlspecialchars($student['grade']) ?></td>
                                <td><?= htmlspecialchars(date("d M Y", strtotime($student['created_at']))) ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="mt-3 text-muted">No students added yet.</p>
            <?php endif; ?>
        </div>
    </div>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
