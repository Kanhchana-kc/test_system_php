<?php
require '../config/db_conn.php';
session_start();

$message = '';

if (isset($_POST['register'])) {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $message = "Passwords do not match!";
    } else {
        $check = $conn->query("SELECT * FROM users WHERE username='$username'");
        if ($check->num_rows > 0) {
            $message = "Username already taken!";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (username, password) VALUES ('$username', '$hashed_password')";
            if ($conn->query($sql)) {
                $message = "Registration successful! <a href='login.php'>Login here</a>";
            } else {
                $message = "Error: " . $conn->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5" style="max-width: 500px;">
    <h2 class="mb-4">Register</h2>
    <?php if ($message): ?>
        <div class="alert alert-info"><?= $message ?></div>
    <?php endif; ?>
    <form method="post">
        <div class="mb-3">
            <label>Username</label>
            <input type="text" name="username" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Confirm Password</label>
            <input type="password" name="confirm_password" class="form-control" required>
        </div>
        <button type="submit" name="register" class="btn btn-primary w-100">Register</button>
    </form>
    <p class="mt-3">Already have an account? <a href="login.php">Login here</a></p>
</div>
</body>
</html>
