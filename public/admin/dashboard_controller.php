<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include __DIR__ . '/../../config/config.php';

require_admin();

$user_id  = (int)($_SESSION['user_id'] ?? 0);
$username = 'Admin'; // fallback

if ($user_id > 0) {
    $sql = "SELECT username FROM user WHERE user_id = $user_id LIMIT 1";
    $res = mysqli_query($koneksi, $sql);
    if ($res && ($row = mysqli_fetch_assoc($res))) {
        $username = htmlspecialchars($row['username']);
    }
}

$result_user = mysqli_query($koneksi, "SELECT COUNT(*) AS total_user FROM user");
$data_user = mysqli_fetch_assoc($result_user);
$total_user = $data_user['total_user'] ?? 0;

// total aduan (misal tabel: aduan)
$result_aduan = mysqli_query($koneksi, "SELECT COUNT(*) AS total_aduan FROM laporan");
$data_aduan = mysqli_fetch_assoc($result_aduan);
$total_aduan = $data_aduan['total_aduan'] ?? 0;

// total aduan selesai (status = 'selesai')
$result_selesai = mysqli_query($koneksi, "SELECT COUNT(*) AS total_selesai FROM laporan WHERE status = 'Selesai'");
$data_selesai = mysqli_fetch_assoc($result_selesai);
$total_selesai = $data_selesai['total_selesai'] ?? 0;

$result_proses = mysqli_query($koneksi, "SELECT COUNT(*) AS total_diterima FROM laporan WHERE status = 'Diterima'");
$data_diterima = mysqli_fetch_assoc($result_proses);
$total_diterima = $data_diterima['total_diterima'] ?? 0;

$result_proses = mysqli_query($koneksi, "SELECT COUNT(*) AS total_ditanggapi FROM laporan WHERE status = 'Ditanggapi'");
$data_ditanggapi = mysqli_fetch_assoc($result_proses);
$total_ditanggapi = $data_ditanggapi['total_ditanggapi'] ?? 0;

$result_proses = mysqli_query($koneksi, "SELECT COUNT(*) AS total_ditolak FROM laporan WHERE status = 'Ditolak'");
$data_ditolak = mysqli_fetch_assoc($result_proses);
$total_ditolak = $data_ditolak['total_ditolak'] ?? 0;