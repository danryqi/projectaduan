<?php
include __DIR__ . '../../../app/auth.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_admin();

include __DIR__ . '/_admin_header.php';
include __DIR__ . '/../../config/config.php';

// === Proses Hapus Pengguna ===
if (isset($_POST['hapus_pengguna'])) {
    $user_id = $_POST['user_id'];

    // Cegah admin menghapus dirinya sendiri
    if ($user_id == $_SESSION['user_id']) {
        echo "<script>alert('Anda tidak bisa menghapus akun Anda sendiri!'); window.location='data_pengguna.php';</script>";
        exit();
    }

    // Hapus semua tanggapan & laporan yang dibuat user ini (opsional tergantung relasi database)
    mysqli_query($koneksi, "DELETE FROM tanggapan WHERE user_id='$user_id'");
    mysqli_query($koneksi, "DELETE FROM laporan WHERE user_id='$user_id'");

    // Hapus user
    mysqli_query($koneksi, "DELETE FROM user WHERE user_id='$user_id'");

    header("Location: data_pengguna.php");
    exit();
}

// Ambil semua data pengguna
$query = mysqli_query($koneksi, "SELECT user_id, username, email, role, created_at FROM user ORDER BY created_at DESC");
?>

<main class="justify-content-center align-items-center" style="min-height: 40vh; margin: 0 110px;">
  <div class="card border-0 rounded-5 text-center py-4 mb-4 mt-5" style="
    width: 100%;
    box-shadow: 0 0 20px rgba(1, 1, 0, 0.25);
    transition: all 0.3s ease;
  ">
    <div class="card-body">
      <h4 class="card-title text-warning fw-bold mb-3">Daftar Pengguna</h4>
      <p class="text-muted mb-0">Berikut adalah seluruh pengguna yang terdaftar di sistem.</p>
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
            <th scope="col">Username</th>
            <th scope="col">Email</th>
            <th scope="col">Role</th>
            <th scope="col">Tanggal Dibuat</th>
            <th scope="col">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $no = 1;
          if (mysqli_num_rows($query) > 0) {
            while ($row = mysqli_fetch_assoc($query)) {
              echo "<tr>
                      <td>{$no}</td>
                      <td>" . htmlspecialchars($row['username']) . "</td>
                      <td>" . htmlspecialchars($row['email']) . "</td>
                      <td><span class='badge bg-" . 
                        ($row['role'] === 'Admin' ? 'danger' : 'secondary') . 
                        "'>" . htmlspecialchars($row['role']) . "</span></td>
                      <td>" . date('d M Y, H:i', strtotime($row['created_at'])) . "</td>
                      <td>";

              // Tombol hapus (tidak muncul untuk akun admin sendiri)
              if ($row['user_id'] != $_SESSION['user_id']) {
                echo "
                  <form method='POST' style='display:inline;' onsubmit=\"return confirm('Yakin ingin menghapus pengguna ini? Semua datanya akan dihapus.')\">
                    <input type='hidden' name='user_id' value='{$row['user_id']}'>
                    <button type='submit' name='hapus_pengguna' class='btn btn-sm btn-danger rounded-pill px-3'>Hapus</button>
                  </form>";
              } else {
                echo "<button class='btn btn-sm btn-secondary rounded-pill px-3' disabled>Admin Aktif</button>";
              }

              echo "</td></tr>";
              $no++;  
            }
          } else {
            echo "<tr><td colspan='6' class='text-muted'>Belum ada pengguna terdaftar.</td></tr>";
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>
</main>
