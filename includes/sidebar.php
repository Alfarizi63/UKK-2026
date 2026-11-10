
<?php
$role = $_SESSION['role'] ?? '';
$halaman = basename($_SERVER['PHP_SELF']);

$nama_pengguna = trim($_SESSION['name'] ?? 'Pengguna');

$inisial = function_exists('mb_substr')
    ? mb_strtoupper(mb_substr($nama_pengguna, 0, 1, 'UTF-8'), 'UTF-8')
    : strtoupper(substr($nama_pengguna, 0, 1));

$role_label = [
    'admin' => 'Administrator',
    'guru' => 'Guru',
    'wali_kelas' => 'Wali Kelas'
];

$label_role = $role_label[$role] ?? 'Pengguna';

// Pemetaan halaman agar menu tetap aktif
$halaman_alias = [
    'tambah_kategori_pelanggaran.php' => 'kelola_kategori_pelanggaran.php',
    'edit_kategori_pelanggaran.php' => 'kelola_kategori_pelanggaran.php'
];

$halaman_aktif = $halaman_alias[$halaman] ?? $halaman;

// Menu Admin
$menu_admin = [
    ['Dashboard', 'dashboard.php', 'bi-speedometer2'],
    ['Data Siswa', 'kelola_siswa.php', 'bi-people'],
    ['Data Guru', 'kelola_guru.php', 'bi-person-badge'],
    ['Kelas', 'kelola_kelas.php', 'bi-building'],
    ['Kategori Pelanggaran', 'kelola_kategori_pelanggaran.php', 'bi-grid'],
    ['Tahun Ajaran', 'kelola_tahun_ajaran.php', 'bi-calendar3'],
    ['Laporan', 'laporan.php', 'bi-file-earmark-bar-graph'],
    ['Wali Kelas', 'kelola_wali_kelas.php', 'bi-person-workspace'],
    ['Penempatan Siswa', 'kelola_penempatan_siswa.php', 'bi-person-lines-fill'],
    ['Jenis Pelanggaran', 'kelola_jenis_pelanggaran.php', 'bi-clipboard-check'],
    ['Export', 'export.php', 'bi-download'],
    ['About Me', 'tentang_saya.php', 'bi-info-circle']
];

// Menu Guru
$menu_guru = [
    ['Dashboard', 'dashboard.php', 'bi-speedometer2'],
    ['Catatan', 'catatan_pelanggaran.php', 'bi-clipboard-plus'],
    ['Tindakan', 'tindakan.php', 'bi-clipboard-check'],
    ['Riwayat', 'riwayat.php', 'bi-clock-history'],
    ['Rekap Poin', 'rekap_point.php', 'bi-bar-chart']
];

// Menu Wali Kelas
$menu_wali_kelas = [
    ['Dashboard', 'dashboard.php', 'bi-speedometer2'],
    ['Catatan', 'catatan_pelanggaran.php', 'bi-clipboard-plus'],
    ['Tindakan', 'tindakan.php', 'bi-clipboard-check'],
    ['Riwayat', 'riwayat.php', 'bi-clock-history'],
    ['Rekap Poin', 'rekap_point.php', 'bi-bar-chart'],
    ['Laporan', 'laporan.php', 'bi-file-earmark-bar-graph']
];

// Pilih menu berdasarkan role
if ($role === 'admin') {
    $menu = $menu_admin;
} elseif ($role === 'guru') {
    $menu = $menu_guru;
} elseif ($role === 'wali_kelas') {
    $menu = $menu_wali_kelas;
} else {
    $menu = [];
}
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="includes/sidebar.css">

<aside class="sidebar">

    <!-- BRAND -->
    <div class="sidebar-brand">
        <div class="brand-icon">
            <i class="bi bi-mortarboard-fill"></i>
        </div>

        <div class="brand-text">
            <h4>SIPES</h4>
            <span>School Management System</span>
        </div>
    </div>

    <!-- PROFIL PENGGUNA -->
    <div class="sidebar-profile">
        <div class="profile-top">
            <div class="profile-avatar">
                <?= htmlspecialchars($inisial, ENT_QUOTES, 'UTF-8') ?>
            </div>

            <div class="profile-info">
                <h5>
                    <?= htmlspecialchars($nama_pengguna, ENT_QUOTES, 'UTF-8') ?>
                </h5>

                <span>
                    <?= htmlspecialchars($label_role, ENT_QUOTES, 'UTF-8') ?>
                </span>
            </div>

            <div class="profile-check">
                <i class="bi bi-patch-check-fill"></i>
            </div>
        </div>

        <div class="profile-status">
            <span class="status-label">STATUS AKUN</span>

            <span class="status-online">
                <span class="online-dot"></span>
                Online
            </span>
        </div>
    </div>

    <!-- MENU -->
    <div class="sidebar-menu-wrapper">

        <div class="menu-heading">
            NAVIGASI MENU
        </div>

        <ul class="sidebar-menu">
            <?php foreach ($menu as $item) : ?>
                <?php
                    $nama_menu = $item[0];
                    $link_menu = $item[1];
                    $icon_menu = $item[2];
                    $aktif = ($halaman_aktif === $link_menu);
                ?>

                <li>
                    <a href="<?= htmlspecialchars($link_menu, ENT_QUOTES, 'UTF-8') ?>"
                       class="<?= $aktif ? 'active' : '' ?>">

                        <span class="menu-icon">
                            <i class="bi <?= htmlspecialchars($icon_menu, ENT_QUOTES, 'UTF-8') ?>"></i>
                        </span>

                        <span class="menu-text">
                            <?= htmlspecialchars($nama_menu, ENT_QUOTES, 'UTF-8') ?>
                        </span>

                        <?php if ($aktif) : ?>
                            <span class="active-indicator"></span>
                        <?php endif; ?>

                    </a>
                </li>
            <?php endforeach; ?>
        </ul>

    </div>

    <!-- BAGIAN BAWAH -->
    <div class="sidebar-bottom">

        <div class="sidebar-bottom-card">
            <div class="bottom-icon">
                <i class="bi bi-shield-check"></i>
            </div>

            <div>
                <strong>SIPES System</strong>
                <span>Sistem berjalan dengan baik</span>
            </div>

            <span class="bottom-dot"></span>
        </div>

        <a href="logout.php" class="sidebar-logout">
            <i class="bi bi-box-arrow-left"></i>
            <span>Keluar dari Sistem</span>
        </a>

        <div class="sidebar-footer">
            SIPES &copy; <?= date('Y') ?>
        </div>

    </div>

</aside>
