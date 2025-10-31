<?php
require '../config/db_conn.php';
include 'staff_header.php';

$staff_id = $_SESSION['user_id'] ?? null;
if (!$staff_id) {
    header('Location: ../auth/login.php');
    exit;
}

// Handle deletion
if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM students WHERE id = ? AND created_by = ?");
    $stmt->bind_param("ii", $delete_id, $staff_id);
    if ($stmt->execute()) {
        echo "<div class='alert alert-success mt-3'>Student deleted successfully.</div>";
    } else {
        echo "<div class='alert alert-danger mt-3'>Error deleting student: " . $stmt->error . "</div>";
    }
    $stmt->close();
}

// Handle search
$search = $_GET['search'] ?? '';
$sql = "SELECT * FROM students WHERE created_by = ?";
$types = "i";
$params = [$staff_id];

if ($search !== '') {
    $sql .= " AND (first_name LIKE ? OR last_name LIKE ? OR grade LIKE ?)";
    $types .= "sss";
    $search_param = "%$search%";
    $params[] = $search_param;
    $params[] = $search_param;
    $params[] = $search_param;
}

$sql .= " ORDER BY id DESC";

$stmt = $conn->prepare($sql);

// Bind parameters by reference
$bind_names[] = $types;
for ($i = 0; $i < count($params); $i++) {
    $bind_name = 'bind' . $i;
    $$bind_name = $params[$i];
    $bind_names[] = &$$bind_name;
}
call_user_func_array([$stmt, 'bind_param'], $bind_names);

$stmt->execute();
$result = $stmt->get_result();
$students = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Manage Students</h3>
        <a href="add_student.php" class="btn btn-success"><i class="bi bi-plus-circle"></i> Add Student</a>
    </div>

    <!-- Search Form -->
    <form method="get" class="mb-3">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Search by name or grade"
                value="<?= htmlspecialchars($search) ?>">
            <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> Search</button>
        </div>
    </form>

    <table class="table table-hover table-bordered align-middle text-center">
        <thead class="table-dark">
            <tr>
                <th>Image</th>
                <th>Name</th>
                <th>Grade</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($students): ?>
                <?php foreach ($students as $s): ?>
                    <tr style="cursor:pointer;" onclick="window.location='view_student.php?id=<?= $s['id'] ?>'">
                        <td>
                            <?php
                            $img = "../uploads/" . ($s['image'] ?? '');
                            if (!empty($s['image']) && file_exists($img)) {
                                echo '<img src="' . htmlspecialchars($img) . '" class="rounded-circle" width="50" height="50">';
                            } else {
                                echo '<img src="../uploads/default.png" class="rounded-circle" width="50" height="50">';
                            }
                            ?>
                        </td>
                        <td><?= htmlspecialchars($s['first_name'] . ' ' . $s['last_name']) ?></td>
                        <td><span class="badge bg-success"><?= htmlspecialchars($s['grade']) ?></span></td>
                        <td><span class="badge bg-info"><?= htmlspecialchars($s['role']) ?></span></td>
                        <td>
                            <a href="edit_student.php?id=<?= $s['id'] ?>" class="btn btn-primary btn-sm"><i
                                    class="bi bi-pencil"></i></a>
                            <a href="manage_students.php?delete=<?= $s['id'] ?>" class="btn btn-danger btn-sm"
                                onclick="return confirm('Delete this student?');"><i class="bi bi-trash"></i></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">No students found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>