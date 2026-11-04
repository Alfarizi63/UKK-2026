<?php
$role = $_SESSION['role'] ?? '';
$halaman = basename($_SERVER['PHP_SELF']);

$menu_admin = [
    ['Dashboard', 'dashboard.php'],
    ['Data Siswa', 'kelola_siswa.php'],
    ['Data Guru', 'kelola_guru.php'],
    ['Kelas', 'kelola_kelas.php'],
    ['Kategori Pelanggaran', 'kelola_kategori_pelanggaran.php'],
    ['Tahun Ajaran', 'kelola_tahun_ajaran.php'],
    ['Laporan', 'laporan.php'],
    ['Wali Kelas', 'kelola_wali_kelas.php'],
    ['Penempatan Siswa', 'kelola_penempatan_siswa.php'],
    ['Rekap Poin', 'rekap_point.php'],
    ['Riwayat', 'riwayat.php'],
    ['Jenis Pelanggaran', 'kelola_jenis_pelanggaran.php'],
    ['Export Pelanggaran', 'export_pelanggaran.php'],
    ['Catatan Pelanggaran', 'catatan_pelanggaran.php'],
    ['About Me', 'tentang_saya.php']
];

$menu_guru = [
    ['Dashboard', 'dashboard.php'],
    ['Catatan', 'catatan_pelanggaran.php'],
    ['Tindakan', 'tindakan.php'],
    ['Riwayat', 'riwayat.php'],
    ['Rekap Poin', 'rekap_point.php']
];

if ($role === 'admin') {
    $menu = $menu_admin;
} elseif ($role === 'guru' || $role === 'wali_kelas') {
    $menu = $menu_guru;
} else {
    $menu = [];
}
?>

<link rel="stylesheet" href="includes/sidebar.css">

<aside class="sidebar">
    <h4 class="sidebar-title">My Website</h4>

    <ul class="sidebar-menu">
        <?php foreach ($menu as $item) : ?>
            <li>
                <a href="<?= htmlspecialchars($item[1]) ?>"
                   class="<?= $halaman === $item[1] ? 'active' : '' ?>">
                    <?= htmlspecialchars($item[0]) ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>

    <div class="sidebar-logout">
        <a href="logout.php">Logout</a>
    </div>
</aside>