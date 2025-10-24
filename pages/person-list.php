<?php
require '../config/db_conn.php';
include '../includes/header.php';

// Delete person
if (isset($_GET['delete'])) {
  $id = $_GET['delete'];
  $conn->query("DELETE FROM people WHERE id=$id");
  header("Location: person-list.php");
  exit;
}

// Search
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$query = "SELECT * FROM people 
          WHERE first_name LIKE '%$search%' 
             OR last_name LIKE '%$search%' 
             OR grade LIKE '%$search%'
             OR province LIKE '%$search%'
             OR id LIKE '%$search%'
           ORDER BY id ASC";//ពីលើនេះជួសជុលទៅ ASC ដើម្បីបង្ហាញ ID ចាប់ពីតូចទៅធំ
// ORDER BY id DESC"; DESC ពីធំទៅតូច



$result = $conn->query($query);
?>

<div class="container">
  <h2>👥 People List</h2>

  <form method="GET" class="d-flex mb-3">
    <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" class="form-control me-2"
      placeholder="Search by first_name, last_name, grade, province, or ID">
    <button class="btn btn-primary">Search</button>
  </form>

  <a href="person-create.php" class="btn btn-success mb-3">➕ Add Person</a>
  <a href="../dashboard/index.php" class="btn btn-secondary mb-3">⬅ Back to Dashboard</a>

  <?php if ($result->num_rows > 0): ?>
    <table class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>រូបភាព</th>
          <th>First Name</th>
          <th>Last Name</th>
          <th>Gender</th>
          <th>Role</th>
          <th>Grade</th>
          <th>Location</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
          <?php $view_link = "person-view.php?id=" . $row['id']; ?>
          <tr>
            <td>
              <a href="<?= $view_link ?>">
                <?php if ($row['image'] && file_exists("../uploads/" . $row['image'])): ?>
                  <img src="../uploads/<?= htmlspecialchars($row['image']) ?>" width="50" height="50" alt="image">
                <?php else: ?>
                  No Image
                <?php endif; ?>
              </a>
            </td>
            <td><a href="<?= $view_link ?>"><?= htmlspecialchars($row['first_name']) ?></a></td>
            <td><a href="<?= $view_link ?>"><?= htmlspecialchars($row['last_name']) ?></a></td>
            <td><?= htmlspecialchars($row['gender']) ?></td>
            <td><?= htmlspecialchars($row['role']) ?></td>
            <td><?= htmlspecialchars($row['grade']) ?></td>
            <td><?= htmlspecialchars($row['village']) ?>, <?= htmlspecialchars($row['commune']) ?>,
              <?= htmlspecialchars($row['district']) ?>, <?= htmlspecialchars($row['province']) ?>
            </td>
            <td>
              <a href="person-edit.php?id=<?= $row['id'] ?>" class="btn btn-primary btn-sm">Edit</a>
              <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm"
                onclick="return confirm('Are you sure?')">Delete</a>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>

    </table>
  <?php else: ?>
    <div class="alert alert-warning">
      ⚠ No results found for "<strong><?= htmlspecialchars($search) ?></strong>".
    </div>
  <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>