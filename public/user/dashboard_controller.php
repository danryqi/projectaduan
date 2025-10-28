<?php
include __DIR__ . '/../../config/config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Ambil ID user yang sedang login
$user_id  = (int)($_SESSION['user_id'] ?? 0);


// Jika user belum login, redirect
if (!$user_id) {
    header('Location: ../login.php');
    exit;
}

$username = 'User'; // fallback

if ($user_id > 0) {
    $sql = "SELECT username FROM user WHERE user_id = $user_id LIMIT 1";
    $res = mysqli_query($koneksi, $sql);
    if ($res && ($row = mysqli_fetch_assoc($res))) {
        $username = htmlspecialchars($row['username']);
    }
}



$result_aduan = mysqli_query($koneksi, "SELECT COUNT(*) AS total_aduan FROM laporan WHERE user_id = '$user_id'");
$data_aduan = mysqli_fetch_assoc($result_aduan);
$total_aduan = $data_aduan['total_aduan'] ?? 0;


// Diterima
$result_diterima = mysqli_query($koneksi, "SELECT COUNT(*) AS total_diterima FROM laporan WHERE user_id = '$user_id' AND status = 'Diterima'");
$data_diterima = mysqli_fetch_assoc($result_diterima);
$total_diterima = $data_diterima['total_diterima'] ?? 0;

// Ditanggapi
$result_tanggapi = mysqli_query($koneksi, "SELECT COUNT(*) AS total_tanggapi FROM laporan WHERE user_id = '$user_id' AND status = 'Ditanggapi'");
$data_tanggapi = mysqli_fetch_assoc($result_tanggapi);
$total_tanggapi = $data_tanggapi['total_tanggapi'] ?? 0;

// Selesai
$result_selesai = mysqli_query($koneksi, "SELECT COUNT(*) AS total_selesai FROM laporan WHERE user_id = '$user_id' AND status = 'Selesai'");
$data_selesai = mysqli_fetch_assoc($result_selesai);
$total_selesai = $data_selesai['total_selesai'] ?? 0;

// Ditolak
$result_ditolak = mysqli_query($koneksi, "SELECT COUNT(*) AS total_ditolak FROM laporan WHERE user_id = '$user_id' AND status = 'Ditolak'");
$data_ditolak = mysqli_fetch_assoc($result_ditolak);
$total_ditolak = $data_ditolak['total_ditolak'] ?? 0;
