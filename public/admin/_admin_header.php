<?php
if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/../../app/functions.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Adu-in Aja</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
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
                    <a class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'admin_dashboard.php' ? 'active text-warning fw-semibold' : '' ?>" href="admin_dashboard.php">Beranda</a>
                    </li>
                    <li class="nav-item px-3">
                    <a class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'data_laporan.php' ? 'active text-warning fw-semibold' : '' ?>" href="data_laporan.php">Data Laporan</a>
                    </li>
                    <li class="nav-item px-3">
                    <a class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'data_pengguna.php' ? 'active text-warning fw-semibold' : '' ?>" href="data_pengguna.php">Pengguna</a>
                    </li>
                    <li class="nav-item px-3">
                    <a class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'laporan_selesai.php' ? 'active text-warning fw-semibold' : '' ?>" href="laporan_selesai.php">Laporan Selesai</a>
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
