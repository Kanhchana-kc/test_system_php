<?php
require '../config/db_conn.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../auth/login.php');
    exit;
}

$id = intval($_GET['id']);
$conn->query("DELETE FROM users WHERE id=$id AND role='staff'");
header("Location: manage_staff.php");
exit;
