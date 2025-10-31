<?php
require '../config/db_conn.php';
include 'staff_header.php';

$staff_id = $_SESSION['user_id'] ?? null;
if (!$staff_id) {
    header('Location: ../auth/login.php');
    exit;
}

if (!isset($_GET['id'])) die('Student ID missing.');
$id = intval($_GET['id']);

// Fetch student created by this staff
$stmt = $conn->prepare("SELECT * FROM students WHERE id = ? AND created_by = ?");
$stmt->bind_param("ii", $id, $staff_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) die('Student not found.');
$student = $result->fetch_assoc();
$stmt->close();

$student_id_display = $student['student_id'] ?? 'NOBILI0000000';
?>

<div class="container mt-4">
    <div class="card mx-auto shadow p-3" style="max-width:600px;">
        <div class="text-center">
            <!-- Logo -->
            <img src="../assets/nobili_logo.png" alt="NOBILI Logo" style="width:120px; margin-bottom:10px;">
        </div>
        <div class="card-header text-center bg-primary text-white">
            <h4><?= htmlspecialchars($student['first_name'] . ' ' . $student['last_name']) ?></h4>
            <small>Student ID: <strong><?= htmlspecialchars($student_id_display) ?></strong></small>
        </div>
        <div class="card-body text-center">
            <?php
            $img_path = "../uploads/" . ($student['image'] ?? '');
            if (!empty($student['image']) && file_exists($img_path)) {
                echo '<img src="' . htmlspecialchars($img_path) . '" class="img-thumbnail mb-3" style="width:150px;">';
            } else {
                echo '<img src="../uploads/default.png" class="img-thumbnail mb-3" style="width:150px;">';
            }
            ?>
            <div class="row text-start mt-3">
                <div class="col-6"><strong>Gender:</strong> <?= htmlspecialchars($student['gender']) ?></div>
                <div class="col-6"><strong>Role:</strong> <?= htmlspecialchars($student['role']) ?></div>
                <div class="col-6 mt-2"><strong>Grade:</strong> <?= htmlspecialchars($student['grade']) ?></div>
                <div class="col-6 mt-2"><strong>DOB:</strong> <?= !empty($student['dob']) ? date('d/m/Y', strtotime($student['dob'])) : '-' ?></div>
                <div class="col-6 mt-2"><strong>Phone:</strong> <?= htmlspecialchars($student['phone'] ?? '-') ?></div>
                <div class="col-6 mt-2"><strong>Email:</strong> <?= htmlspecialchars($student['email'] ?? '-') ?></div>
                <div class="col-6 mt-2"><strong>Village:</strong> <?= htmlspecialchars($student['village'] ?? '-') ?></div>
                <div class="col-6 mt-2"><strong>Commune:</strong> <?= htmlspecialchars($student['commune'] ?? '-') ?></div>
                <div class="col-6 mt-2"><strong>District:</strong> <?= htmlspecialchars($student['district'] ?? '-') ?></div>
                <div class="col-6 mt-2"><strong>Province:</strong> <?= htmlspecialchars($student['province'] ?? '-') ?></div>
                <div class="col-12 mt-2"><strong>Note:</strong> <?= htmlspecialchars($student['note'] ?? '-') ?></div>
            </div>
            <div class="mt-4 d-flex justify-content-between">
                <a href="manage_students.php" class="btn btn-secondary">Back to List</a>
                <button onclick="window.print()" class="btn btn-success">Print Card</button>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    body * { visibility: hidden; }
    .card, .card * { visibility: visible; }
    .card { position: absolute; left: 0; top: 0; width: 100%; }
}
</style>

<?php include 'staff_footer.php'; ?>
