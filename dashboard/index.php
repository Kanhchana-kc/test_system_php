<?php
require '../config/db_conn.php';
include '../includes/header.php';

// Total people
$total = $conn->query("SELECT COUNT(*) AS total FROM people")->fetch_assoc()['total'];

// Gender count
$male = $conn->query("SELECT COUNT(*) AS total FROM people WHERE gender='Male'")->fetch_assoc()['total'];
$female = $conn->query("SELECT COUNT(*) AS total FROM people WHERE gender='Female'")->fetch_assoc()['total'];

// Role count
$students = $conn->query("SELECT COUNT(*) AS total FROM people WHERE role='Student'")->fetch_assoc()['total'];
$teachers = $conn->query("SELECT COUNT(*) AS total FROM people WHERE role='Teacher'")->fetch_assoc()['total'];
$staffs = $conn->query("SELECT COUNT(*) AS total FROM people WHERE role='Staff'")->fetch_assoc()['total'];

// Grade count
$grade10 = $conn->query("SELECT COUNT(*) AS total FROM people WHERE grade='10'")->fetch_assoc()['total'];
$grade11 = $conn->query("SELECT COUNT(*) AS total FROM people WHERE grade='11'")->fetch_assoc()['total'];
$grade12 = $conn->query("SELECT COUNT(*) AS total FROM people WHERE grade='12'")->fetch_assoc()['total'];
?>

<div class="container">
    <h2 class="text-center mb-4">📊 Dashboard</h2>
    <div class="row g-3 text-center">
        <div class="col-md-3">
            <div class="card bg-info text-white shadow">
                <div class="card-body">
                    <h5>Total People</h5>
                    <h2><?= $total ?></h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-primary text-white shadow">
                <div class="card-body">
                    <h5>Male</h5>
                    <h2><?= $male ?></h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-danger text-white shadow">
                <div class="card-body">
                    <h5>Female</h5>
                    <h2><?= $female ?></h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-success text-white shadow">
                <div class="card-body">
                    <h5>Students</h5>
                    <h2><?= $students ?></h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-warning text-dark shadow">
                <div class="card-body">
                    <h5>Teachers</h5>
                    <h2><?= $teachers ?></h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-secondary text-white shadow">
                <div class="card-body">
                    <h5>Staff</h5>
                    <h2><?= $staffs ?></h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-light border shadow">
                <div class="card-body">
                    <h5>Grade 10</h5>
                    <h2><?= $grade10 ?></h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-light border shadow">
                <div class="card-body">
                    <h5>Grade 11</h5>
                    <h2><?= $grade11 ?></h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
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

<?php include '../includes/footer.php'; ?>