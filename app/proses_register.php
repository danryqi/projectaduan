<?php

require_once __DIR__ . '/../config/config.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('Location: ../public/register.php');
  exit;
}

$username = ($_POST['username']);
$email = ($_POST['email']);
$password = ($_POST['password']);


if ($username === '' || $email === '' || $password === '') {
  header('Location: ../public/register.php?error=' . urlencode('Semua field wajib diisi'));
  exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  header('Location: ../public/register.php?error=' . urlencode('Email tidak valid'));
  exit;
}

$password_hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = mysqli_prepare($koneksi, "INSERT INTO user (username, email, password_hash, role) VALUES (?, ?, ?, 'User')");
if (!$stmt) {
  header('Location: ../public/register.php?error=' . urlencode('Terjadi kesalahan server'));
  exit;
}

mysqli_stmt_bind_param($stmt, "sss", $username, $email, $password_hash);
// ok berarti akun tersebut kita masukin ya atau eksekusi
$ok = mysqli_stmt_execute($stmt);
// pemrosesan akun
mysqli_stmt_close($stmt);
// tutup koneksi
mysqli_close($koneksi);

if ($ok) {
  // kalau misal dia itu berhasil di daftar
  header('Location: ../public/login.php?registered=1');
  exit;
} else {
  // enggak berhasil daftar akun
  header('Location: ../public/register.php?error=' . urlencode('Gagal membuat akun'));
  exit;
}