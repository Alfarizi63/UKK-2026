
<?php

$role = $_SESSION['role'] ?? '';
$halaman = basename($_SERVER['PHP_SELF']);

$nama_pengguna = trim($_SESSION['name'] ?? 'Pengguna');
$email_pengguna = trim($_SESSION['email'] ?? '');

$inisial = function_exists('mb_substr')
    ? mb_strtoupper(mb_substr($nama_pengguna, 0, 1, 'UTF-8'), 'UTF-8')
    : strtoupper(substr($nama_pengguna, 0, 1));

// ==========================================
// KONEKSI DATABASE
// ==========================================

if (!isset($koneksi)) {
    include __DIR__ . '/../config/koneksi.php';
}

// ==========================================
// CEK PENUGASAN WALI KELAS
// ==========================================

$is_wali_kelas = false;
$kelas_wali_id = 0;
$nama_kelas_wali = '';

if (
    $role === 'guru' &&
    $email_pengguna !== '' &&
    isset($koneksi)
) {

    $stmt_wali = mysqli_prepare(
        $koneksi,
        "
        SELECT
            wk.id,
            wk.kelas_id,
            k.nama AS nama_kelas
        FROM t_wali_kelas wk

        INNER JOIN t_guru g
            ON g.id = wk.guru_id

        INNER JOIN t_tahun_ajaran ta
            ON ta.id = wk.tahun_ajaran_id

        INNER JOIN t_kelas k
            ON k.id = wk.kelas_id

        WHERE g.email = ?
          AND wk.status_aktif = 1
          AND ta.status_aktif = 1

        LIMIT 1
        "
    );

    if ($stmt_wali) {

        mysqli_stmt_bind_param(
            $stmt_wali,
            's',
            $email_pengguna
        );

        mysqli_stmt_execute($stmt_wali);

        $hasil_wali = mysqli_stmt_get_result($stmt_wali);

        $data_wali = mysqli_fetch_assoc($hasil_wali);

        if ($data_wali) {

            $is_wali_kelas = true;

            $kelas_wali_id = (int)$data_wali['kelas_id'];

            $nama_kelas_wali = $data_wali['nama_kelas'];
        }

        mysqli_stmt_close($stmt_wali);
    }
}

// ==========================================
// LABEL ROLE
// ==========================================

$role_label = [
    'admin' => 'Administrator',
    'guru' => 'Guru',
    'wali_kelas' => 'Wali Kelas'
];

if ($role === 'guru' && $is_wali_kelas) {

    $label_role = 'Wali Kelas';

} else {

    $label_role = $role_label[$role] ?? 'Pengguna';
}

// ==========================================
// PEMETAAN HALAMAN
// ==========================================

$halaman_alias = [
    'tambah_kategori_pelanggaran.php' => 'kelola_kategori_pelanggaran.php',
    'edit_kategori_pelanggaran.php' => 'kelola_kategori_pelanggaran.php'
];

$halaman_aktif = $halaman_alias[$halaman] ?? $halaman;

// ==========================================
// MENU ADMIN
// ==========================================

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
    ['About Me', 'tentang_saya.php', 'bi-info-circle']

];

// ==========================================
// MENU GURU
// ==========================================

$menu_guru = [

    ['Dashboard', 'dashboard.php', 'bi-speedometer2'],
    ['Catatan', 'catatan_pelanggaran.php', 'bi-clipboard-plus'],
    ['Tindakan', 'tindakan.php', 'bi-clipboard-check'],
    ['Riwayat', 'riwayat.php', 'bi-clock-history'],
    ['Rekap Poin', 'rekap_point.php', 'bi-bar-chart']

];

// ==========================================
// MENU WALI KELAS
// ==========================================

$menu_wali_kelas = [

    ['Dashboard', 'dashboard.php', 'bi-speedometer2'],
    ['Catatan', 'catatan_pelanggaran.php', 'bi-clipboard-plus'],
    ['Tindakan', 'tindakan.php', 'bi-clipboard-check'],
    ['Riwayat', 'riwayat.php', 'bi-clock-history'],
    ['Rekap Poin', 'rekap_point.php', 'bi-bar-chart'],
    ['Laporan', 'laporan.php', 'bi-file-earmark-bar-graph']

];

// ==========================================
// PILIH MENU BERDASARKAN ROLE
// ==========================================

if ($role === 'admin') {

    $menu = $menu_admin;

} elseif ($role === 'guru' && $is_wali_kelas) {

    $menu = $menu_wali_kelas;

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

        <?php if ($is_wali_kelas): ?>

            <div class="mt-2 small text-white-50">

                <i class="bi bi-building me-1"></i>

                <?= htmlspecialchars($nama_kelas_wali, ENT_QUOTES, 'UTF-8') ?>

            </div>

        <?php endif; ?>

    </div>

    <!-- MENU -->

    <div class="sidebar-menu-wrapper">

        <div class="menu-heading">
            NAVIGASI MENU
        </div>

        <ul class="sidebar-menu">

            <?php foreach ($menu as $item): ?>

                <?php

                    $nama_menu = $item[0];
                    $link_menu = $item[1];
                    $icon_menu = $item[2];

                    $aktif = ($halaman_aktif === $link_menu);

                ?>

                <li>

                    <a
                        href="<?= htmlspecialchars($link_menu, ENT_QUOTES, 'UTF-8') ?>"
                        class="<?= $aktif ? 'active' : '' ?>"
                    >

                        <span class="menu-icon">

                            <i class="bi <?= htmlspecialchars($icon_menu, ENT_QUOTES, 'UTF-8') ?>"></i>

                        </span>

                        <span class="menu-text">

                            <?= htmlspecialchars($nama_menu, ENT_QUOTES, 'UTF-8') ?>

                        </span>

                        <?php if ($aktif): ?>

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
