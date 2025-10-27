<?php

session_start();

require_once __DIR__ . '/../config/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../public/login.php');
    exit;
}

$email = $_POST['email'];
$password = $_POST['password'];

// validasi input
if ($email === '' || $password === '') {
    header('Location: ../public/login.php?error=' . urlencode('Email & password wajib diisi'));
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: ../public/login.php?error=' . urlencode('Email tidak valid'));
    exit;
}

$stmt = mysqli_prepare($koneksi, "SELECT user_id, username, password_hash, role FROM user WHERE email = ? LIMIT 1");
if (!$stmt) {
    // koneksi / query error
    header('Location: ../public/login.php?error=' . urlencode('Terjadi kesalahan server'));
    exit;
}
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);

if (mysqli_stmt_num_rows($stmt) === 0) {
    mysqli_stmt_close($stmt);
    header('Location: ../public/login.php?error=' . urlencode('Email atau password salah'));
    exit;
}

mysqli_stmt_bind_result($stmt, $user_id, $username, $password_hash, $role);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

// verifikasi password
if (!password_verify($password, $password_hash)) {
    header('Location: ../public/login.php?error=' . urlencode('Email atau password salah'));
    exit;
}

// berhasil login: regenerasi session id dan set session vars
session_regenerate_id(true);

$_SESSION['user_id'] = (int)$user_id;
$_SESSION['user_name'] = $username;
$_SESSION['user_email'] = $email;
$_SESSION['user_role'] = $role;

if ($role === 'Admin') {
    header('Location: ../public/admin/admin_dashboard.php');
    exit;
} else {
    header('Location: ../public/user/user_dashboard.php');
    exit;
}