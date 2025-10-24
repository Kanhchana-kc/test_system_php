<?php
require '../config/db_conn.php';
include '../includes/header.php';

// Get person ID from URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<div class='alert alert-danger'>Person not found!</div>";
    exit;
}

$id = intval($_GET['id']);

// Fetch person from database
$stmt = $conn->prepare("SELECT * FROM people WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "<div class='alert alert-danger'>Person not found!</div>";
    exit;
}

$person = $result->fetch_assoc();
?>

<div class="container mt-5">
    <h2>👤 Person Details</h2>
    <div class="card" style="max-width: 600px;">
        <div class="card-body text-center">
            <?php if ($person['image'] && file_exists("../uploads/".$person['image'])): ?>
                <img src="../uploads/<?= htmlspecialchars($person['image']) ?>" alt="Image" class="img-thumbnail mb-3" style="width: 150px; height: 150px;">
            <?php else: ?>
                <img src="../uploads/default.png" alt="No Image" class="img-thumbnail mb-3" style="width: 150px; height: 150px;">
            <?php endif; ?>

            <h4><?= htmlspecialchars($person['first_name'] . ' ' . $person['last_name']) ?></h4>
            <p><strong>Gender:</strong> <?= htmlspecialchars($person['gender']) ?></p>
            <p><strong>Role:</strong> <?= htmlspecialchars($person['role']) ?></p>
            <p><strong>Grade:</strong> <?= htmlspecialchars($person['grade']) ?></p>
            <p><strong>Village:</strong> <?= htmlspecialchars($person['village']) ?></p>
            <p><strong>Commune:</strong> <?= htmlspecialchars($person['commune']) ?></p>
            <p><strong>District:</strong> <?= htmlspecialchars($person['district']) ?></p>
            <p><strong>Province:</strong> <?= htmlspecialchars($person['province']) ?></p>

            <a href="person-list.php" class="btn btn-secondary mt-3">Back to List</a>
        </div>
    </div>
</div>
