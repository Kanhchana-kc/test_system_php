<?php
session_start();
require '../config/db_conn.php';

// Ensure only staff can access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'staff') {
    header('Location: ../auth/login.php');
    exit;
}

$staff_id = $_SESSION['user_id'];
$message = '';

if (!isset($_GET['id'])) {
    die('Student ID is missing!');
}

$id = intval($_GET['id']);

// Fetch student
$stmt = $conn->prepare("SELECT * FROM people WHERE id = ? AND created_by = ?");
$stmt->bind_param("ii", $id, $staff_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    die('Student not found or you do not have permission.');
}
$student = $result->fetch_assoc();
$stmt->close();

// Handle update
if (isset($_POST['update'])) {
    $first_name = $conn->real_escape_string($_POST['first_name']);
    $last_name = $conn->real_escape_string($_POST['last_name']);
    $gender = $_POST['gender'];
    $role = $_POST['role'];
    $grade = $conn->real_escape_string($_POST['grade']);
    $village = $conn->real_escape_string($_POST['village']);
    $commune = $conn->real_escape_string($_POST['commune']);
    $district = $conn->real_escape_string($_POST['district']);
    $province = $conn->real_escape_string($_POST['province']);

    // Handle image upload
    $image_name = $student['image'];
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $tmp_name = $_FILES['image']['tmp_name'];
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $image_name = uniqid() . '.' . $ext;
        move_uploaded_file($tmp_name, "../uploads/$image_name");
    }

    $stmt = $conn->prepare("UPDATE people SET first_name=?, last_name=?, gender=?, role=?, grade=?, village=?, commune=?, district=?, province=?, image=? WHERE id=? AND created_by=?");
    $stmt->bind_param("ssssssssssii", $first_name, $last_name, $gender, $role, $grade, $village, $commune, $district, $province, $image_name, $id, $staff_id);
    if ($stmt->execute()) {
        $message = "Student updated successfully!";
    } else {
        $message = "Error: " . $stmt->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Student</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Edit Student</h2>

    <?php if ($message): ?>
        <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form action="" method="post" enctype="multipart/form-data">
        <div class="row g-3">
            <div class="col-md-6">
                <label>First Name</label>
                <input type="text" name="first_name" class="form-control" value="<?= htmlspecialchars($student['first_name']) ?>" required>
            </div>
            <div class="col-md-6">
                <label>Last Name</label>
                <input type="text" name="last_name" class="form-control" value="<?= htmlspecialchars($student['last_name']) ?>" required>
            </div>
            <div class="col-md-4">
                <label>Gender</label>
                <select name="gender" class="form-control">
                    <option value="Male" <?= $student['gender']=='Male'?'selected':'' ?>>Male</option>
                    <option value="Female" <?= $student['gender']=='Female'?'selected':'' ?>>Female</option>
                </select>
            </div>
            <div class="col-md-4">
                <label>Role</label>
                <input type="text" name="role" class="form-control" value="<?= htmlspecialchars($student['role']) ?>">
            </div>
            <div class="col-md-4">
                <label>Grade</label>
                <input type="text" name="grade" class="form-control" value="<?= htmlspecialchars($student['grade']) ?>">
            </div>
            <div class="col-md-6">
                <label>Village</label>
                <input type="text" name="village" class="form-control" value="<?= htmlspecialchars($student['village']) ?>">
            </div>
            <div class="col-md-6">
                <label>Commune</label>
                <input type="text" name="commune" class="form-control" value="<?= htmlspecialchars($student['commune']) ?>">
            </div>
            <div class="col-md-6">
                <label>District</label>
                <input type="text" name="district" class="form-control" value="<?= htmlspecialchars($student['district']) ?>">
            </div>
            <div class="col-md-6">
                <label>Province</label>
                <input type="text" name="province" class="form-control" value="<?= htmlspecialchars($student['province']) ?>">
            </div>
            <div class="col-md-6">
                <label>Image</label>
                <input type="file" name="image" class="form-control">
                <?php if ($student['image'] && file_exists("../uploads/".$student['image'])): ?>
                    <img src="../uploads/<?= htmlspecialchars($student['image']) ?>" width="80" class="mt-2">
                <?php endif; ?>
            </div>
        </div>
        <button type="submit" name="update" class="btn btn-primary mt-3">Update Student</button>
        <a href="manage_students.php" class="btn btn-secondary mt-3">Back to List</a>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
