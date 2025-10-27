<?php

require_once __DIR__ . '/../../app/auth.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_login();
require_once __DIR__ . '/config_dashboard.php';

include __DIR__ . '/_user_header.php';
?>

<main class="justify-content-center align-items-center mt-5" style="min-height: 40vh; margin: 0 110px;">
  <div class="card border-0 rounded-5 text-center py-4 mb-4" style="
    width: 100%;
    box-shadow: 0 0 20px rgba(1, 1, 0, 0.25); /* bayangan di semua sisi */
    transition: all 0.3s ease;
  ">
    <div class="card-body">
      <h5 class="card-title text-warning fw-bold mb-3">Total Aduan</h5>
      <h2 class="fw-semibold text-dark mb-0"><?= htmlspecialchars($data_aduan['total_aduan'] ?? 0) ?></h2>
    </div>
  </div>
  <div class="row d-flex">
    <div class="col-3">
        <div class="card border-0 rounded-5 text-center py-4" style="
            width: 100%;
            box-shadow: 0 0 20px rgba(1, 1, 0, 0.25); /* bayangan di semua sisi */
            transition: all 0.3s ease;
        ">
            <div class="card-body">
            <h5 class="card-title text-warning fw-bold mb-3">Diterima</h5>
            <h2 class="fw-semibold text-dark mb-0"><?= htmlspecialchars($data_diterima['total_diterima'] ?? 0) ?></h2>
            </div>
        </div>
    </div>
    <div class="col-3">
        <div class="card border-0 rounded-5 text-center py-4" style="
            width: 100%;
            box-shadow: 0 0 20px rgba(1, 1, 0, 0.25); /* bayangan di semua sisi */
            transition: all 0.3s ease;
        ">
            <div class="card-body">
            <h5 class="card-title text-warning fw-bold mb-3">Ditanggapi</h5>
            <h2 class="fw-semibold text-dark mb-0"><?= htmlspecialchars($data_diproses['total_ditanggapi'] ?? 0) ?></h2>
            </div>
        </div>
    </div>
    <div class="col-3">
        <div class="card border-0 rounded-5 text-center py-4" style="
            width: 100%;
            box-shadow: 0 0 20px rgba(1, 1, 0, 0.25); /* bayangan di semua sisi */
            transition: all 0.3s ease;
        ">
            <div class="card-body">
            <h5 class="card-title text-warning fw-bold mb-3">Selesai</h5>
            <h2 class="fw-semibold text-dark mb-0"><?= htmlspecialchars($data_selesai['total_selesai'] ?? 0) ?></h2>
            </div>
        </div>
    </div>
    <div class="col-3">
        <div class="card border-0 rounded-5 text-center py-4" style="
            width: 100%;
            box-shadow: 0 0 20px rgba(1, 1, 0, 0.25); /* bayangan di semua sisi */
            transition: all 0.3s ease;
        ">
            <div class="card-body">
            <h5 class="card-title text-warning fw-bold mb-3">Ditolak</h5>
            <h2 class="fw-semibold text-dark mb-0"><?= htmlspecialchars($data_ditolak['total_ditolak'] ?? 0) ?></h2>
            </div>
        </div>
    </div>
  </div>
</main>
