
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

$pesan = '';
$nama = '';
$deskripsi = '';
$status_aktif = 1;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nama = trim($_POST['nama'] ?? '');
    $deskripsi = trim($_POST['deskripsi'] ?? '');
    $status_aktif = isset($_POST['status_aktif']) ? 1 : 0;

    if ($nama === '') {
        $pesan = 'Nama kategori wajib diisi.';
    } else {

        $stmt = mysqli_prepare(
            $koneksi,
            "INSERT INTO t_pelanggaran_kategori
            (nama, deskripsi, status_aktif)
            VALUES (?, ?, ?)"
        );

        if ($stmt) {

            mysqli_stmt_bind_param(
                $stmt,
                "ssi",
                $nama,
                $deskripsi,
                $status_aktif
            );

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_close($stmt);
                header('Location: kelola_kategori_pelanggaran.php');
                exit;
            } else {
                $pesan = 'Gagal menyimpan kategori.';
            }

            mysqli_stmt_close($stmt);

        } else {
            $pesan = 'Terjadi kesalahan pada database.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tambah Kategori Pelanggaran</title>

    <!-- BOOTSTRAP -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">

    <!-- ICON -->
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
            padding: 25px 28px;
            min-height: 100vh;
            width: calc(100% - 273px);
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

        /* FORM */
        .card-form {
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.05);
            margin-bottom: 20px;
        }

        .card-header-custom {
            background: #007bff;
            color: white;
            padding: 13px 20px;
            font-weight: bold;
            border-radius: 4px 4px 0 0;
        }

        .card-body {
            padding: 25px;
        }

        .form-control {
            height: 42px;
            font-size: 14px;
        }

        textarea.form-control {
            height: auto;
        }

        .form-group label {
            font-weight: 600;
            font-size: 14px;
        }

        .btn {
            font-size: 14px;
            padding: 9px 18px;
        }

        .status-box {
            background: #f8f9fa;
            padding: 13px;
            border: 1px solid #dee2e6;
            border-radius: 5px;
        }

        @media (max-width: 767px) {
            .sidebar {
                position: relative;
                width: 100%;
                height: auto;
                min-height: auto;
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
                    <a href="kelola_kategori_pelanggaran.php"
                       class="nav-link active">
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
                    <a href="kelola_penempatan_siswa.php" class="nav-link">
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

            <h2 class="judul">Tambah Kategori Pelanggaran</h2>

            <p class="subjudul">
                Tambahkan kategori pelanggaran baru ke dalam sistem.
            </p>

            <a href="kelola_kategori_pelanggaran.php"
               class="btn btn-secondary mb-4">
                <i class="bi bi-arrow-left"></i>
                Kembali
            </a>

            <!-- FORM TAMBAH -->
            <div class="card-form">

                <div class="card-header-custom">
                    <i class="bi bi-plus-circle"></i>
                    Form Tambah Kategori
                </div>

                <div class="card-body">

                    <?php if ($pesan !== '') { ?>
                        <div class="alert alert-danger">
                            <?= htmlspecialchars($pesan) ?>
                        </div>
                    <?php } ?>

                    <form method="POST">

                        <div class="form-group">
                            <label>Nama Kategori</label>

                            <input type="text"
                                   name="nama"
                                   class="form-control"
                                   placeholder="Masukkan nama kategori"
                                   value="<?= htmlspecialchars($nama) ?>"
                                   required>
                        </div>

                        <div class="form-group">
                            <label>Deskripsi</label>

                            <textarea name="deskripsi"
                                      class="form-control"
                                      rows="4"
                                      placeholder="Masukkan deskripsi kategori"><?= htmlspecialchars($deskripsi) ?></textarea>
                        </div>

                        <div class="form-group">
                            <label>Status Aktif</label>

                            <div class="status-box">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox"
                                           class="custom-control-input"
                                           id="status_aktif"
                                           name="status_aktif"
                                           value="1"
                                           <?= $status_aktif ? 'checked' : '' ?>>

                                    <label class="custom-control-label"
                                           for="status_aktif">
                                        Aktif
                                    </label>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i>
                            Simpan
                        </button>

                        <a href="kelola_kategori_pelanggaran.php"
                           class="btn btn-secondary">
                            Batal
                        </a>

                    </form>

                </div>
            </div>

            <p class="text-center text-muted small mt-4">
                © <?= date('Y') ?> Sistem Pelanggaran Siswa
            </p>

        </main>

    </div>
</div>

</body>
</html>
