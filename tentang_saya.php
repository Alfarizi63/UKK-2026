
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

// DATA SESSION
$nama = $_SESSION['name'] ?? 'Nama Pengguna';
$role = strtolower($_SESSION['role'] ?? '');
$email = $_SESSION['email'] ?? '-';

// DATA PROFIL
$kelas = 'XII RPL 1';
$jurusan = 'Rekayasa Perangkat Lunak';
$sekolah = 'SMK Muhammadiyah Kota Tasikmalaya';

$deskripsi = 'Saya adalah siswa yang sedang belajar mengembangkan website menggunakan PHP, MySQL, HTML, CSS, dan JavaScript.';

$foto = 'alfarizi.jpg';

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>About Me</title>

    <!-- BOOTSTRAP -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">

    <!-- BOOTSTRAP ICONS -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #f8f9fa;
            font-family: Arial, sans-serif;
            color: #343a40;
        }

        /* KONTEN */
        .konten {
            margin-left: 250px;
            padding: 25px 28px;
            min-height: 100vh;
            width: calc(100% - 250px);
        }

        .judul {
            font-size: 30px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .subjudul {
            color: #6c757d;
            margin-bottom: 25px;
        }

        /* KARTU */
        .kartu {
            background: white;
            border-radius: 5px;
            padding: 24px;
            margin-bottom: 22px;
            border: 1px solid #dee2e6;
            box-shadow: 0 1px 4px rgba(0,0,0,0.05);
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
            color: #007bff;
            padding: 7px 15px;
            border-radius: 20px;
            font-size: 13px;
            margin-top: 5px;
        }

        .judul-kartu {
            font-size: 19px;
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
            border-radius: 5px;
            height: 100%;
        }

        .item-info i {
            font-size: 25px;
            color: #007bff;
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

        /* RESPONSIVE */
        @media (max-width: 767px) {
            .konten {
                margin-left: 0;
                width: 100%;
                padding: 18px;
            }
        }
    </style>
</head>

<body>

<?php include 'includes/sidebar.php'; ?>

<main class="konten">

            <h2 class="judul">About Me</h2>

            <p class="subjudul">
                Informasi profil dan data diri pengembang.
            </p>

            <!-- PROFIL UTAMA -->
            <div class="kartu text-center">

                <img src="<?= htmlspecialchars($foto) ?>"
                     alt="Foto Profil"
                     class="foto-profil">

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
                            <td><?= htmlspecialchars($nama) ?></td>
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

                <div class="row">

                    <div class="col-md-6 col-lg-3 mb-3">
                        <div class="item-info">
                            <i class="bi bi-filetype-html"></i>
                            <h6>HTML & CSS</h6>
                            <p>Membuat struktur dan tampilan website.</p>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-3 mb-3">
                        <div class="item-info">
                            <i class="bi bi-filetype-php"></i>
                            <h6>PHP</h6>
                            <p>Membuat logika dan proses website.</p>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-3 mb-3">
                        <div class="item-info">
                            <i class="bi bi-database"></i>
                            <h6>MySQL</h6>
                            <p>Mengelola data menggunakan database.</p>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-3 mb-3">
                        <div class="item-info">
                            <i class="bi bi-filetype-js"></i>
                            <h6>JavaScript</h6>
                            <p>Membuat halaman website lebih interaktif.</p>
                        </div>
                    </div>

                </div>
            </div>

            <!-- MINAT DAN TUJUAN -->
            <div class="row">

                <div class="col-md-6 mb-3">
                    <div class="kartu h-100">

                        <h5 class="judul-kartu">
                            <i class="bi bi-heart text-danger"></i>
                            Minat Saya
                        </h5>

                        <p class="text-secondary">
                            Saya tertarik mempelajari pemrograman,
                            desain website, teknologi, dan pengembangan aplikasi.
                        </p>

                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <div class="kartu h-100">

                        <h5 class="judul-kartu">
                            <i class="bi bi-bullseye text-primary"></i>
                            Tujuan Saya
                        </h5>

                        <p class="text-secondary">
                            Terus meningkatkan kemampuan pemrograman
                            dan membuat aplikasi yang bermanfaat
                            serta mudah digunakan.
                        </p>

                    </div>
                </div>

            </div>

            <p class="text-center text-secondary small mt-3">
                © <?= date('Y') ?> Sistem Pelanggaran Siswa
            </p>

        </main>

</body>
</html>
