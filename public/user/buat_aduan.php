<?php
require_once __DIR__ . '/../../app/auth.php';
require_once __DIR__ . '/../../app/functions.php';
require_once __DIR__ . '/../../config/config.php';

if (session_status() === PHP_SESSION_NONE) session_start();
require_login();

$user_id = (int)($_SESSION['user_id'] ?? 0);

if (isset($_POST['submit'])) {
    $judul = mysqli_real_escape_string($koneksi, $_POST['judul']);
    $deskripsi = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);

    $query = "INSERT INTO laporan (user_id, judul, deskripsi, status, created_at, update_at)
              VALUES ('$user_id', '$judul', '$deskripsi', 'Diterima', NOW(), NOW())";

    if (mysqli_query($koneksi, $query)) {
        echo "<script>alert('Aduan berhasil dikirim!'); window.location='daftar_aduan.php';</script>";
    } else {
        echo "<div class='alert alert-danger text-center mt-3'>Gagal mengirim aduan.</div>";
    }
}

include __DIR__ . '/_user_header.php';
?>

<main class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
      <div class="card border-0 rounded-4 shadow-lg p-5">
        <div class="text-center mb-5">
          <h5 class="fw-semibold text-black">Buat Aduan Baru</h5>
        </div>
        <form method="POST">
          <div class="mb-3">
            <label class="form-label fw-semibold">Judul Aduan</label>
            <input 
              type="text" 
              name="judul" 
              class="form-control" 
              placeholder="Masukkan judul aduan" 
              required>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Deskripsi Aduan</label>
            <textarea 
              name="deskripsi" 
              class="form-control" 
              rows="4" 
              placeholder="Tuliskan deskripsi aduan" 
              required></textarea>
          </div>

          <div class="d-grid">
            <button type="submit" name="submit" class="btn btn-warning text-white fw-semibold py-2">
              Kirim Aduan
            </button>
          </div>
        </form>
    </div>
  </div>
</main>

<style>
  body {
    background-color: #f8f9fa;
  }
  .form-control {
    background-color: #e5e5e5ff;
    border-color: #dee2e6;
    transition: all 0.2s ease-in-out;
  }
  .form-control:focus {
    background-color: #fff;
    border-color: #ffc107;
    box-shadow: 0 0 0 .15rem rgba(255,193,7,.25);
  }
  .btn-warning:hover {
    background-color: #e0a800;
  }
</style>

</body>
</html>
