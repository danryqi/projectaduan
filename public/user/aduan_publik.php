<?php
require_once __DIR__ . '/../../app/auth.php';
require_once __DIR__ . '/../../app/functions.php';
require_once __DIR__ . '/../../config/config.php';

if (session_status() === PHP_SESSION_NONE) session_start();
require_login();

// Ambil semua laporan dengan join user untuk menampilkan username
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
                        data-updated="<?= htmlspecialchars($row['update_at']); ?>">
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
    const button = event.relatedTarget
    document.getElementById('modalUser').textContent = button.getAttribute('data-user')
    document.getElementById('modalJudul').textContent = button.getAttribute('data-judul')
    document.getElementById('modalDeskripsi').textContent = button.getAttribute('data-deskripsi')
    document.getElementById('modalStatus').textContent = button.getAttribute('data-status')
    document.getElementById('modalCreated').textContent = button.getAttribute('data-created')
    document.getElementById('modalUpdated').textContent = button.getAttribute('data-updated')
  })
</script>

<style>
  body { background-color: #f8f9fa; }
  .card { background-color: #ffffff; }
  thead.table-warning th {
    background-color: #ffeaa7 !important;
    color: #000;
    font-weight: 600;
    border-bottom: 2px solid #ffcc00 !important;
  }
  tbody tr:hover { background-color: #fffbea !important; transition: 0.25s ease; }
  .badge { font-size: 0.9rem; }
  .modal-body p { margin-bottom: 0.75rem; font-size: 15px; }
</style>
</body>
</html>
