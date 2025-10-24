<?php
require '../config/db_conn.php';
include '../includes/admin_header.php';
$message = '';

if (isset($_POST['save'])) {
    $username = $conn->real_escape_string($_POST['username']);
    $full_name = $conn->real_escape_string($_POST['full_name']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];
    $role = $conn->real_escape_string($_POST['role']);

    $check = $conn->query("SELECT * FROM users WHERE username='$username'");
    if ($check->num_rows > 0) {
        $message = "Username already exists!";
    } else {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $conn->query("INSERT INTO users (username, full_name, email, password, role)
                      VALUES ('$username','$full_name','$email','$hashed','$role')");
        header("Location: manage_users.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container mt-5">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Add User</h4>
            </div>
            <div class="card-body">

                <?php if ($message): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($message) ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="full_name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select name="role" class="form-select">
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>

                    <button type="submit" name="save" class="btn btn-success">Save</button>
                    <a href="manage_users.php" class="btn btn-secondary ms-2">Back</a>
                </form>

            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>