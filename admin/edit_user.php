<?php
require '../config/db_conn.php';
include '../admin/admin_header.php';

$id = intval($_GET['id']);
$userQuery = $conn->query("SELECT * FROM users WHERE id=$id");
$user = $userQuery->fetch_assoc();

if (!$user) {
    echo "<div class='alert alert-danger m-3'>User not found!</div>";
    exit;
}

if (isset($_POST['update'])) {
    $full_name = $conn->real_escape_string($_POST['full_name']);
    $email = $conn->real_escape_string($_POST['email']);
    $role = $conn->real_escape_string($_POST['role']);

    $conn->query("UPDATE users SET full_name='$full_name', email='$email', role='$role' WHERE id=$id");

    echo "<script>alert('User updated successfully!'); window.location='manage_users.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <!-- ✅ Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container mt-5">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Edit User</h4>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="full_name" class="form-control"
                            value="<?= htmlspecialchars($user['full_name']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control"
                            value="<?= htmlspecialchars($user['email']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select name="role" class="form-select">
                            <option value="user" <?= $user['role'] == 'user' ? 'selected' : '' ?>>User</option>
                            <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                        </select>
                    </div>

                    <button type="submit" name="update" class="btn btn-success">Update User</button>
                    <a href="manage_users.php" class="btn btn-secondary ms-2">Back</a>
                </form>
            </div>
        </div>
    </div>

    <!-- ✅ Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>