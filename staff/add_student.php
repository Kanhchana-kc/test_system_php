<?php
require '../config/db_conn.php';
include 'staff_header.php';

$staff_id = $_SESSION['user_id'];
$message = '';

if (isset($_POST['save'])) {
  $first_name = $conn->real_escape_string($_POST['first_name']);
  $last_name = $conn->real_escape_string($_POST['last_name']);
  $gender = $_POST['gender'];
  $role = $_POST['role'];
  $grade = $conn->real_escape_string($_POST['grade']);
  $village = $conn->real_escape_string($_POST['village']);
  $commune = $conn->real_escape_string($_POST['commune']);
  $district = $conn->real_escape_string($_POST['district']);
  $province = $conn->real_escape_string($_POST['province']);

  $image_name = null;
  if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $tmp_name = $_FILES['image']['tmp_name'];
    $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
    $image_name = uniqid() . '.' . $ext;
    move_uploaded_file($tmp_name, "../uploads/$image_name");
  }

  $stmt = $conn->prepare("INSERT INTO people (first_name,last_name,gender,role,grade,village,commune,district,province,image,created_by) VALUES (?,?,?,?,?,?,?,?,?,?,?)");
  $stmt->bind_param("ssssssssssi", $first_name, $last_name, $gender, $role, $grade, $village, $commune, $district, $province, $image_name, $staff_id);
  if ($stmt->execute())
    $message = "Student added successfully!";
  else
    $message = "Error: " . $stmt->error;
  $stmt->close();
}
?>

<div class="container">
  <div class="card mx-auto mt-4" style="max-width:700px;">
    <div class="card-body">
      <h3 class="card-title mb-3">Add Student</h3>
      <?php if ($message): ?>
        <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
      <?php endif; ?>
      <form method="post" enctype="multipart/form-data">
        <div class="row g-3">
          <div class="col-md-6">
            <label>First Name</label>
            <input type="text" name="first_name" class="form-control" required>
          </div>
          <div class="col-md-6">
            <label>Last Name</label>
            <input type="text" name="last_name" class="form-control" required>
          </div>
          <div class="col-md-4">
            <label>Gender</label>
            <select name="gender" class="form-control" required>
              <option value="Male">Male</option>
              <option value="Female">Female</option>
            </select>
          </div>
          <div class="col-md-4">
            <label>Role</label>
            <input type="text" name="role" class="form-control" value="Student">
          </div>

          <!-- <div class="col-md-4">
                        <label>Grade</label>
                        <input type="text" name="grade" class="form-control">
                    </div> -->

          <div class="col-md-4">
            <label>Grade</label>
            <select name="grade" class="form-control" required>
              <option value="">Select Grade</option>
              <option value="10">10</option>
              <option value="11">11</option>
              <option value="12">12</option>
              <option value="Other">Other</option>
            </select>
          </div>


          <div class="col-md-6">
            <label>Village</label>
            <input type="text" name="village" class="form-control">
          </div>
          <div class="col-md-6">
            <label>Commune</label>
            <input type="text" name="commune" class="form-control">
          </div>
          <div class="col-md-6">
            <label>District</label>
            <input type="text" name="district" class="form-control">
          </div>
          <div class="col-md-6">
            <label>Province</label>
            <input type="text" name="province" class="form-control">
          </div>
          <div class="col-md-6">
            <label>Image</label>
            <input type="file" name="image" class="form-control">
          </div>
        </div>
        <button type="submit" name="save" class="btn btn-success mt-3">Save</button>
        <a href="manage_students.php" class="btn btn-secondary mt-3">Back</a>
      </form>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>