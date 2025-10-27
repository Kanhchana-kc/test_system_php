<?php
require '../config/db_conn.php';
include __DIR__ . '/admin_header.php';

// Handle Delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM users WHERE id=$id AND role='staff'");
    header("Location: manage_staff.php");
    exit;
}

// Fetch all staff users
$result = $conn->query("SELECT * FROM users WHERE role='staff' ORDER BY id DESC");
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Manage Staff</h2>
    <a href="add_staff.php" class="btn btn-primary">Add New Staff</a>
</div>

<table class="table table-bordered table-hover text-center align-middle">
    <thead class="table-dark">
        <tr>
            <th>#</th>
            <th>Username</th>
            <th>Email</th>
            <th>Created Date</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
    <?php if ($result && $result->num_rows > 0): ?>
        <?php $no = 1; while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $no++ ?></td>
            <td><?= htmlspecialchars($row['username']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td><?= htmlspecialchars($row['created_at'] ?? '') ?></td>
            <td>
                <a href="edit_staff.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                <a href="?delete=<?= $row['id'] ?>" class="btn btn-sm btn-danger"
                   onclick="return confirm('Delete this staff account?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr><td colspan="5">No staff found.</td></tr>
    <?php endif; ?>
    </tbody>
</table>

</div>
</body>
</html>
