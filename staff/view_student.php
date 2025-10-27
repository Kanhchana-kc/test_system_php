<?php
require '../config/db_conn.php';
include 'staff_header.php';

$staff_id = $_SESSION['user_id'];

if (!isset($_GET['id'])) die('Student ID missing.');
$id = intval($_GET['id']);

$stmt = $conn->prepare("SELECT * FROM people WHERE id = ? AND created_by = ?");
$stmt->bind_param("ii", $id, $staff_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) die('Student not found.');
$student = $result->fetch_assoc();
$stmt->close();
?>

<div class="container mt-4">
    <h2>Student Details</h2>
    <div class="card mx-auto" style="max-width:600px;">
        <div class="card-body text-center">
            <?php
            $img = "../uploads/".$student['image'];
            if (!empty($student['image']) && file_exists($img)) {
                echo '<img src="'.htmlspecialchars($img).'" class="img-thumbnail mb-3" style="width:150px;">';
            } else {
                echo '<img src="../uploads/default.png" class="img-thumbnail mb-3" style="width:150px;">';
            }
            ?>
            <h4><?= htmlspecialchars($student['first_name'].' '.$student['last_name']) ?></h4>
            <p><strong>Gender:</strong> <?= htmlspecialchars($student['gender']) ?></p>
            <p><strong>Role:</strong> <?= htmlspecialchars($student['role']) ?></p>
            <p><strong>Grade:</strong> <?= htmlspecialchars($student['grade']) ?></p>
            <p><strong>Village:</strong> <?= htmlspecialchars($student['village']) ?></p>
            <p><strong>Commune:</strong> <?= htmlspecialchars($student['commune']) ?></p>
            <p><strong>District:</strong> <?= htmlspecialchars($student['district']) ?></p>
            <p><strong>Province:</strong> <?= htmlspecialchars($student['province']) ?></p>
            <a href="manage_students.php" class="btn btn-secondary mt-3">Back to List</a>
        </div>
    </div>
</div>
