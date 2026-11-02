
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama'] ?? '');
    $tingkat = trim($_POST['tingkat'] ?? '');
    $jurusan = trim($_POST['jurusan'] ?? '');
    $status_aktif = (int) ($_POST['status_aktif'] ?? 1);

    if ($nama === '' || $tingkat === '' || $jurusan === '') {
        $error = "Semua data wajib diisi.";
    } else {
        $stmt = mysqli_prepare(
            $koneksi,
            "INSERT INTO t_kelas (nama, tingkat, jurusan, status_aktif)
             VALUES (?, ?, ?, ?)"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "sssi",
            $nama,
            $tingkat,
            $jurusan,
            $status_aktif
        );

        if (mysqli_stmt_execute($stmt)) {
            header('Location: kelola_kelas.php');
            exit;
        }

        $error = "Gagal menyimpan data kelas: " . mysqli_error($koneksi);
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

            <h2>Tambah Kelasussss</h2>

            <p class="text-muted">
                Tambahkan data kelas baru ke dalam sistem.
            </p>

            <a href="kelola_kelas.php" class="btn btn-secondary btn-sm mb-4">
                Kembali
            </a>

            <div class="form-card">

                <h5 class="mb-4">Form Tambah Kelas</h5>

                <?php if (isset($error)) { ?>
                    <div class="alert alert-danger">
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php } ?>

                <form method="POST">

                    <div class="form-group">
                        <label>Nama Kelas</label>
                        <input type="text"
                               name="nama"
                               class="form-control"
                               placeholder="Masukkan nama kelas"
                               required>
                    </div>

                    <div class="form-group">
                        <label>Tingkat</label>
                        <select name="tingkat" class="form-control" required>
                            <option value="">Pilih Tingkat</option>
                            <option value="X">X</option>
                            <option value="XI">XI</option>
                            <option value="XII">XII</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Jurusan</label>
                        <select name="jurusan" class="form-control" required>
                            <option value="">Pilih Jurusan</option>
                            <option value="RPL">RPL</option>
                            <option value="TKJ">TKJ</option>
                            <option value="TKR">TKR</option>
                            <option value="BD">BD</option>
                            <option value="TSM">TSM</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Status</label>
                        <select name="status_aktif" class="form-control" required>
                            <option value="1">Aktif</option>
                            <option value="0">Tidak Aktif</option>
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
