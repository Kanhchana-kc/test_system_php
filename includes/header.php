<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location:../../../pages/login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>People Management System</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <!-- Bootstrap Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <!-- Logo + Brand Name -->
            <a class="navbar-brand" href="../index.php">
                PMS
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link <?php if(basename($_SERVER['PHP_SELF'])=='index.php'){echo 'active';} ?>" href="../index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php if(basename($_SERVER['PHP_SELF'])=='person-list.php'){echo 'active';} ?>" href="../person-list.php">People</a>
                    </li>

                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item">
                            <a class="nav-link <?php if(basename($_SERVER['PHP_SELF'])=='add-person.php'){echo 'active';} ?>" href="../add-person.php">Add Person</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../pages/logout.php">Logout</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link <?php if(basename($_SERVER['PHP_SELF'])=='login.php'){echo 'active';} ?>" href="../pages/login.php">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php if(basename($_SERVER['PHP_SELF'])=='register.php'){echo 'active';} ?>" href="../pages/register.php">Register</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
