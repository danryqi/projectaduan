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
    background: linear-gradient(135deg, #fffdf5, #fffaf0);
    min-height: 100vh;
  }

  main {
    margin-top: 60px;
  }

  /* ===== Card ===== */
  .card {
    border: none;
    border-radius: 28px;
    background: linear-gradient(180deg, #ffffff, #fafafa);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
    transition: transform 0.4s cubic-bezier(0.22, 1, 0.36, 1),
                box-shadow 0.4s cubic-bezier(0.22, 1, 0.36, 1);
  }

  .card:hover {
    transform: translateY(-6px);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
  }

  .card h5 {
    color: #ffb300;
    font-weight: 700;
    letter-spacing: 0.5px;
  }

  /* ===== Input & Textarea ===== */
  .form-control {
    background-color: #f7f7f7;
    border: 1px solid #e2e2e2;
    border-radius: 14px;
    transition: all 0.25s ease-in-out;
    font-size: 0.95rem;
    padding: 0.7rem 1rem;
  }

  .form-control:focus {
    background-color: #fff;
    border-color: #ffc107;
    box-shadow: 0 0 0 0.25rem rgba(255, 193, 7, 0.25);
  }

  label.form-label {
    color: #555;
    font-weight: 600;
    margin-bottom: 0.5rem;
  }

  /* ===== Button ===== */
  .btn-warning {
    border: none;
    border-radius: 14px;
    font-weight: 600;
    letter-spacing: 0.3px;
    transition: all 0.3s ease;
    box-shadow: 0 6px 20px rgba(255, 193, 7, 0.25);
  }

  .btn-warning:hover {
    background-color: #e0a800;
    box-shadow: 0 10px 25px rgba(255, 193, 7, 0.35);
    transform: translateY(-2px);
  }

  /* ===== Responsif ===== */
  @media (max-width: 768px) {
    .card {
      padding: 2rem 1.5rem !important;
      border-radius: 20px;
    }
  }
</style>


</body>
</html>
