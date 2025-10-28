<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../../app/functions.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - Adu-in Aja</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card:hover {
            box-shadow: 0 0 30px rgba(255, 193, 7, 0.4);
            transform: translateY(-4px);
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-white px-4 py-3" style="margin: 0 70px;">
        <div class="container-fluid">
            <a class="navbar-brand text-warning fw-bold fst-italic fs-4" href="admin_dashboard.php">
            Adu-in Aja
            </a>
            <div class="collapse navbar-collapse justify-content-end" id="navbarAdmin">
                <ul class="navbar-nav">
                    <li class="nav-item px-3">
                    <a class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'u    ser_dashboard.php' ? 'active text-warning fw-semibold' : '' ?>" href="user_dashboard.php">Beranda</a>
                    </li>
                    <li class="nav-item px-3">
                    <a class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'buat_aduan.php' ? 'active text-warning fw-semibold' : '' ?>" href="buat_aduan.php">Buat Aduan</a>
                    </li>
                    <li class="nav-item px-3">
                    <a class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'daftar_aduan.php' ? 'active text-warning fw-semibold' : '' ?>" href="daftar_aduan.php">Aduan Saya</a>
                    </li>
                    <li class="nav-item px-3">
                    <a class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'aduan_publik.php' ? 'active text-warning fw-semibold' : '' ?>" href="aduan_publik.php">Aduan Publik</a>
                    </li>
                    <li class="nav-item ps-3">
                    <a class="nav-link text-danger fw-semibold" href="../logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

</body>
</html>
