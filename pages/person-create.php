<?php
require '../config/db_conn.php';
include '../includes/header.php';

$error = ''; // variable to store error message

if (isset($_POST['save'])) {
    $first_name = $conn->real_escape_string($_POST['first_name']);
    $last_name = $conn->real_escape_string($_POST['last_name']);
    $gender = $_POST['gender'];
    $role = $_POST['role'];
    $grade = $_POST['grade'];
    $village = $conn->real_escape_string($_POST['village']);
    $commune = $conn->real_escape_string($_POST['commune']);
    $district = $conn->real_escape_string($_POST['district']);
    $province = $conn->real_escape_string($_POST['province']);

    // Handle image upload
    $image = '';
    if (!empty($_FILES['image']['name'])) {
        $image_name = time() . '_' . basename($_FILES['image']['name']);
        $target_dir = "../uploads/";
        if (!file_exists($target_dir)) mkdir($target_dir, 0777, true);
        $target_file = $target_dir . $image_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png','gif'];

        if (!in_array($imageFileType, $allowed)) {
            $error = "❌ Only JPG, JPEG, PNG & GIF files are allowed.";
        } elseif (!move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $error = "❌ Failed to upload image!";
        } else {
            $image = $image_name;
        }
    }

    // Check for duplicates
    if (!$error) {
        $check = $conn->query("SELECT * FROM people 
                               WHERE first_name='$first_name' 
                               AND last_name='$last_name' 
                               AND village='$village' 
                               AND commune='$commune' 
                               AND district='$district' 
                               AND province='$province'");
        if ($check->num_rows > 0) {
            $error = "❌ This person already exists!";
        } else {
            $conn->query("INSERT INTO people (first_name, last_name, gender, role, grade, village, commune, district, province, image)
                          VALUES ('$first_name', '$last_name', '$gender', '$role', '$grade', '$village', '$commune', '$district', '$province', '$image')");
            header("Location: person-list.php");
            exit;
        }
    }
}
?>

<div class="container mt-5">
    <h2>➕ បន្ថែមបុគ្គលថ្មី</h2>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">

        <div class="mb-3">
            <label>First Name:</label>
            <input type="text" name="first_name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Last Name:</label>
            <input type="text" name="last_name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>ភេទ:</label><br>
            <input type="radio" name="gender" value="Male" required> បុរស
            <input type="radio" name="gender" value="Female" class="ms-3"> ស្រី
        </div>

        <div class="mb-3">
            <label>តួនាទី:</label>
            <select name="role" class="form-control" required>
                <option value="Student">សិស្ស (Student)</option>
                <option value="Teacher">គ្រូបង្រៀន (Teacher)</option>
                <option value="Staff">បុគ្គលិក (Staff)</option>
            </select>
        </div>

        <div class="mb-3">
            <label>ថ្នាក់:</label>
            <select name="grade" class="form-control">
                <option value="N/A">N/A</option>
                <option value="10">ថ្នាក់ទី ១០</option>
                <option value="11">ថ្នាក់ទី ១១</option>
                <option value="12">ថ្នាក់ទី ១២</option>
            </select>
        </div>

        <div class="mb-3">
            <label>ភូមិ:</label>
            <input type="text" name="village" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>ឃុំ:</label>
            <input type="text" name="commune" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>ស្រុក:</label>
            <input type="text" name="district" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>ខេត្ត:</label>
            <input type="text" name="province" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>រូបភាព:</label>
            <input type="file" name="image" class="form-control" accept="image/*">
        </div>

        <button type="submit" name="save" class="btn btn-success">Save</button>
        <a href="person-list.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
