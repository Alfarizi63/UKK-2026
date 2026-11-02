
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

$error = '';

$siswa_id = 0;
$kelas_id = 0;
$tahun_ajaran_id = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $siswa_id = (int) ($_POST['siswa_id'] ?? 0);
    $kelas_id = (int) ($_POST['kelas_id'] ?? 0);
    $tahun_ajaran_id = (int) ($_POST['tahun_ajaran_id'] ?? 0);

    if ($siswa_id <= 0 || $kelas_id <= 0 || $tahun_ajaran_id <= 0) {

        $error = 'Silakan lengkapi semua pilihan.';

    } else {

        // Cek apakah siswa sudah ditempatkan
        $cek = mysqli_prepare(
            $koneksi,
            "SELECT id FROM t_kelas_siswa
             WHERE siswa_id = ? AND tahun_ajaran_id = ?"
        );

        mysqli_stmt_bind_param(
            $cek,
            "ii",
            $siswa_id,
            $tahun_ajaran_id
        );

        mysqli_stmt_execute($cek);
        $hasil = mysqli_stmt_get_result($cek);

        if (mysqli_num_rows($hasil) > 0) {

            $error = 'Siswa sudah ditempatkan pada tahun ajaran tersebut.';

        } else {

            $stmt = mysqli_prepare(
                $koneksi,
                "INSERT INTO t_kelas_siswa
                (siswa_id, kelas_id, tahun_ajaran_id)
                VALUES (?, ?, ?)"
            );

            mysqli_stmt_bind_param(
                $stmt,
                "iii",
                $siswa_id,
                $kelas_id,
                $tahun_ajaran_id
            );

            if (mysqli_stmt_execute($stmt)) {

                mysqli_stmt_close($stmt);
                mysqli_stmt_close($cek);

                header('Location: kelola_penempatan_siswa.php');
                exit;

            } else {

                $error = 'Gagal menyimpan penempatan siswa: '
                    . mysqli_error($koneksi);

                mysqli_stmt_close($stmt);
            }
        }

        mysqli_stmt_close($cek);
    }
}

// Ambil data siswa
$query_siswa = mysqli_query(
    $koneksi,
    "SELECT id, nis, nama
     FROM t_siswa
     ORDER BY nama ASC"
);

// Ambil data kelas
$query_kelas = mysqli_query(
    $koneksi,
    "SELECT id, nama, tingkat, jurusan
     FROM t_kelas
     ORDER BY tingkat, jurusan, nama"
);

// Ambil data tahun ajaran
$query_tahun = mysqli_query(
    $koneksi,
    "SELECT id, nama
     FROM t_tahun_ajaran
     ORDER BY id DESC"
);

