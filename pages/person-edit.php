<?php
require '../config/db_conn.php';
include '../includes/header.php';

$id = intval($_GET['id']);
$result = $conn->query("SELECT * FROM people WHERE id=$id");
$row = $result->fetch_assoc();

if (isset($_POST['update'])) {
  $first_name = $conn->real_escape_string($_POST['first_name']);
  $last_name = $conn->real_escape_string($_POST['last_name']);
  $gender = $_POST['gender'];
  $role = $_POST['role'];
  $grade = $_POST['grade'];
  $village = $conn->real_escape_string($_POST['village']);
  $commune = $conn->real_escape_string($_POST['commune']);
  $district = $conn->real_escape_string($_POST['district']);
  $province = $conn->real_escape_string($_POST['province']);

  // Check for duplicates
  $check = $conn->query("SELECT * FROM people 
                           WHERE first_name='$first_name' 
                             AND last_name='$last_name' 
                             AND village='$village' 
                             AND commune='$commune' 
                             AND district='$district' 
                             AND province='$province' 
                             AND id != $id");

  if ($check->num_rows > 0) {
    echo "<div class='alert alert-danger'>❌ This person already exists!</div>";
  } else {
    $conn->query("UPDATE people SET 
                    first_name='$first_name', 
                    last_name='$last_name',
                    gender='$gender', 
                    role='$role',
                    grade='$grade',
                    village='$village', 
                    commune='$commune', 
                    district='$district', 
                    province='$province' 
                    WHERE id=$id");
    header("Location: person-list.php");
    exit;
  }
}
?>





<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Edit Person</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
  <div class="container mt-5">
    <h2>កែប្រែព័ត៌មានបុគ្គល</h2>
    <form method="POST">
      <div class="mb-3">
        <label>First Name:</label>
        <input type="text" name="first_name" value="<?= $row['first_name'] ?>" class="form-control" required>
      </div>

      <div class="mb-3">
        <label>Last Name:</label>
        <input type="text" name="last_name" value="<?= $row['last_name'] ?>" class="form-control" required>
      </div>


      <div class="mb-3">
        <label>ភេទ:</label><br>
        <input type="radio" name="gender" value="Male" <?= $row['gender'] == 'Male' ? 'checked' : '' ?>> បុរស
        <input type="radio" name="gender" value="Female" class="ms-3" <?= $row['gender'] == 'Female' ? 'checked' : '' ?>>
        ស្រី
      </div>

      <div class="mb-3">
        <label>តួនាទី:</label>
        <select name="role" class="form-control">
          <option value="Student" <?= $row['role'] == 'Student' ? 'selected' : '' ?>>សិស្ស (Student)</option>
          <option value="Teacher" <?= $row['role'] == 'Teacher' ? 'selected' : '' ?>>គ្រូបង្រៀន (Teacher)</option>
          <option value="Staff" <?= $row['role'] == 'Staff' ? 'selected' : '' ?>>បុគ្គលិក (Staff)</option>
        </select>
      </div>

      <div class="mb-3">
        <label>ថ្នាក់:</label>
        <select name="grade" class="form-control">
          <option value="N/A" <?= $row['grade'] == 'N/A' ? 'selected' : '' ?>>N/A</option>
          <option value="10" <?= $row['grade'] == '10' ? 'selected' : '' ?>>ថ្នាក់ទី ១០</option>
          <option value="11" <?= $row['grade'] == '11' ? 'selected' : '' ?>>ថ្នាក់ទី ១១</option>
          <option value="12" <?= $row['grade'] == '12' ? 'selected' : '' ?>>ថ្នាក់ទី ១២</option>
        </select>
      </div>

      <div class="mb-3">
        <label>ភូមិ:</label>
        <input type="text" name="village" value="<?= $row['village'] ?>" class="form-control">
      </div>
      <div class="mb-3">
        <label>ឃុំ:</label>
        <input type="text" name="commune" value="<?= $row['commune'] ?>" class="form-control">
      </div>
      <div class="mb-3">
        <label>ស្រុក:</label>
        <input type="text" name="district" value="<?= $row['district'] ?>" class="form-control">
      </div>
      <div class="mb-3">
        <label>ខេត្ត:</label>
        <input type="text" name="province" value="<?= $row['province'] ?>" class="form-control">
      </div>

      <button type="submit" name="update" class="btn btn-primary">Update</button>
      <a href="index.php" class="btn btn-secondary">Cancel</a>
    </form>
  </div>
</body>

</html>