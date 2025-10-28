<?php
require_once __DIR__ . '/../../app/auth.php';
require_once __DIR__ . '/../../app/functions.php';
require_once __DIR__ . '/../../config/config.php';

if (session_status() === PHP_SESSION_NONE) session_start();
require_login();

$user_id = (int)($_SESSION['user_id'] ?? 0);

// ambil semua laporan milik user + tanggapan (jika ada)
$query = "
  SELECT l.*, t.isi_tanggapan, t.created_at AS tanggapan_at, a.username AS admin_username
  FROM laporan l
  LEFT JOIN tanggapan t ON t.laporan_id = l.id
  LEFT JOIN user a ON t.user_id = a.user_id
  WHERE l.user_id = '$user_id'
  ORDER BY l.created_at DESC
";
$result = mysqli_query($koneksi, $query);

// $can_edit = ($row['status'] === 'Diterima');

include __DIR__ . '/_user_header.php';
?>

<main class="container py-5">
  <div class="row justify-content-center">
    <div class="col-lg-10">
      <div class="card border-0 rounded-4 shadow-lg p-4">
        <div class="text-center mb-4">
          <h5 class="fw-semibold text-black">Daftar Aduan Saya</h5>
        </div>

        <div class="table-responsive">
          <table class="table table-hover table-borderless align-middle text-center mb-0 shadow-sm rounded-3 overflow-hidden">
            <thead class="table-warning">
              <tr class="align-middle">
                <th width="5%">No</th>
                <th>Judul Aduan</th>
                <th>Status</th>
                <th>Tanggal Dibuat</th>
                <th>Terakhir Diperbarui</th>
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

                    $tanggapan = $row['isi_tanggapan'] ? htmlspecialchars($row['isi_tanggapan']) : 'Belum ada tanggapan.';
                    $admin = $row['admin_username'] ? htmlspecialchars($row['admin_username']) : '-';
                    $tgl_tanggapan = $row['tanggapan_at'] ? date('d M Y, H:i', strtotime($row['tanggapan_at'])) : '-';
                  ?>
                  <tr>
                    <td><?= $no++; ?></td>
                    <td class="text-start ps-3"><?= htmlspecialchars($row['judul']); ?></td>
                    <td>
                      <span class="badge rounded-pill px-3 py-2 fw-semibold <?= $statusClass ?>">
                        <?= htmlspecialchars($row['status']); ?>
                      </span>
                    </td>
                    <td><?= htmlspecialchars($row['created_at']); ?></td>
                    <td><?= htmlspecialchars($row['update_at']); ?></td>
                    <td>
                      <button 
                        class="btn btn-sm btn-outline-warning rounded-3 fw-semibold px-3"
                        data-bs-toggle="modal" 
                        data-bs-target="#detailModal"
                        data-judul="<?= htmlspecialchars($row['judul']); ?>"
                        data-deskripsi="<?= htmlspecialchars($row['deskripsi']); ?>"
                        data-status="<?= htmlspecialchars($row['status']); ?>"
                        data-created="<?= htmlspecialchars($row['created_at']); ?>"
                        data-updated="<?= htmlspecialchars($row['update_at']); ?>"
                        data-tanggapan="<?= $tanggapan; ?>"
                        data-admin="<?= $admin; ?>"
                        data-tanggapanat="<?= $tgl_tanggapan; ?>">
                        Detail
                      </button>
                    </td>
                  </tr>
                <?php endwhile; ?>
              <?php else: ?>
                <tr>
                  <td colspan="6" class="text-center text-muted py-3">Belum ada aduan yang dikirim.</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>

        <div class="mt-4 text-center">
          <a href="tambah_aduan.php" class="btn btn-warning text-white fw-semibold py-2 px-4 rounded-3">
            + Buat Aduan Baru
          </a>
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
        <p><strong>Judul Aduan:</strong><br><span id="modalJudul"></span></p>
        <p><strong>Deskripsi:</strong><br><span id="modalDeskripsi"></span></p>
        <p><strong>Status:</strong><br><span id="modalStatus"></span></p>
        <p><strong>Tanggal Dibuat:</strong><br><span id="modalCreated"></span></p>
        <p><strong>Terakhir Diperbarui:</strong><br><span id="modalUpdated"></span></p>
        <hr>
        <p><strong>Tanggapan Admin:</strong><br><span id="modalTanggapan"></span></p>
        <p><strong>Diberikan oleh:</strong> <span id="modalAdmin"></span></p>
        <p><strong>Tanggal Tanggapan:</strong> <span id="modalTanggapanAt"></span></p>
      </div>
      <div class="modal-footer border-0">
        <button type="button" class="btn btn-secondary rounded-3 fw-semibold px-4" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  const detailModal = document.getElementById('detailModal')
  detailModal.addEventListener('show.bs.modal', event => {
    const button = event.relatedTarget
    document.getElementById('modalJudul').textContent = button.getAttribute('data-judul')
    document.getElementById('modalDeskripsi').textContent = button.getAttribute('data-deskripsi')
    document.getElementById('modalStatus').textContent = button.getAttribute('data-status')
    document.getElementById('modalCreated').textContent = button.getAttribute('data-created')
    document.getElementById('modalUpdated').textContent = button.getAttribute('data-updated')
    document.getElementById('modalTanggapan').textContent = button.getAttribute('data-tanggapan')
    document.getElementById('modalAdmin').textContent = button.getAttribute('data-admin')
    document.getElementById('modalTanggapanAt').textContent = button.getAttribute('data-tanggapanat')
  })
