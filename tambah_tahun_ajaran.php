
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

$error = '';

$nama = '';
$tanggal_mulai = '';
$tanggal_selesai = '';
$status_aktif = 1;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nama = trim($_POST['nama'] ?? '');
    $tanggal_mulai = $_POST['tanggal_mulai'] ?? '';
    $tanggal_selesai = $_POST['tanggal_selesai'] ?? '';
    $status_aktif = (int) ($_POST['status_aktif'] ?? 0);

    if (
        $nama === '' ||
        $tanggal_mulai === '' ||
        $tanggal_selesai === ''
    ) {
        $error = 'Semua kolom wajib diisi.';

    } elseif ($tanggal_mulai > $tanggal_selesai) {
        $error = 'Tanggal mulai tidak boleh melewati tanggal selesai.';

    } else {

        $stmt = mysqli_prepare(
            $koneksi,
            "INSERT INTO t_tahun_ajaran
            (nama, tanggal_mulai, tanggal_selesai, status_aktif)
            VALUES (?, ?, ?, ?)"
        );

        if ($stmt) {

            mysqli_stmt_bind_param(
                $stmt,
                "sssi",
                $nama,
                $tanggal_mulai,
                $tanggal_selesai,
                $status_aktif
            );

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_close($stmt);
                header('Location: kelola_tahun_ajaran.php');
                exit;
            } else {
                $error = 'Gagal menyimpan data tahun ajaran.';
            }

            mysqli_stmt_close($stmt);

        } else {
            $error = 'Terjadi kesalahan pada database.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tambah Tahun Ajaran</title>

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

        .status-box {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 12px 15px;
            border-radius: 5px;
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
                    <a href="kelola_tahun_ajaran.php" class="nav-link active">
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

            <h2 class="judul">Tambah Tahun Ajaran</h2>

            <p class="subjudul">
                Tambahkan data tahun ajaran baru ke dalam sistem.
            </p>

            <a href="kelola_tahun_ajaran.php"
               class="btn btn-secondary mb-4">
                Kembali
            </a>

            <!-- FORM -->
            <div class="card-form">

                <div class="card-header-custom">
                    Form Tambah Tahun Ajaran
                </div>

                <div class="card-body">

                    <?php if ($error !== '') { ?>
                        <div class="alert alert-danger">
                            <?= htmlspecialchars($error) ?>
                        </div>
                    <?php } ?>

                    <form method="POST">

                        <div class="form-group">
                            <label>Nama Tahun Ajaran</label>

                            <input type="text"
                                   name="nama"
                                   class="form-control"
                                   placeholder="Contoh: 2026/2027"
                                   value="<?= htmlspecialchars($nama) ?>"
                                   required>
                        </div>

                        <div class="form-group">
                            <label>Tanggal Mulai</label>

                            <input type="date"
                                   name="tanggal_mulai"
                                   class="form-control"
                                   value="<?= htmlspecialchars($tanggal_mulai) ?>"
                                   required>
                        </div>

                        <div class="form-group">
                            <label>Tanggal Selesai</label>

                            <input type="date"
                                   name="tanggal_selesai"
                                   class="form-control"
                                   value="<?= htmlspecialchars($tanggal_selesai) ?>"
                                   required>
                        </div>

                        <div class="form-group">
                            <label>Status</label>

                            <div class="status-box">
                                <select name="status_aktif"
                                        class="form-control"
                                        required>

                                    <option value="1"
                                        <?= $status_aktif === 1 ? 'selected' : '' ?>>
                                        Aktif
                                    </option>

                                    <option value="0"
                                        <?= $status_aktif === 0 ? 'selected' : '' ?>>
                                        Tidak Aktif
                                    </option>

                                </select>
                            </div>
                        </div>

                        <hr>

                        <button type="submit" class="btn btn-primary">
                            Simpan
                        </button>

                        <a href="kelola_tahun_ajaran.php"
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
