<?php
include __DIR__ . '../../../app/auth.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_admin();

include __DIR__ . '/_admin_header.php';
include __DIR__ . '/dashboard_controller.php';

?>

<style>
  /* ====== Tampilan modern & hidup (tanpa lib tambahan) ====== */
  .hero {
    background: radial-gradient(1200px 500px at 10% -10%, rgba(255, 193, 7, .18), transparent),
                radial-gradient(1200px 500px at 100% 0%, rgba(255, 193, 7, .10), transparent);
    border: 1px solid rgba(0,0,0,.04);
    border-radius: 28px;
    padding: 28px 28px;
    box-shadow: 0 6px 24px rgba(0,0,0,.06);
  }
  .kpi-card {
    border: 0;
    border-radius: 22px;
    box-shadow: 0 6px 22px rgba(0,0,0,0.08);
    transition: transform .25s ease, box-shadow .25s ease;
    background: linear-gradient(180deg, #ffffff, #fafafa);
  }
  .kpi-card:hover { transform: translateY(-4px); box-shadow: 0 12px 30px rgba(0,0,0,0.25);}
  .kpi-icon {
    width: 46px; height: 46px; display:flex; align-items:center; justify-content:center;
    border-radius: 16px; background: rgba(255,193,7,.12); color: #ff9f1c; font-size: 22px; font-weight: 700;
    margin: 0 auto 8px auto;
  }
  .section-title { font-weight: 700; letter-spacing: .2px; }
  .badge-soft {
    background: rgba(255,193,7,.15); color: #8a6d00; border-radius: 999px; padding: 6px 12px; font-weight: 600;
  }
  .infocard {
    border: 0; border-radius: 18px; background: #ffffff; box-shadow: 0 6px 22px rgba(0,0,0,.07);
  }
  .infocard .list-group-item { border:0; padding:.65rem 1rem; }
  .progress { height: 10px; border-radius: 999px; background: #f1f1f1; }
  .progress-bar { border-radius: 999px; }
  .quick-actions .btn {
    border-radius: 14px; font-weight: 600; padding: .65rem 1rem; box-shadow: 0 6px 16px rgba(0,0,0,.06);
  }
</style>

<main class="justify-content-center align-items-center mb-3" style="min-height: 40vh; margin: 0 110px;">
  <div class="hero mt-4">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
      <div>
        <h4 class="mb-1 fw-bold">Selamat datang kembali, <?= $username ?> 👋</h4>
        <div class="text-muted">Ringkasan singkat sistem Adu-in Aja.</div>
      </div>
      <div class="d-flex align-items-center gap-2">
        <span class="badge-soft">Total Aduan: <strong><?= htmlspecialchars($total_aduan) ?></strong></span>
        <span class="badge-soft">Selesai: <strong><?= htmlspecialchars($total_selesai) ?></strong></span>
        <span class="badge-soft">Pengguna: <strong><?= htmlspecialchars($data_user['total_user'] ?? 0) ?></strong></span>
      </div>
    </div>

    <!-- Quick Actions -->
    <div class="quick-actions mt-3">
      <a href="data_laporan.php" class="btn btn-outline-warning me-2">🔎 Lihat Data Laporan</a>
      <a href="laporan_selesai.php" class="btn btn-warning text-white me-2">✅ Laporan Selesai</a>
      <a href="data_pengguna.php" class="btn btn-outline-dark">👤 Kelola Pengguna</a>

    </div>
  </div>

  <!-- ========== KPI CARDS ========== -->
  <div class="row mt-4 g-4">
    <div class="col-12">
      <div class="card kpi-card text-center py-4">
        <div class="card-body">
          <div class="kpi-icon">👥</div>
          <h6 class="text-warning fw-bold mb-1">Total Pengguna</h6>
          <h2 class="fw-semibold text-dark mb-0"><?= htmlspecialchars($data_user['total_user'] ?? 0) ?></h2>
        </div>
      </div>
    </div>

    <div class="col-12">
      <div class="card kpi-card text-center py-4">
        <div class="card-body">
          <div class="kpi-icon">📝</div>
          <h6 class="text-warning fw-bold mb-1">Total Aduan</h6>
          <h2 class="fw-semibold text-dark mb-0"><?= htmlspecialchars($total_aduan) ?></h2>
        </div>
      </div>
    </div>

    <div class="col-6 col-lg-3">
      <div class="card kpi-card text-center py-4">
        <div class="card-body">
          <div class="kpi-icon">📥</div>
          <h6 class="text-warning fw-bold mb-1">Diterima</h6>
          <h2 class="fw-semibold text-dark mb-0"><?= htmlspecialchars($total_diterima) ?></h2>
        </div>
      </div>
    </div>
    <div class="col-6 col-lg-3">
      <div class="card kpi-card text-center py-4">
        <div class="card-body">
          <div class="kpi-icon">💬</div>
          <h6 class="text-warning fw-bold mb-1">Ditanggapi</h6>
          <h2 class="fw-semibold text-dark mb-0"><?= htmlspecialchars($total_ditanggapi) ?></h2>
        </div>
      </div>
    </div>
    <div class="col-6 col-lg-3">
      <div class="card kpi-card text-center py-4">
        <div class="card-body">
          <div class="kpi-icon">✅</div>
          <h6 class="text-warning fw-bold mb-1">Selesai</h6>
          <h2 class="fw-semibold text-dark mb-0"><?= htmlspecialchars($total_selesai) ?></h2>
        </div>
      </div>
    </div>
    <div class="col-6 col-lg-3">
      <div class="card kpi-card text-center py-4">
        <div class="card-body">
          <div class="kpi-icon">⛔</div>
          <h6 class="text-warning fw-bold mb-1">Ditolak</h6>
          <h2 class="fw-semibold text-dark mb-0"><?= htmlspecialchars($total_ditolak) ?></h2>
        </div>
      </div>
    </div>
  </div>
</main>
