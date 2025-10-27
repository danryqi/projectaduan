<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Adu-in Aja</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    /* tambahan ringan untuk efek visual */
    .form-control {
      background-color: #eaeaeaff; /* abu-abu muda */
      border-color: #dee2e6;
      transition: all 0.2s ease-in-out;
    }
    .form-control:hover, 
    .form-control:focus {
      background-color: #fff; /* putih saat hover/fokus */
      border-color: #ffc107; /* kuning Bootstrap (warning) */
      box-shadow: 0 0 0 .15rem rgba(255,193,7,.25);
    }
  </style>
</head>
<body class="bg-light">
    <div class="d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="row gap-4 bg-white shadow p-4 rounded d-flex align-items-center" style="max-width: 700px; width: 100%; height: 350px; max-height: 100vh;">
        <div class="col-md-6">
            <h1 class="text-warning fst-italic mb-4">Adu-in Aja</h1>
            <p class="text-muted" style="text-align: justify;">
            Platform yang memudahkan pengguna untuk menyampaikan keluhan, laporan, atau saran secara online.
            </p>
        </div>
        <div class="col-md-5">
            <h5 class="mb-4 ms-1">Login Page</h5>
            <form action="../app/proses_login.php" method="POST" class="d-grid gap-3">
                <input id="email" name="email" type="email" placeholder="Masukkan email" required class="form-control" />
                <input id="password" name="password" type="password" placeholder="Masukkan password" required class="form-control" />
                <button type="submit" class="btn btn-warning text-white px-4 mt-3">Login</button>
                <a href="register.php" class="text-muted small text-decoration-none mt-0">Belum punya akun? Daftar</a>
            </form>
        </div>
        </div>
    </div>
</body>
</html>
