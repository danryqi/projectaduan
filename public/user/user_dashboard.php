<?php

require_once __DIR__ . '/../../app/auth.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_login();
require_once __DIR__ . '/dashboard_controller.php';

include __DIR__ . '/_user_header.php';
?>

<main class="container my-5">

  <!-- GREETING -->
  <div class="card border-0 shadow-sm mb-4 rounded-4 p-4 bg-light">
    <div class="d-flex flex-wrap justify-content-between align-items-center">
      <div>
        <h4 class="fw-bold text-dark mb-1">
          Selamat datang kembali, <?= htmlspecialchars($username); ?> 👋
        </h4>
        <p class="text-muted mb-0">Berikut ringkasan aduanmu di sistem hari ini.</p>
      </div>
      <div class="text-end">
        <span class="badge bg-warning text-dark fs-6">
          Total Aduan: <?= htmlspecialchars($data_aduan['total_aduan'] ?? 0) ?>
        </span>
      </div>
    </div>
  </div>

  <!-- TOTAL ADUAN (FULL WIDTH) -->
  <div class="row mb-4">
    <div class="col-12">
      <div class="card text-center border-0 shadow-sm rounded-4 p-4 h-100 bg-white">
        <div class="card-body">
          <div class="text-warning mb-2 fs-2"><i class="bi bi-clipboard-data"></i></div>
          <h5 class="text-warning fw-bold mb-2">Total Aduan</h5>
          <h1 class="fw-bold text-dark mb-0" style="font-size: 2.5rem;">
            <?= htmlspecialchars($data_aduan['total_aduan'] ?? 0) ?>
          </h1>
        </div>
      </div>
    </div>
  </div>

  <!-- 4 STATUS (SEBARIS) -->
  <div class="row g-4">
    <!-- Diterima -->
    <div class="col-12 col-md-6 col-lg-3">
      <div class="card text-center border-0 shadow-sm rounded-4 p-4 h-100 hover-card">
        <div class="card-body">
          <div class="text-success mb-2 fs-2"><i class="bi bi-inbox"></i></div>
          <h6 class="text-warning fw-bold">Diterima</h6>
          <h2 class="fw-semibold text-dark mb-0"><?= htmlspecialchars($data_diterima['total_diterima'] ?? 0) ?></h2>
        </div>
      </div>
    </div>

    <!-- Ditanggapi -->
    <div class="col-12 col-md-6 col-lg-3">
      <div class="card text-center border-0 shadow-sm rounded-4 p-4 h-100 hover-card">
        <div class="card-body">
          <div class="text-info mb-2 fs-2"><i class="bi bi-chat-dots"></i></div>
          <h6 class="text-warning fw-bold">Ditanggapi</h6>
          <h2 class="fw-semibold text-dark mb-0"><?= htmlspecialchars($data_diproses['total_ditanggapi'] ?? 0) ?></h2>
        </div>
      </div>
    </div>

    <!-- Selesai -->
    <div class="col-12 col-md-6 col-lg-3">
      <div class="card text-center border-0 shadow-sm rounded-4 p-4 h-100 hover-card">
        <div class="card-body">
          <div class="text-primary mb-2 fs-2"><i class="bi bi-check-circle"></i></div>
          <h6 class="text-warning fw-bold">Selesai</h6>
          <h2 class="fw-semibold text-dark mb-0"><?= htmlspecialchars($data_selesai['total_selesai'] ?? 0) ?></h2>
        </div>
      </div>
    </div>

    <!-- Ditolak -->
    <div class="col-12 col-md-6 col-lg-3">
      <div class="card text-center border-0 shadow-sm rounded-4 p-4 h-100 hover-card">
        <div class="card-body">
          <div class="text-danger mb-2 fs-2"><i class="bi bi-x-circle"></i></div>
          <h6 class="text-warning fw-bold">Ditolak</h6>
          <h2 class="fw-semibold text-dark mb-0"><?= htmlspecialchars($data_ditolak['total_ditolak'] ?? 0) ?></h2>
        </div>
      </div>
    </div>
  </div>

</main>

<style>
  /* ===== Hero Greeting (tanpa hover) ===== */
  .card.bg-light {
    background: radial-gradient(900px 400px at 10% -10%, rgba(255,193,7,.18), transparent),
                radial-gradient(900px 400px at 100% 0%, rgba(255,193,7,.10), transparent);
    border: 1px solid rgba(0,0,0,.04);
    border-radius: 28px;
    padding: 20px;
    box-shadow: 0 6px 22px rgba(0,0,0,.08);
  }

  /* ===== Kartu Statistik (KPI) ===== */
  .hover-card {
    border: none;
    border-radius: 22px;
    background: linear-gradient(180deg, #ffffff, #fafafa);
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    transition: transform 0.4s cubic-bezier(0.22, 1, 0.36, 1),
                box-shadow 0.4s cubic-bezier(0.22, 1, 0.36, 1);
  }

  .hover-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.18); /* ✅ bayangan lebih nyata */
  }

  /* ===== Isi kartu ===== */
  .card-body h1, .card-body h2 {
    font-weight: 700;
    font-size: 2rem;
    color: #212529;
  }

  .text-warning {
    color: #ffb300 !important;
  }

  .badge {
    padding: 10px 14px;
    font-weight: 600;
    border-radius: 999px;
    background: rgba(255,193,7,.15);
    color: #8a6d00;
  }

  /* ===== Efek transisi halus global ===== */
  * {
    transition: all 0.25s ease-in-out;
  }

  /* ===== Responsif ===== */
  @media (max-width: 992px) {
    .card.bg-light {
      padding: 16px;
      border-radius: 20px;
    }
    .card-body h1, .card-body h2 {
      font-size: 1.6rem;
    }
  }
</style>