</script>

<style>
  body {
    background: linear-gradient(135deg, #fffdf5, #fffaf0);
    min-height: 100vh;
  }

  main {
    min-height: 80vh;
  }

  /* ===== Card utama ===== */
  .card {
    background: linear-gradient(180deg, #ffffff, #fafafa);
    border: none;
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

  /* ===== Table styling ===== */
  .table {
    border-collapse: separate;
    border-spacing: 0 10px;
  }

  thead.table-warning th {
    background-color: #fff7df !important;
    color: #7a5c00 !important;
    font-weight: 700;
    text-transform: uppercase;
    font-size: 0.85rem;
    letter-spacing: 0.4px;
    border: none !important;
    padding: 12px 10px;
  }

  tbody tr {
    transition: all 0.3s ease;
  }

  tbody tr:hover td {
    background: #fff9e6;
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.06);
  }

  tbody td {
    background: #ffffff;
    border: none;
    border-radius: 12px;
    padding: 12px 10px;
    vertical-align: middle;
    box-shadow: 0 1px 6px rgba(0, 0, 0, 0.04);
  }

  /* ===== Badge status ===== */
  .badge {
    font-size: 0.8rem;
    padding: 6px 12px;
    border-radius: 999px;
    font-weight: 600;
    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.06);
  }

  /* ===== Button styling ===== */
  .btn-outline-warning {
    border: 2px solid #ffc107;
    color: #ffb300;
    font-weight: 600;
    transition: all 0.3s ease;
  }

  .btn-outline-warning:hover {
    background-color: #ffc107;
    color: #fff;
    box-shadow: 0 6px 20px rgba(255, 193, 7, 0.3);
    transform: translateY(-2px);
  }

  .btn-warning {
    background-color: #ffc107;
    border: none;
    color: #fff;
    font-weight: 600;
    box-shadow: 0 6px 20px rgba(255, 193, 7, 0.25);
    transition: all 0.3s ease;
  }

  .btn-warning:hover {
    background-color: #e0a800;
    box-shadow: 0 10px 25px rgba(255, 193, 7, 0.35);
    transform: translateY(-2px);
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

  /* ===== Modal styling ===== */
  .modal-content {
    border-radius: 20px;
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
    border: none;
    transition: all 0.4s ease;
  }

  .modal-header {
    background: linear-gradient(135deg, #fff7df, #fff2bf);
    border-radius: 20px 20px 0 0;
    border-bottom: none;
  }

  .modal-title {
    color: #7a5c00;
    font-weight: 700;
  }

  .modal-body p {
    margin-bottom: 0.6rem;
    font-size: 15px;
    color: #444;
  }

  .modal-body strong {
    color: #222;
  }

  * {
    transition: all 0.25s ease-in-out;
  }

</style>


</body>
</html>
