<?php
include __DIR__ . '/../../config/config.php';
include __DIR__ . '../../../app/auth.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_admin();

include __DIR__ . '/_admin_header.php';

// Ambil semua laporan
$query = "
    SELECT l.id AS laporan_id, l.judul, l.deskripsi, l.status, l.created_at, u.username, t.isi_tanggapan
    FROM laporan l
    JOIN user u ON l.user_id = u.user_id
    LEFT JOIN tanggapan t ON t.laporan_id = l.id
    ORDER BY l.created_at DESC
";
$result = mysqli_query($koneksi, $query);

// Tambah tanggapan
if (isset($_POST['submit_tanggapan'])) {
    $laporan_id = $_POST['laporan_id'];
    $isi_tanggapan = $_POST['isi_tanggapan'];
    $user_id = $_SESSION['user_id'];

    $insert = "INSERT INTO tanggapan (laporan_id, user_id, isi_tanggapan) VALUES ('$laporan_id', '$user_id', '$isi_tanggapan')";
    mysqli_query($koneksi, $insert);

    mysqli_query($koneksi, "UPDATE laporan SET status='Ditanggapi', update_at=NOW() WHERE id='$laporan_id'");
    header("Location: data_laporan.php");
    exit();
}

// Ubah status
if (isset($_POST['ubah_status'])) {
    $laporan_id = $_POST['laporan_id'];
    $status_baru = $_POST['status'];
    mysqli_query($koneksi, "UPDATE laporan SET status='$status_baru', update_at=NOW() WHERE id='$laporan_id'");
    header("Location: data_laporan.php");
    exit();
}

// Hapus laporan
if (isset($_POST['hapus_laporan'])) {
    $laporan_id = $_POST['laporan_id'];

    // Hapus tanggapan yang terkait dulu (agar tidak error foreign key)
    mysqli_query($koneksi, "DELETE FROM tanggapan WHERE laporan_id='$laporan_id'");

    // Hapus laporan
    mysqli_query($koneksi, "DELETE FROM laporan WHERE id='$laporan_id'");

    header("Location: data_laporan.php");
    exit();
}
?>

<main class="justify-content-center align-items-center" style="min-height: 60vh; margin: 0 110px;">

  <div class="card border-0 rounded-5 text-center py-4 mb-4 mt-5" style="
      width: 100%;
      box-shadow: 0 0 20px rgba(1, 1, 0, 0.25);
      transition: all 0.3s ease;">
    <div class="card-body">
      <h4 class="fw-bold text-warning mb-5">Daftar Laporan Pengguna</h4>

      <div class="table-responsive px-3">
        <table class="table align-middle">
          <thead class="table-warning">
            <tr>
              <th>No</th>
              <th>Pelapor</th>
              <th>Judul</th>
              <th>Deskripsi</th>
              <th>Status</th>
              <th>Tanggapan</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php 
            $no = 1;
            while ($row = mysqli_fetch_assoc($result)) { ?>
              <tr style="vertical-align: middle;">
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($row['username']) ?></td>
                <td><?= htmlspecialchars($row['judul']) ?></td>
                <td><?= htmlspecialchars($row['deskripsi']) ?></td>
                <td>
                  <span class="badge 
                    <?= ($row['status'] == 'Diterima') ? 'bg-success' : 
                        (($row['status'] == 'Diproses') ? 'bg-primary' : 
                        (($row['status'] == 'Selesai') ? 'bg-info text-dark' : 
                        (($row['status'] == 'Ditolak') ? 'bg-danger' : 'bg-secondary'))) ?>">
                    <?= htmlspecialchars($row['status']) ?>
                  </span>
                </td>
                <td><?= $row['isi_tanggapan'] ? htmlspecialchars($row['isi_tanggapan']) : '<i>Belum ada tanggapan</i>' ?></td>
                <td>
                  <button class="btn btn-sm btn-success rounded-pill px-3" onclick="openModal(<?= $row['laporan_id'] ?>)">Tanggapi</button>
                  <button class="btn btn-sm btn-warning rounded-pill px-3" onclick="openStatusModal(<?= $row['laporan_id'] ?>)">Ubah Status</button>
                  <form method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus laporan ini?')">
                    <input type="hidden" name="laporan_id" value="<?= $row['laporan_id'] ?>">
                    <button type="submit" name="hapus_laporan" class="btn btn-sm btn-danger rounded-pill px-3">Hapus</button>
                  </form>
                </td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</main>

<!-- Modal Tanggapan -->
<div id="modalTanggapan" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeModal('modalTanggapan')">&times;</span>
    <h5 class="fw-bold mb-3">Tambah Tanggapan</h5>
    <form method="POST">
      <input type="hidden" name="laporan_id" id="laporan_id_tanggapan">
      <textarea name="isi_tanggapan" class="form-control mb-3" placeholder="Tuliskan tanggapan..." required></textarea>
      <button type="submit" name="submit_tanggapan" class="btn btn-success rounded-pill px-4">Kirim</button>
    </form>
  </div>
</div>

<!-- Modal Ubah Status -->
<div id="modalStatus" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeModal('modalStatus')">&times;</span>
    <h5 class="fw-bold mb-3">Ubah Status Laporan</h5>
    <form method="POST">
      <input type="hidden" name="laporan_id" id="laporan_id_status">
      <select name="status" class="form-select mb-3" required>
        <option value="Diterima">Diterima</option>
        <option value="Diproses">Diproses</option>
        <option value="Selesai">Selesai</option>
        <option value="Ditolak">Ditolak</option>
      </select>
      <button type="submit" name="ubah_status" class="btn btn-warning rounded-pill px-4">Simpan</button>
    </form>
  </div>
</div>

<style>
  .table th, .table td {
    vertical-align: middle;
  }
  .badge {
    font-size: 0.85rem;
    padding: 6px 12px;
  }
  /* Modal Styling */
  .modal {
    display: none;
    position: fixed;
    z-index: 10;
    left: 0; top: 0;
    width: 100%; height: 100%;
    background: rgba(0,0,0,0.5);
  }
  .modal-content {
    background: white;
    width: 40%;
    margin: 8% auto;
    padding: 25px;
    border-radius: 15px;
    box-shadow: 0 0 15px rgba(0,0,0,0.3);
    animation: fadeIn 0.3s ease-in-out;
  }
  .close {
    float: right;
    font-size: 22px;
    color: #888;
    cursor: pointer;
  }
  .close:hover { color: black; }
  @keyframes fadeIn {
    from {opacity: 0; transform: translateY(-20px);}
    to {opacity: 1; transform: translateY(0);}
  }
</style>

<script>
  function openModal(id) {
    document.getElementById("laporan_id_tanggapan").value = id;
    document.getElementById("modalTanggapan").style.display = "block";
  }
  function openStatusModal(id) {
    document.getElementById("laporan_id_status").value = id;
    document.getElementById("modalStatus").style.display = "block";
  }
  function closeModal(modalId) {
    document.getElementById(modalId).style.display = "none";
  }
</script>
