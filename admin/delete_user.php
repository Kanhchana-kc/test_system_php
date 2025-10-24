<?php
require '../config/db_conn.php';
$id = intval($_GET['id']);
$conn->query("DELETE FROM users WHERE id=$id");
header("Location: manage_users.php");
exit;
