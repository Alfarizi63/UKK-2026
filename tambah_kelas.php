
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

$error = '';

$nama = '';
$tingkat = '';
$jurusan = '';
$status_aktif = '1';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nama = trim($_POST['nama'] ?? '');
    $tingkat = trim($_POST['tingkat'] ?? '');
    $jurusan = trim($_POST['jurusan'] ?? '');
    $status_aktif = $_POST['status_aktif'] ?? '1';

    if ($nama === '' || $tingkat === '' || $jurusan === '') {

        $error = "Semua data wajib diisi.";

    } elseif (!in_array($tingkat, ['X', 'XI', 'XII'], true)) {

        $error = "Tingkat kelas tidak valid.";

    } elseif (!in_array($jurusan, ['RPL', 'TKJ', 'TKR', 'BD', 'TSM'], true)) {

        $error = "Jurusan tidak valid.";

    } elseif (!in_array($status_aktif, ['0', '1'], true)) {

        $error = "Status kelas tidak valid.";

    } else {

        // Cek apakah kombinasi nama, tingkat, dan jurusan sudah ada
        $cek = mysqli_prepare(
            $koneksi,
            "SELECT id
             FROM t_kelas
             WHERE nama = ?
               AND tingkat = ?
               AND jurusan = ?
             LIMIT 1"
        );

        if (!$cek) {
            $error = "Gagal memeriksa data kelas: " . mysqli_error($koneksi);
        } else {

            mysqli_stmt_bind_param(
                $cek,
                "sss",
                $nama,
                $tingkat,
                $jurusan
            );

            mysqli_stmt_execute($cek);
            mysqli_stmt_store_result($cek);

            if (mysqli_stmt_num_rows($cek) > 0) {

                $error = "Kelas tersebut sudah terdaftar. Silakan gunakan nama, tingkat, atau jurusan yang berbeda.";

            } else {

                // Simpan kelas jika belum ada
                $stmt = mysqli_prepare(
                    $koneksi,
                    "INSERT INTO t_kelas (nama, tingkat, jurusan, status_aktif)
                     VALUES (?, ?, ?, ?)"
                );

                if (!$stmt) {

                    $error = "Gagal menyiapkan penyimpanan data: " . mysqli_error($koneksi);

                } else {

                    $status = (int) $status_aktif;

                    mysqli_stmt_bind_param(
                        $stmt,
                        "sssi",
                        $nama,
                        $tingkat,
                        $jurusan,
                        $status
                    );

                    if (mysqli_stmt_execute($stmt)) {
                        mysqli_stmt_close($stmt);
                        mysqli_stmt_close($cek);

                        header('Location: kelola_kelas.php');
                        exit;
                    }

                    $error = "Gagal menyimpan data kelas: " . mysqli_stmt_error($stmt);

                    mysqli_stmt_close($stmt);
                }
            }

            mysqli_stmt_close($cek);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kelas</title>

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css"
          integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO"
          crossorigin="anonymous">

    <style>
        body {
            background: #f8f9fa;
        }

        .sidebar {
            background: #212529;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            z-index: 1030;
            overflow-y: auto;
        }

        .sidebar .nav-link {
            color: white;
            padding: 8px 14px;
            border-radius: 4px;
        }

        .sidebar .nav-link:hover {
            background: #343a40;
            color: white;
        }

        .sidebar .nav-link.active {
            background: #007bff;
            color: white;
        }

        main {
            min-height: 100vh;
        }

        .form-card {
            background: white;
            padding: 25px;
            border-radius: 6px;
            box-shadow: 0 1px 5px rgba(0,0,0,0.10);
        }

        @media (max-width: 767.98px) {
            .sidebar {
                position: relative;
                height: auto;
                min-height: auto;
            }

            main {
                margin-left: 0 !important;
            }
        }
    </style>
</head>

<body>

<div class="container-fluid">
    <div class="row">

        <!-- SIDEBAR -->
        <aside class="col-md-3 col-lg-2 sidebar p-3">

            <h4 class="text-white mb-4">
                My Website
            </h4>

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
                    <a href="kelola_kelas.php" class="nav-link active">
                        Kelas
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="kelola_kategori_pelanggaran.php" class="nav-link">
                        kelola kategori pelanggaran
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="kelola_tahun_ajaran.php" class="nav-link">
                        Kelola tahun ajaran
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="laporan.php" class="nav-link">
                        Laporan
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="kelola_wali_kelas.php" class="nav-link">
                        kelola wali kelas
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="kelola_penempatan_siswa.php" class="nav-link">
                        kelola penempatan siswa
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="rekap_point.php" class="nav-link">
                        rekap poin
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="riwayat.php" class="nav-link">
                        riwayat
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="kelola_jenis_pelanggaran.php" class="nav-link">
                        kelola jenis pelanggaran
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="export_pelanggaran.php" class="nav-link">
                        export pelanggaran
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

        <!-- KONTEN -->
        <main class="col-md-9 col-lg-10 p-4 offset-md-3 offset-lg-2">

            <h2>Tambah Kelas</h2>

            <p class="text-muted">
                Tambahkan data kelas baru ke dalam sistem.
            </p>

            <a href="kelola_kelas.php" class="btn btn-secondary btn-sm mb-4">
                Kembali
            </a>

            <div class="form-card">

                <h5 class="mb-4">Form Tambah Kelas</h5>

                <?php if ($error !== '') { ?>
                    <div class="alert alert-danger">
                        <?= htmlspecialchars($error); ?>
                    </div>
                <?php } ?>

                <form method="POST">

                    <div class="form-group">
                        <label>Nama Kelas</label>

                        <input type="text"
                               name="nama"
                               class="form-control"
                               placeholder="Contoh: 1"
                               value="<?= htmlspecialchars($nama); ?>"
                               required>
                    </div>

                    <div class="form-group">
                        <label>Tingkat</label>

                        <select name="tingkat" class="form-control" required>
                            <option value="">Pilih Tingkat</option>

                            <option value="X" <?= $tingkat === 'X' ? 'selected' : ''; ?>>
                                X
                            </option>

                            <option value="XI" <?= $tingkat === 'XI' ? 'selected' : ''; ?>>
                                XI
                            </option>

                            <option value="XII" <?= $tingkat === 'XII' ? 'selected' : ''; ?>>
                                XII
                            </option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Jurusan</label>

                        <select name="jurusan" class="form-control" required>
                            <option value="">Pilih Jurusan</option>

                            <option value="RPL" <?= $jurusan === 'RPL' ? 'selected' : ''; ?>>
                                RPL
                            </option>

                            <option value="TKJ" <?= $jurusan === 'TKJ' ? 'selected' : ''; ?>>
                                TKJ
                            </option>

                            <option value="TKR" <?= $jurusan === 'TKR' ? 'selected' : ''; ?>>
                                TKR
                            </option>

                            <option value="BD" <?= $jurusan === 'BD' ? 'selected' : ''; ?>>
                                BD
                            </option>

                            <option value="TSM" <?= $jurusan === 'TSM' ? 'selected' : ''; ?>>
                                TSM
                            </option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Status</label>

                        <select name="status_aktif" class="form-control" required>
                            <option value="1" <?= $status_aktif === '1' ? 'selected' : ''; ?>>
                                Aktif
                            </option>

                            <option value="0" <?= $status_aktif === '0' ? 'selected' : ''; ?>>
                                Tidak Aktif
                            </option>
                        </select>
                    </div>

                    <hr>

                    <button type="submit" class="btn btn-primary">
                        Simpan
                    </button>

                    <a href="kelola_kelas.php" class="btn btn-secondary">
                        Batal
                    </a>

                </form>

            </div>

        </main>

    </div>
</div>

</body>
</html>
