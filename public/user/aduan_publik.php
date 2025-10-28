<?php
require_once __DIR__ . '/../../app/auth.php';
require_once __DIR__ . '/../../app/functions.php';
require_once __DIR__ . '/../../config/config.php';

if (session_status() === PHP_SESSION_NONE) session_start();
require_login();

// Ambil semua laporan + nama pengirim
$query = "
  SELECT laporan.*, user.username 
  FROM laporan 
  JOIN user ON laporan.user_id = user.user_id 
  ORDER BY laporan.created_at DESC
";
$result = mysqli_query($koneksi, $query);

include __DIR__ . '/_user_header.php';
?>

<main class="container py-5">
  <div class="row justify-content-center">
    <div class="col-lg-10">
      <div class="card border-0 rounded-4 shadow-lg p-4">
        <div class="text-center mb-4">
          <h5 class="fw-semibold text-black">Daftar Aduan Publik</h5>
          <p class="text-muted small mb-0">Semua aduan yang telah dikirim oleh pengguna sistem</p>
        </div>

        <div class="table-responsive">
          <table class="table table-hover table-borderless align-middle text-center mb-0 shadow-sm rounded-3 overflow-hidden">
            <thead class="table-warning">
              <tr class="align-middle">
                <th width="5%">No</th>
                <th>Username</th>
                <th>Judul Aduan</th>
                <th>Status</th>
                <th>Tanggal Dibuat</th>
                <th width="10%">Aksi</th>
              </tr>
            </thead>
            <tbody class="bg-light">
              <?php if (mysqli_num_rows($result) > 0): ?>
                <?php $no = 1; ?>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                  <?php
                    // Ambil tanggapan terakhir dari tabel tanggapan
                    $laporan_id = $row['id'];
                    $q_tanggapan = "
                      SELECT t.isi_tanggapan, t.created_at, u.username AS admin_nama
                      FROM tanggapan t
                      JOIN user u ON t.user_id = u.user_id
                      WHERE t.laporan_id = '$laporan_id'
                      ORDER BY t.created_at DESC
                      LIMIT 1
                    ";
                    $res_t = mysqli_query($koneksi, $q_tanggapan);
                    $tanggapan = mysqli_fetch_assoc($res_t);

                    $statusClass = match($row['status']) {
                      'Diterima'   => 'bg-secondary-subtle text-dark border border-secondary',
                      'Diproses'   => 'bg-warning-subtle text-dark border border-warning',
                      'Ditanggapi' => 'bg-info-subtle text-dark border border-info',
                      'Selesai'    => 'bg-success-subtle text-dark border border-success',
                      'Ditolak'    => 'bg-danger-subtle text-dark border border-danger',
                      default      => 'bg-light text-dark border border-secondary'
                    };
                  ?>
                  <tr>
                    <td><?= $no++; ?></td>
                    <td class="fw-semibold text-dark"><?= htmlspecialchars($row['username']); ?></td>
                    <td class="text-start ps-3"><?= htmlspecialchars($row['judul']); ?></td>
                    <td>
                      <span class="badge rounded-pill px-3 py-2 fw-semibold <?= $statusClass ?>">
                        <?= htmlspecialchars($row['status']); ?>
                      </span>
                    </td>
                    <td><?= htmlspecialchars($row['created_at']); ?></td>
                    <td>
                      <button 
                        class="btn btn-sm btn-outline-warning rounded-3 fw-semibold px-3"
                        data-bs-toggle="modal" 
                        data-bs-target="#detailModal"
                        data-user="<?= htmlspecialchars($row['username']); ?>"
                        data-judul="<?= htmlspecialchars($row['judul']); ?>"
                        data-deskripsi="<?= htmlspecialchars($row['deskripsi']); ?>"
                        data-status="<?= htmlspecialchars($row['status']); ?>"
                        data-created="<?= htmlspecialchars($row['created_at']); ?>"
                        data-updated="<?= htmlspecialchars($row['update_at']); ?>"
                        data-tanggapan="<?= htmlspecialchars($tanggapan['isi_tanggapan'] ?? 'Belum ada tanggapan dari admin.'); ?>"
                        data-admin="<?= htmlspecialchars($tanggapan['admin_nama'] ?? '-'); ?>"
                        data-tanggal-tanggapan="<?= htmlspecialchars($tanggapan['created_at'] ?? '-'); ?>">
                        Detail
                      </button>
                    </td>
                  </tr>
                <?php endwhile; ?>
              <?php else: ?>
                <tr>
                  <td colspan="6" class="text-center text-muted py-3">Belum ada aduan publik.</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</main>

