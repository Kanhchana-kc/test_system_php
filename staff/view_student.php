<?php
require '../config/db_conn.php';
include 'staff_header.php';

$staff_id = $_SESSION['user_id'] ?? null;
if (!$staff_id) {
    header('Location: ../auth/login.php');
    exit;
}

if (!isset($_GET['id']))
    die('Student ID missing.');
$id = intval($_GET['id']);

// Fetch student created by this staff
$stmt = $conn->prepare("SELECT * FROM students WHERE id = ? AND created_by = ?");
$stmt->bind_param("ii", $id, $staff_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0)
    die('Student not found.');
$student = $result->fetch_assoc();
$stmt->close();

$student_id_display = $student['student_id'] ?? 'NOBILI0000000';
$img_path = "../uploads/" . ($student['image'] ?? 'default.png');
if (!file_exists($img_path))
    $img_path = "../uploads/default.png";
?>

<div class="container mt-5 mb-5">
    <div class="card mx-auto shadow-lg border-primary rounded-4" style="max-width:650px;">
        <!-- Header -->
        <div class="card-header text-center bg-primary text-white rounded-top d-flex flex-column align-items-center">
            <img src="../assets/images/logonobili.jpg" alt="NOBILI Logo" class="img-fluid mb-2" style="width:80px;">
            <h4 class="fw-bold mb-0"><?= htmlspecialchars($student['first_name'] . ' ' . $student['last_name']) ?></h4>
            <small>ID: <strong><?= htmlspecialchars($student_id_display) ?></strong></small>
        </div>

        <!-- Body -->
        <div class="card-body text-center">
            <!-- Student Image -->
            <img src="<?= htmlspecialchars($img_path) ?>" class="img-thumbnail rounded-circle mb-3 shadow-sm"
                style="width:150px; height:150px; object-fit:cover; cursor:pointer;" data-bs-toggle="modal"
                data-bs-target="#viewImage">

            <!-- Modal to view full image -->
            <div class="modal fade" id="viewImage" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered modal-sm">
                    <div class="modal-content border-0">
                        <img src="<?= htmlspecialchars($img_path) ?>" class="img-fluid rounded">
                    </div>
                </div>
            </div>

            <!-- Student Info -->
            <div class="row text-start mt-3 g-2">
                <div class="col-6"><strong>Gender:</strong> <?= htmlspecialchars($student['gender']) ?></div>
                <div class="col-6"><strong>Role:</strong> <?= htmlspecialchars($student['role']) ?></div>
                <div class="col-6"><strong>Grade:</strong> <?= htmlspecialchars($student['grade']) ?></div>
                <div class="col-6"><strong>DOB:</strong>
                    <?= !empty($student['dob']) ? date('d/m/Y', strtotime($student['dob'])) : '-' ?></div>
                <div class="col-6"><strong>Phone:</strong> <?= htmlspecialchars($student['phone'] ?? '-') ?></div>
                <div class="col-6"><strong>Email:</strong> <?= htmlspecialchars($student['email'] ?? '-') ?></div>
                <div class="col-6"><strong>Village:</strong> <?= htmlspecialchars($student['village'] ?? '-') ?></div>
                <div class="col-6"><strong>Commune:</strong> <?= htmlspecialchars($student['commune'] ?? '-') ?></div>
                <div class="col-6"><strong>District:</strong> <?= htmlspecialchars($student['district'] ?? '-') ?></div>
                <div class="col-6"><strong>Province:</strong> <?= htmlspecialchars($student['province'] ?? '-') ?></div>
                <div class="col-12"><strong>Note:</strong> <?= htmlspecialchars($student['note'] ?? '-') ?></div>
            </div>

            <!-- QR Code -->
            <div class="mt-4">
                <p class="fw-bold mb-1">Scan to View</p>
                <img src="https://api.qrserver.com/v1/create-qr-code/?data=<?= urlencode('https://yourdomain.com/staff/view_student.php?id=' . $student['id']) ?>&size=100x100"
                    alt="QR Code" class="border rounded">
            </div>

            <!-- Buttons -->
            <div class="d-flex justify-content-between mt-4 no-print">
                <a href="manage_students.php" class="btn btn-secondary"><i class="bi bi-arrow-left-circle"></i> Back</a>
                <button onclick="window.print()" class="btn btn-success"><i class="bi bi-printer"></i> Print</button>
            </div>
        </div>
    </div>
</div>

<!-- Print Styles -->
<style>
    @media print {
        body * {
            visibility: hidden;
        }

        .card,
        .card * {
            visibility: visible;
        }

        .no-print {
            display: none !important;
        }

        .card {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            border: none;
            box-shadow: none;
        }
    }
</style>

<?php include 'staff_footer.php'; ?>