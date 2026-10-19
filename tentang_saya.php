```php
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

$nama = $_SESSION['name'] ?? 'Nama Pengguna';
$role = $_SESSION['role'] ?? 'Pengguna';

// Ganti dengan data profil kamu
$kelas = 'XII RPL 1';
$jurusan = 'Rekayasa Perangkat Lunak';
$sekolah = 'SMK Muhammadiyah Tasikmalaya';
$email = $_SESSION['email'] ?? '-';
$deskripsi = 'Saya adalah siswa yang sedang belajar mengembangkan website menggunakan PHP, MySQL, HTML, CSS, dan JavaScript.';

$foto = 'assets/img/profil.jpg';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Me</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #f4f6f9;
            font-family: Arial, sans-serif;
            color: #343a40;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: 273px;
            padding: 20px 14px;
            background: #343a40;
            color: white;
            overflow-y: auto;
            z-index: 1000;
        }

        .sidebar h4 {
            font-size: 21px;
            font-weight: bold;
            margin-bottom: 28px;
        }

        .sidebar a {
            display: block;
            padding: 11px 13px;
            margin-bottom: 5px;
            border-radius: 7px;
            text-decoration: none;
            color: #d7dce1;
            font-size: 14px;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background: #0d6efd;
            color: white;
        }

        .sidebar a i {
            margin-right: 9px;
        }

        .konten {
            margin-left: 273px;
            padding: 28px;
            width: calc(100% - 273px);
        }

        .judul {
            font-size: 27px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .subjudul {
            color: #7b8490;
            margin-bottom: 25px;
        }

        .kartu {
            background: white;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 22px;
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.05);
        }

        .foto-profil {
            width: 145px;
            height: 145px;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid #edf2f7;
            background: #e9ecef;
        }

        .nama-profil {
            font-size: 25px;
            font-weight: bold;
            margin-top: 15px;
        }

        .badge-role {
            display: inline-block;
            background: #e7f1ff;
            color: #0d6efd;
            padding: 7px 15px;
            border-radius: 20px;
            font-size: 13px;
            margin-top: 5px;
        }

        .judul-kartu {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 18px;
        }

        .tabel-biodata td {
            padding: 12px 8px;
            border-bottom: 1px solid #edf0f3;
            vertical-align: top;
        }

        .tabel-biodata td:first-child {
            width: 145px;
            color: #6c757d;
        }

        .tabel-biodata tr:last-child td {
            border-bottom: none;
        }

        .item-info {
            background: #f8f9fa;
            border: 1px solid #edf0f3;
            padding: 15px;
            border-radius: 9px;
            height: 100%;
        }

        .item-info i {
            font-size: 25px;
            color: #0d6efd;
            margin-bottom: 10px;
            display: block;
        }

        .item-info h6 {
            font-weight: bold;
        }

        .item-info p {
            font-size: 14px;
            color: #6c757d;
            margin-bottom: 0;
        }

        @media (max-width: 768px) {
            .sidebar {
                position: relative;
                width: 100%;
                height: auto;
            }

            .konten {
                margin-left: 0;
                width: 100%;
                padding: 16px;
            }
        }
    </style>
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <h4><i class="bi bi-shield-check"></i> Sistem Pelanggaran</h4>

    <a href="dashboard.php">
        <i class="bi bi-speedometer2"></i> Dashboard
    </a>

    <?php if (strtolower($role) === 'admin'): ?>

        <a href="data_siswa.php">
            <i class="bi bi-people"></i> Data Siswa
        </a>

        <a href="data_guru.php">
            <i class="bi bi-person-badge"></i> Data Guru
        </a>

        <a href="kelola_kelas.php">
            <i class="bi bi-building"></i> Kelas
        </a>

        <a href="kelola_kategori_pelanggaran.php">
            <i class="bi bi-list-check"></i> Kelola Kategori Pelanggaran
        </a>

        <a href="kelola_tahun_ajaran.php">
            <i class="bi bi-calendar"></i> Kelola Tahun Ajaran
        </a>

        <a href="laporan.php">
            <i class="bi bi-file-earmark-text"></i> Laporan
        </a>

        <a href="kelola_wali_kelas.php">
            <i class="bi bi-person-workspace"></i> Kelola Wali Kelas
        </a>

        <a href="kelola_penempatan_siswa.php">
            <i class="bi bi-person-lines-fill"></i> Kelola Penempatan Siswa
        </a>

        <a href="rekap_point.php">
            <i class="bi bi-bar-chart"></i> Rekap Poin
        </a>

    <?php else: ?>

        <a href="catatan.php">
            <i class="bi bi-journal-text"></i> Catatan
        </a>

        <a href="tindakan.php">
            <i class="bi bi-clipboard-check"></i> Tindakan
        </a>

        <a href="laporan.php">
            <i class="bi bi-file-earmark-text"></i> Laporan
        </a>

        <a href="riwayat.php">
            <i class="bi bi-clock-history"></i> Riwayat
        </a>

        <a href="rekap_point.php">
            <i class="bi bi-bar-chart"></i> Rekap Point
        </a>

    <?php endif; ?>

    <a href="tentang_saya.php" class="active">
        <i class="bi bi-person-circle"></i> About Me
    </a>

    <a href="logout.php">
        <i class="bi bi-box-arrow-right"></i> Logout
    </a>
</div>

<!-- KONTEN -->
<div class="konten">

    <h2 class="judul">About Me</h2>
    <p class="subjudul">Informasi profil dan data diri pengembang.</p>

    <!-- PROFIL UTAMA -->
    <div class="kartu text-center">
        <img src="alfarizi.jpg" alt="">
        <div class="nama-profil">
            <?= htmlspecialchars($nama) ?>
        </div>

        <span class="badge-role">
            <i class="bi bi-code-slash"></i>
            Pengembang Sistem
        </span>

        <p class="text-secondary mt-3 mb-0">
            <?= htmlspecialchars($deskripsi) ?>
        </p>
    </div>

    <!-- BIODATA -->
    <div class="kartu">
        <h5 class="judul-kartu">
            <i class="bi bi-person-vcard text-primary"></i>
            Biodata Saya
        </h5>

        <div class="table-responsive">
            <table class="table tabel-biodata mb-0">
                <tr>
                    <td>Nama</td>
                    <td>alfarizi</td>
                </tr>

                <tr>
                    <td>Kelas</td>
                    <td><?= htmlspecialchars($kelas) ?></td>
                </tr>

                <tr>
                    <td>Jurusan</td>
                    <td><?= htmlspecialchars($jurusan) ?></td>
                </tr>

                <tr>
                    <td>Sekolah</td>
                    <td><?= htmlspecialchars($sekolah) ?></td>
                </tr>

                <tr>
                    <td>Email</td>
                    <td><?= htmlspecialchars($email) ?></td>
                </tr>

                <tr>
                    <td>Deskripsi</td>
                    <td><?= htmlspecialchars($deskripsi) ?></td>
                </tr>
            </table>
        </div>
    </div>

    <!-- KEAHLIAN -->
    <div class="kartu">
        <h5 class="judul-kartu">
            <i class="bi bi-laptop text-primary"></i>
            Keahlian
        </h5>

        <div class="row g-3">
            <div class="col-md-6 col-lg-3">
                <div class="item-info">
                    <i class="bi bi-filetype-html"></i>
                    <h6>HTML & CSS</h6>
                    <p>Membuat struktur dan tampilan website.</p>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="item-info">
                    <i class="bi bi-filetype-php"></i>
                    <h6>PHP</h6>
                    <p>Membuat logika dan proses website.</p>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="item-info">
                    <i class="bi bi-database"></i>
                    <h6>MySQL</h6>
                    <p>Mengelola data menggunakan database.</p>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="item-info">
                    <i class="bi bi-filetype-js"></i>
                    <h6>JavaScript</h6>
                    <p>Membuat halaman website lebih interaktif.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- MINAT DAN TUJUAN -->
    <div class="row g-3">
        <div class="col-md-6">
            <div class="kartu h-100">
                <h5 class="judul-kartu">
                    <i class="bi bi-heart text-danger"></i>
                    Minat Saya
                </h5>

                <p class="text-secondary">
                    Saya tertarik mempelajari pemrograman, desain website,
                    teknologi, dan pengembangan aplikasi.
                </p>
            </div>
        </div>

        <div class="col-md-6">
            <div class="kartu h-100">
                <h5 class="judul-kartu">
                    <i class="bi bi-bullseye text-primary"></i>
                    Tujuan Saya
                </h5>

                <p class="text-secondary">
                    Terus meningkatkan kemampuan pemrograman dan membuat
                    aplikasi yang bermanfaat serta mudah digunakan.
                </p>
            </div>
        </div>
    </div>

    <p class="text-center text-secondary small mt-3">
        © <?= date('Y') ?> Sistem Pelanggaran Siswa
    </p>

</div>

</body>
</html>
```