if (!$query_siswa || !$query_kelas || !$query_tahun) {
    die('Gagal mengambil pilihan data. Periksa struktur tabel database.');
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tambah Penempatan Siswa</title>

    <!-- BOOTSTRAP -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">

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

        /* SIDEBAR */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: 273px;
            padding: 15px;
            background: #212529;
            color: white;
            overflow-y: auto;
            z-index: 1030;
        }

        .sidebar h4 {
            font-size: 21px;
            font-weight: bold;
            margin-bottom: 25px;
        }

        .sidebar .nav-link {
            display: block;
            padding: 10px 14px;
            margin-bottom: 5px;
            border-radius: 5px;
            color: white;
            font-size: 14px;
            text-decoration: none;
        }

        .sidebar .nav-link:hover {
            background: #343a40;
            color: white;
        }

        .sidebar .nav-link.active {
            background: #007bff;
            color: white;
        }

        /* KONTEN */
        .konten {
            margin-left: 273px;
            padding: 28px;
            width: calc(100% - 273px);
            min-height: 100vh;
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

        /* CARD FORM */
        .card-form {
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            overflow: hidden;
        }

        .card-header-custom {
            background: #007bff;
            color: white;
            padding: 15px 20px;
            font-size: 16px;
            font-weight: bold;
        }

        .card-body {
            padding: 25px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            font-weight: 600;
            font-size: 14px;
        }

        .form-control {
            height: 42px;
            font-size: 14px;
            border: 1px solid #ced4da;
        }

        .form-control:focus {
            border-color: #80bdff;
            box-shadow: 0 0 0 3px rgba(0,123,255,0.15);
        }

        .btn {
            padding: 9px 18px;
            font-size: 14px;
        }

        .footer {
            margin-top: 30px;
            color: #6c757d;
            text-align: center;
            font-size: 13px;
        }

        /* RESPONSIVE */
        @media (max-width: 767px) {
            .sidebar {
                position: relative;
                width: 100%;
                height: auto;
            }

            .konten {
                margin-left: 0;
                width: 100%;
                padding: 18px;
            }
        }
    </style>
</head>

<body>

<div class="container-fluid">
    <div class="row">

        <!-- SIDEBAR -->
        <aside class="sidebar">

            <h4>My Website</h4>

            <ul class="nav nav-pills flex-column">

                <li class="nav-item mb-2">
                    <a href="dashboard.php" class="nav-link">
                        Dashboard
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="kelola_siswa.php" class="nav-link">
                        Data Siswa
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="kelola_guru.php" class="nav-link">
                        Data Guru
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="kelola_kelas.php" class="nav-link">
                        Kelas
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="kelola_kategori_pelanggaran.php" class="nav-link">
                        Kelola Kategori Pelanggaran
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="kelola_tahun_ajaran.php" class="nav-link">
                        Kelola Tahun Ajaran
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="laporan.php" class="nav-link">
                        Laporan
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="kelola_wali_kelas.php" class="nav-link">
                        Kelola Wali Kelas
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="kelola_penempatan_siswa.php" class="nav-link active">
                        Kelola Penempatan Siswa
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="rekap_point.php" class="nav-link">
                        Rekap Poin
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="riwayat.php" class="nav-link">
                        Riwayat
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="kelola_jenis_pelanggaran.php" class="nav-link">
                        Kelola Jenis Pelanggaran
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="export_pelanggaran.php" class="nav-link">
                        Export Pelanggaran
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="tentang_saya.php" class="nav-link">
                        About Me
                    </a>
                </li>

                <hr class="bg-secondary">

                <li class="nav-item">
                    <a href="logout.php" class="nav-link text-danger">
                        Logout
                    </a>
                </li>

            </ul>
        </aside>

        <!-- KONTEN UTAMA -->
        <main class="konten">

            <h2 class="judul">Tambah Penempatan Siswa</h2>

            <p class="subjudul">
                Tambahkan siswa ke kelas berdasarkan tahun ajaran.
            </p>

            <a href="kelola_penempatan_siswa.php"
               class="btn btn-secondary mb-4">
                Kembali
            </a>

            <!-- FORM -->
            <div class="card-form">

                <div class="card-header-custom">
                    Form Tambah Penempatan Siswa
                </div>

                <div class="card-body">

                    <?php if ($error !== '') { ?>
                        <div class="alert alert-danger">
                            <?= htmlspecialchars($error) ?>
                        </div>
                    <?php } ?>

                    <form method="POST">

                        <div class="form-group">
                            <label>Siswa</label>

                            <select name="siswa_id"
                                    class="form-control"
                                    required>

                                <option value="">
                                    -- Pilih Siswa --
                                </option>

                                <?php while ($siswa = mysqli_fetch_assoc($query_siswa)) { ?>

                                    <option value="<?= (int) $siswa['id'] ?>"
                                        <?= $siswa_id == $siswa['id'] ? 'selected' : '' ?>>

                                        <?= htmlspecialchars(
                                            $siswa['nis'] . ' - ' . $siswa['nama']
                                        ) ?>

                                    </option>

                                <?php } ?>

                            </select>
                        </div>

                        <div class="form-group">
                            <label>Kelas</label>

                            <select name="kelas_id"
                                    class="form-control"
                                    required>

                                <option value="">
                                    -- Pilih Kelas --
                                </option>

                                <?php while ($kelas = mysqli_fetch_assoc($query_kelas)) { ?>

                                    <option value="<?= (int) $kelas['id'] ?>"
                                        <?= $kelas_id == $kelas['id'] ? 'selected' : '' ?>>

                                        <?= htmlspecialchars(
                                            $kelas['nama'] . ' - ' .
                                            $kelas['tingkat'] . ' - ' .
                                            $kelas['jurusan']
                                        ) ?>

                                    </option>

                                <?php } ?>

                            </select>
                        </div>

                        <div class="form-group">
                            <label>Tahun Ajaran</label>

                            <select name="tahun_ajaran_id"
                                    class="form-control"
                                    required>

                                <option value="">
                                    -- Pilih Tahun Ajaran --
                                </option>

                                <?php while ($ta = mysqli_fetch_assoc($query_tahun)) { ?>

                                    <option value="<?= (int) $ta['id'] ?>"
                                        <?= $tahun_ajaran_id == $ta['id'] ? 'selected' : '' ?>>

                                        <?= htmlspecialchars($ta['nama']) ?>

                                    </option>

                                <?php } ?>

                            </select>
                        </div>

                        <hr>

                        <button type="submit" class="btn btn-primary">
                            Simpan Penempatan
                        </button>

                        <a href="kelola_penempatan_siswa.php"
                           class="btn btn-secondary">
                            Batal
                        </a>

                    </form>

                </div>
            </div>

            <div class="footer">
                © <?= date('Y') ?> Sistem Pelanggaran Siswa
            </div>

        </main>

    </div>
</div>

</body>
</html>
