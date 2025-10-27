<?php
include __DIR__ . '../../../app/auth.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_admin();

include __DIR__ . '/_admin_header.php';
include __DIR__ . '/../../config/config.php';

// Ambil semua laporan dengan status "Selesai" + user pelapor + tanggapan admin
$query = mysqli_query($koneksi, "
  SELECT 
    l.id AS laporan_id,
    l.judul,
    l.deskripsi,
    l.status,
    l.created_at AS laporan_created,
    l.update_at AS laporan_updated,
    u.username AS pelapor,
    t.isi_tanggapan,
    t.created_at AS tanggapan_created
  FROM laporan l
  JOIN user u ON l.user_id = u.user_id
  LEFT JOIN tanggapan t ON t.laporan_id = l.id
  WHERE l.status = 'Selesai'
  ORDER BY l.created_at DESC
");
?>

<main class="justify-content-center align-items-center" style="min-height: 40vh; margin: 0 110px;">
  <div class="card border-0 rounded-5 text-center py-4 mb-4 mt-5" style="
    width: 100%;
    box-shadow: 0 0 20px rgba(1, 1, 0, 0.25);
    transition: all 0.3s ease;
  ">
    <div class="card-body">
      <h4 class="card-title text-warning fw-bold mb-3">Laporan Selesai</h4>
      <p class="text-muted mb-0">Daftar seluruh laporan dengan status <strong>Selesai</strong> beserta tanggapannya.</p>
    </div>
  </div>

  <div class="card border-0 rounded-4 mb-5" style="
      box-shadow: 0 0 20px rgba(1, 1, 0, 0.15);
      transition: all 0.3s ease;
      padding: 20px;
  ">
    <div class="table-responsive">
      <table class="table align-middle text-center">
        <thead class="table-warning">
          <tr>
            <th scope="col">#</th>
            <th scope="col">Pelapor</th>
            <th scope="col">Judul</th>
            <th scope="col">Deskripsi</th>
            <th scope="col">Tanggapan</th>
            <th scope="col">Tanggal Laporan</th>
            <th scope="col">Tanggal Tanggapan</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $no = 1;
          if (mysqli_num_rows($query) > 0) {
            while ($row = mysqli_fetch_assoc($query)) {
              echo "<tr>
                      <td>{$no}</td>
                      <td>" . htmlspecialchars($row['pelapor']) . "</td>
                      <td>" . htmlspecialchars($row['judul']) . "</td>
                      <td class='text-start' style='max-width:300px; white-space:normal;'>" . htmlspecialchars($row['deskripsi']) . "</td>
                      <td class='text-start text-success' style='max-width:300px; white-space:normal;'>" .
                        (!empty($row['isi_tanggapan']) ? htmlspecialchars($row['isi_tanggapan']) : '<em>Belum ada tanggapan</em>') .
                      "</td>
                      <td>" . date('d M Y, H:i', strtotime($row['laporan_created'])) . "</td>
                      <td>" .
                        (!empty($row['tanggapan_created'])
                          ? date('d M Y, H:i', strtotime($row['tanggapan_created']))
                          : '-') .
                      "</td>
                    </tr>";
              $no++;
            }
          } else {
            echo "<tr><td colspan='7' class='text-muted'>Belum ada laporan dengan status <strong>Selesai</strong>.</td></tr>";
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>
</main>
