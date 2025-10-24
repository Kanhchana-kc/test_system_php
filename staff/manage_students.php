<?php
require '../config/db_conn.php';
include __DIR__ . '/staff_header.php';

$staffId = intval($_SESSION['user_id']);
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

$query = "SELECT * FROM people WHERE created_by=$staffId
          AND (first_name LIKE '%$search%' OR last_name LIKE '%$search%')
          ORDER BY id DESC";
$result = $conn->query($query);
?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h2>My Students</h2>
  <a href="add_student.php" class="btn btn-primary">Add Student</a>
</div>

<form method="GET" class="mb-3">
  <div class="input-group">
    <input type="text" name="search" class="form-control" placeholder="Search students..." value="<?= htmlspecialchars($search) ?>">
    <button class="btn btn-secondary" type="submit">Search</button>
  </div>
</form>

<table class="table table-bordered table-hover text-center align-middle">
  <thead class="table-dark">
    <tr>
      <th>Image</th>
      <th>First Name</th>
      <th>Last Name</th>
      <th>Grade</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
    <?php if ($result && $result->num_rows > 0): ?>
      <?php while($student = $result->fetch_assoc()): ?>
        <tr>
          <td>
            <?php if (!empty($student['image'])): ?>
              <img src="../uploads/<?= htmlspecialchars($student['image']) ?>" width="50" height="50">
            <?php else: ?>
              No Image
            <?php endif; ?>
          </td>
          <td><a href="view_student.php?id=<?= $student['id'] ?>"><?= htmlspecialchars($student['first_name']) ?></a></td>
          <td><?= htmlspecialchars($student['last_name']) ?></td>
          <td><?= htmlspecialchars($student['grade']) ?></td>
          <td>
            <a href="edit_student.php?id=<?= $student['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
            <a href="delete_student.php?id=<?= $student['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this student?')">Delete</a>
          </td>
        </tr>
      <?php endwhile; ?>
    <?php else: ?>
      <tr><td colspan="5">No students found.</td></tr>
    <?php endif; ?>
  </tbody>
</table>

</div></div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