<!-- Modal Detail Aduan -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 rounded-4 shadow">
      <div class="modal-header bg-warning-subtle border-0 rounded-top-4">
        <h5 class="modal-title fw-semibold text-dark">Detail Aduan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body px-4 py-3">
        <p><strong>Username:</strong><br><span id="modalUser"></span></p>
        <p><strong>Judul Aduan:</strong><br><span id="modalJudul"></span></p>
        <p><strong>Deskripsi:</strong><br><span id="modalDeskripsi"></span></p>
        <p><strong>Status:</strong><br><span id="modalStatus"></span></p>
        <p><strong>Tanggal Dibuat:</strong><br><span id="modalCreated"></span></p>
        <p><strong>Terakhir Diperbarui:</strong><br><span id="modalUpdated"></span></p>
        <hr>
        <h6 class="fw-semibold text-dark">Tanggapan Admin</h6>
        <p><strong>Admin:</strong> <span id="modalAdmin"></span></p>
        <p><strong>Tanggal Tanggapan:</strong> <span id="modalTanggalTanggapan"></span></p>
        <p><strong>Isi Tanggapan:</strong><br><span id="modalTanggapan" class="text-secondary"></span></p>
      </div>
      <div class="modal-footer border-0">
        <button type="button" class="btn btn-secondary rounded-3 fw-semibold px-4" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  const detailModal = document.getElementById('detailModal')
  detailModal.addEventListener('show.bs.modal', event => {
    const btn = event.relatedTarget
    document.getElementById('modalUser').textContent = btn.getAttribute('data-user')
    document.getElementById('modalJudul').textContent = btn.getAttribute('data-judul')
    document.getElementById('modalDeskripsi').textContent = btn.getAttribute('data-deskripsi')
    document.getElementById('modalStatus').textContent = btn.getAttribute('data-status')
    document.getElementById('modalCreated').textContent = btn.getAttribute('data-created')
    document.getElementById('modalUpdated').textContent = btn.getAttribute('data-updated')
    document.getElementById('modalTanggapan').textContent = btn.getAttribute('data-tanggapan')
    document.getElementById('modalAdmin').textContent = btn.getAttribute('data-admin')
    document.getElementById('modalTanggalTanggapan').textContent = btn.getAttribute('data-tanggal-tanggapan')
  })
</script>

<style>
  body {
    background: linear-gradient(135deg, #fffdf5, #fffaf0);
  }

  main {
    min-height: 80vh;
  }

  /* ====== CARD UTAMA ====== */
  .card {
    background: linear-gradient(180deg, #ffffff, #fafafa);
    border-radius: 26px;
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
    letter-spacing: 0.4px;
  }

  /* ====== TABEL ====== */
  .table {
    border-collapse: separate;
    border-spacing: 0 10px;
  }

  .table thead th {
    background: #fff7df;
    color: #7a5c00;
    font-weight: 700;
    text-transform: uppercase;
    font-size: 0.85rem;
    letter-spacing: 0.4px;
    border: none;
    padding: 12px 8px;
  }

  .table tbody tr {
    transition: all 0.3s ease;
  }

  .table tbody tr:hover td {
    background: #fff9e6;
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.06);
  }

  .table tbody td {
    vertical-align: middle;
    background: #ffffff;
    border: none;
    border-radius: 12px;
    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.04);
    padding: 12px 10px;
  }

  /* ====== BADGE STATUS ====== */
  .badge {
    font-size: 0.8rem;
    padding: 6px 12px;
    border-radius: 999px;
    font-weight: 600;
    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.06);
  }

  /* ====== BUTTON "DETAIL" ====== */
  .btn-outline-warning {
    border: 2px solid #ffc107;
    color: #ffb300;
    font-weight: 600;
    transition: all 0.3s ease;
  }

  .btn-outline-warning:hover {
    background-color: #ffc107;
    color: #fff;
    box-shadow: 0 5px 15px rgba(255, 193, 7, 0.3);
    transform: translateY(-2px);
  }

  /* ====== MODAL ====== */
  .modal-content {
    border-radius: 20px;
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
    border: none;
    transition: all 0.4s ease;
  }

  .modal-header {
    border-bottom: none;
    background: linear-gradient(135deg, #fff7df, #fff2bf);
    border-radius: 20px 20px 0 0;
  }

  .modal-title {
    color: #7a5c00;
    font-weight: 700;
  }

  .modal-body p {
    margin-bottom: 0.6rem;
  }

  .modal-body strong {
    color: #333;
  }

  .btn-secondary {
    border: none;
    border-radius: 10px;
    background: #6c757d;
    font-weight: 600;
    transition: all 0.3s ease;
  }

  .btn-secondary:hover {
    background: #5a6268;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
  }

  * {
    transition: all 0.25s ease-in-out;
  }
</style>

