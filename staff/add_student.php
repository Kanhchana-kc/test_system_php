<?php
require '../config/db_conn.php';
include __DIR__ . '/staff_header.php';

$error = '';
if (isset($_POST['save'])) {
    $first = $conn->real_escape_string($_POST['first_name']);
    $last = $conn->real_escape_string($_POST['last_name']);
    $grade = $conn->real_escape_string($_POST['grade']);
    $staffId = intval($_SESSION['user_id']);

    $sql = "INSERT INTO students (first_name, last_name, grade, created_by) 
            VALUES ('$first', '$last', '$grade', $staffId)";
    if ($conn->query($sql)) {
        header("Location: manage_students.php");
        exit;
    } else {
        $error = $conn->error;
    }
}
?>

<h2>Add Student</h2>
<?php if($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
<form method="POST">
  <div class="mb-3">
    <label>First Name</label>
    <input type="text" name="first_name" class="form-control" required>
  </div>
  <div class="mb-3">
    <label>Last Name</label>
    <input type="text" name="last_name" class="form-control" required>
  </div>
  <div class="mb-3">
    <label>Grade</label>
    <input type="text" name="grade" class="form-control" required>
  </div>
  <button type="submit" name="save" class="btn btn-success">Save</button>
  <a href="manage_students.php" class="btn btn-secondary">Cancel</a>
</form>

</div></div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
