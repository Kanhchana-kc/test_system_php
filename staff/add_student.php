<?php
// Show errors for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// session_start();
require '../config/db_conn.php';
include 'staff_header.php';

if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['staff', 'super_admin'])) {
  header('Location: ../auth/login.php');
  exit;
}

$staff_id = $_SESSION['user_id'];
$message = "";

// Generate Student ID
function generateStudentID($conn)
{
  $prefix = "NOBILI";
  $sql = "SELECT student_id FROM students ORDER BY id DESC LIMIT 1";
  $result = $conn->query($sql);

  if ($result && $row = $result->fetch_assoc()) {
    $last_id = intval(substr($row['student_id'], strlen($prefix)));
    $new_id = $last_id + 1;
  } else {
    $new_id = 1;
  }
  return $prefix . str_pad($new_id, 7, "0", STR_PAD_LEFT);
}

$student_id = generateStudentID($conn);

// Handle form submission
if (isset($_POST['save'])) {
  $first_name = $conn->real_escape_string($_POST['first_name']);
  $last_name = $conn->real_escape_string($_POST['last_name']);
  $gender = $_POST['gender'];
  $role = $conn->real_escape_string($_POST['role']);
  $grade = $conn->real_escape_string($_POST['grade']);
  $phone = $conn->real_escape_string($_POST['phone']);
  $email = $conn->real_escape_string($_POST['email']);
  $village = $conn->real_escape_string($_POST['village']);
  $commune = $conn->real_escape_string($_POST['commune']);
  $district = $conn->real_escape_string($_POST['district']);
  $province = $conn->real_escape_string($_POST['province']);
  $note = $conn->real_escape_string($_POST['note']);
  $student_id = $_POST['student_id'];

  // Convert DD/MM/YYYY to YYYY-MM-DD
  $dob_input = $_POST['dob'];
  $dob = !empty($dob_input) ? DateTime::createFromFormat('d/m/Y', $dob_input)->format('Y-m-d') : null;

  $image_name = null;
  if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $tmp_name = $_FILES['image']['tmp_name'];
    $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
    $allowed_exts = ['jpg', 'jpeg', 'png'];
    if (in_array($ext, $allowed_exts)) {
      $image_name = uniqid("student_") . '.' . $ext;
      move_uploaded_file($tmp_name, "../uploads/$image_name");
    } else {
      $message = "<div class='alert alert-danger'>❌ Only JPG, JPEG, PNG allowed.</div>";
    }
  }

  if (empty($message)) {
    $stmt = $conn->prepare("INSERT INTO students 
            (student_id, first_name, last_name, gender, role, grade, dob, phone, email, village, commune, district, province, note, image, created_by)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param(
      "sssssssssssssssi",
      $student_id,
      $first_name,
      $last_name,
      $gender,
      $role,
      $grade,
      $dob,
      $phone,
      $email,
      $village,
      $commune,
      $district,
      $province,
      $note,
      $image_name,
      $staff_id
    );

    if ($stmt->execute()) {
      $message = "<div class='alert alert-success'>✅ Student added successfully! ID: $student_id</div>";
      $student_id = generateStudentID($conn);
    } else {
      $message = "<div class='alert alert-danger'>❌ Database Error: " . htmlspecialchars($stmt->error) . "</div>";
    }
    $stmt->close();
  }
}
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<div class="container">
  <div class="card mx-auto mt-4 shadow" style="max-width:850px;">
    <div class="card-body">
      <h3 class="card-title mb-3 text-primary">Add New Student</h3>
      <?= $message ?>

      <form method="post" enctype="multipart/form-data">
        <div class="row g-3">
          <div class="col-md-4">
            <label>Student ID</label>
            <input type="text" name="student_id" class="form-control" value="<?= htmlspecialchars($student_id) ?>"
              readonly>
          </div>

          <div class="col-md-4">
            <label>First Name</label>
            <input type="text" name="first_name" class="form-control" required>
          </div>

          <div class="col-md-4">
            <label>Last Name</label>
            <input type="text" name="last_name" class="form-control" required>
          </div>

          <div class="col-md-4">
            <label>Gender</label>
            <select name="gender" class="form-control" required>
              <option value="">Select Gender</option>
              <option value="Male">Male</option>
              <option value="Female">Female</option>
            </select>
          </div>

          <div class="col-md-4">
            <label>Role</label>
            <select name="role" class="form-control">
              <option value="Student" selected>Student</option>
              <option value="Prefect">Prefect</option>
              <option value="Leader">Leader</option>
            </select>
          </div>

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

          <div class="col-md-4">
            <label>Date of Birth</label>
            <input type="text" id="dob" name="dob" class="form-control" placeholder="DD/MM/YYYY">
          </div>

          <div class="col-md-4">
            <label>Phone</label>
            <input type="text" name="phone" class="form-control">
          </div>

          <div class="col-md-4">
            <label>Email</label>
            <input type="email" name="email" class="form-control">
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
            <label>Profile Image</label>
            <input type="file" name="image" class="form-control" accept=".jpg,.jpeg,.png">
          </div>

          <div class="col-md-12">
            <label>Note / Description</label>
            <textarea name="note" class="form-control" rows="3"></textarea>
          </div>
        </div>

        <div class="mt-4">
          <button type="submit" name="save" class="btn btn-success px-4">Save</button>
          <a href="manage_students.php" class="btn btn-secondary px-4">Back</a>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
  flatpickr("#dob", {
    dateFormat: "d/m/Y",
    maxDate: "today",
    defaultDate: new Date().fp_incr(-365 * 18) // 18 years ago
  });
</script>

<?php include 'staff_footer.php'; ?>