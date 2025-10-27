<?php
// public/_header.php - Tailwind (updated: show profile dropdown when logged in)
if (session_status() === PHP_SESSION_NONE) session_start();

// safe include helper (pastikan path benar)
require_once __DIR__ . '/../app/functions.php';

$logged = isset($_SESSION['user_id']);

function dashboard_link_for_role() {
    $role = $_SESSION['user_role'] ?? 'user';
    if ($role === 'admin') {
        return 'admin/admin_dashboard.php';
    }
    return 'users/users_dashboard.php'; 
}
?>
<!doctype html>
<html lang="id">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Adu-in Aja</title>

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
      .page-container { max-width: 1100px; margin-left: auto; margin-right: auto; }
      /* small animation for dropdown */
      .dropdown-enter { transform-origin: top right; }
    </style>
  </head>
  <body class="min-h-screen flex flex-col bg-gray-50 text-gray-800">
    <header class="bg-amber-600 text-white shadow">
      <div class="page-container px-4 py-4 flex items-center justify-between">
        <a href="index.php" class="text-xl font-semibold">Adu-in Aja</a>

        <nav class="flex items-center gap-4">
          <a href="index.php"
             class="<?= $current === 'index.php' ? 'font-semibold text-white' : 'hover:opacity-90' ?>">
            Beranda
          </a>

          <a href="articles.php"
             class="<?= $current === 'articles.php' ? 'font-semibold text-white' : 'hover:opacity-90' ?>">
            Artikel
          </a>

          <a href="about.php"
             class="<?= $current === 'about.php' ? 'font-semibold text-white' : 'hover:opacity-90' ?>">
            Tentang
          </a>

          <a href="contact.php"
             class="<?= $current === 'contact.php' ? 'font-semibold text-white' : 'hover:opacity-90' ?>">
            Kontak
          </a>

          <?php if (!$logged): ?>
            <!-- not logged: show register / login -->
            <a href="register.php" class="<?= $current === 'register.php' ? 'font-semibold text-white' : 'hover:opacity-90' ?>">Daftar</a>
            <a href="login.php" class="<?= $current === 'login.php' ? 'font-semibold text-white' : 'hover:opacity-90' ?>">Login</a>
          <?php else: ?>
            <!-- logged: show profile button -->
            <div class="relative" id="profileDropdownRoot">
              <button id="profileBtn" aria-expanded="false" aria-haspopup="true"
                      class="flex items-center gap-3 px-3 py-1 rounded hover:bg-indigo-500/30 focus:outline-none focus:ring-2 focus:ring-white">
                <!-- Avatar (ui-avatars) -->
                <img
                  src="https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['user_name'] ?? 'User') ?>&background=4f46e5&color=fff&rounded=true"
                  alt="avatar"
                  class="w-8 h-8 rounded-full border-2 border-white/30 shadow-sm">
                <span class="text-sm"><?= e($_SESSION['user_name'] ?? 'User') ?></span>
                <!-- caret -->
                <svg id="caret" class="w-4 h-4 text-white/90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
              </button>

              <!-- Dropdown -->
              <div id="profileDropdown" class="hidden dropdown-enter absolute right-0 mt-2 w-40 bg-white text-gray-800 rounded-md shadow-lg ring-1 ring-black/10 z-50 overflow-hidden">
                <a href="<?= dashboard_link_for_role() ?>" class="block px-4 py-2 text-sm hover:bg-gray-100">Dashboard</a>
                <form action="logout.php" method="POST" class="m-0">
                  <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">Logout</button>
                </form>
              </div>
            </div>
          <?php endif; ?>
        </nav>
      </div>
    </header>

    <main class="flex-grow page-container px-4 py-8">

<script>
  // Dropdown logic: toggle + close on outside click + ESC
  (function(){
    const btn = document.getElementById('profileBtn');
    const dd = document.getElementById('profileDropdown');
    if (!btn || !dd) return;

    function openDropdown() {
      dd.classList.remove('hidden');
      btn.setAttribute('aria-expanded', 'true');
    }
    function closeDropdown() {
      dd.classList.add('hidden');
      btn.setAttribute('aria-expanded', 'false');
    }
    btn.addEventListener('click', (e) => {
      e.stopPropagation();
      if (dd.classList.contains('hidden')) openDropdown(); else closeDropdown();
    });

    // click outside
    document.addEventListener('click', (e) => {
      if (!dd.classList.contains('hidden')) {
        if (!dd.contains(e.target) && !btn.contains(e.target)) closeDropdown();
      }
    });

    // esc
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') closeDropdown();
    });
  })();
</script>
