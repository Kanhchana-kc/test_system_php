<?php
require '../config/db_conn.php';

// Delete person if delete ID exists
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM people WHERE id=$id");
    header("Location: person-list.php");
    exit;
}
else {
    // If no ID, redirect back
    header("Location: person-list.php");
}
