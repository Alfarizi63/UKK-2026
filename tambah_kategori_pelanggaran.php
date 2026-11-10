
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

// Khusus admin
if (($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: dashboard.php');
    exit;
}

$pesan = '';
$nama = '';
$deskripsi = '';
$status_aktif = 1;

// Proses tambah kategori
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nama = trim($_POST['nama'] ?? '');
    $deskripsi = trim($_POST['deskripsi'] ?? '');
    $status_aktif = isset($_POST['status_aktif']) ? 1 : 0;

    // Validasi nama kategori
    if ($nama === '') {

        $pesan = 'Nama kategori wajib diisi!';

    } else {

        try {

            // Cek kategori duplikat
            $sqlCek = "SELECT id
                       FROM t_pelanggaran_kategori
                       WHERE nama = ?";

            $stmtCek = mysqli_prepare($koneksi, $sqlCek);

            if (!$stmtCek) {
                throw new Exception(mysqli_error($koneksi));
            }

            mysqli_stmt_bind_param($stmtCek, "s", $nama);
            mysqli_stmt_execute($stmtCek);

            $hasilCek = mysqli_stmt_get_result($stmtCek);
            $jumlah = mysqli_num_rows($hasilCek);

            mysqli_stmt_close($stmtCek);

            if ($jumlah > 0) {
                throw new Exception('Nama kategori sudah tersedia!');
            }

            // Simpan kategori
            $sql = "INSERT INTO t_pelanggaran_kategori
                    (nama, deskripsi, status_aktif)
                    VALUES (?, ?, ?)";

            $stmt = mysqli_prepare($koneksi, $sql);

            if (!$stmt) {
                throw new Exception(mysqli_error($koneksi));
            }

            mysqli_stmt_bind_param(
                $stmt,
                "ssi",
                $nama,
                $deskripsi,
                $status_aktif
            );

            if (!mysqli_stmt_execute($stmt)) {
                throw new Exception(mysqli_stmt_error($stmt));
            }

            mysqli_stmt_close($stmt);

            // Pesan berhasil
            $_SESSION['pesan'] = 'Kategori pelanggaran berhasil ditambahkan!';

            header('Location: kelola_kategori_pelanggaran.php');
            exit;

        } catch (Throwable $e) {

            $pesan = 'Gagal menambahkan kategori: ' . $e->getMessage();

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

    <!-- Bootstrap 4.1.3 -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body {
            background: #f5f6fa;
            font-family: Arial, sans-serif;
        }

        .content {
            margin-left: 250px;
            padding: 30px;
            min-width: 0;
        }

        .page-title {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 5px;
            color: #333;
        }

        .page-subtitle {
            color: #777;
            margin-bottom: 25px;
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
        }

        .card-header {
            background: #fff;
            border-bottom: 1px solid #eee;
            padding: 18px 22px;
            font-weight: 600;
        }

        .card-body {
            padding: 25px;
        }

        .form-group label {
            font-weight: 600;
            color: #444;
        }

        .form-control {
            border-radius: 6px;
            padding: 10px 12px;
            height: auto;
        }

        textarea.form-control {
            min-height: 120px;
        }

        .btn {
            border-radius: 6px;
            padding: 9px 18px;
        }

        .status-box {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 7px;
            border: 1px solid #e5e5e5;
        }

        @media (max-width: 768px) {
            .content {
                margin-left: 0;
                padding: 15px;
            }
        }
    </style>
</head>

<body>

    <?php include 'includes/sidebar.php'; ?>

    <div class="content">

        <div class="mb-4">

            <h4 class="page-title">
                <i class="bi bi-plus-circle"></i>
                Tambah Kategori Pelanggaran
            </h4>

            <p class="page-subtitle">
                Tambahkan kategori pelanggaran baru ke dalam sistem.
            </p>

        </div>

        <?php if ($pesan !== ''): ?>

            <div class="alert alert-danger alert-dismissible fade show">

                <?= htmlspecialchars($pesan, ENT_QUOTES, 'UTF-8'); ?>

                <button type="button" class="close" data-dismiss="alert">
                    <span>&times;</span>
                </button>

            </div>

        <?php endif; ?>

        <div class="card">

            <div class="card-header">
                <i class="bi bi-card-list"></i>
                Form Tambah Kategori
            </div>

            <div class="card-body">

                <form method="POST" id="formKategori">

                    <!-- Nama Kategori -->
                    <div class="form-group">

                        <label for="nama">Nama Kategori</label>

                        <input
                            type="text"
                            name="nama"
                            id="nama"
                            class="form-control"
                            placeholder="Masukkan nama kategori pelanggaran"
                            value="<?= htmlspecialchars($nama, ENT_QUOTES, 'UTF-8'); ?>"
                            maxlength="100"
                            required
                        >

                    </div>

                    <!-- Deskripsi -->
                    <div class="form-group">

                        <label for="deskripsi">Deskripsi</label>

                        <textarea
                            name="deskripsi"
                            id="deskripsi"
                            class="form-control"
                            placeholder="Masukkan deskripsi kategori pelanggaran"
                        ><?= htmlspecialchars($deskripsi, ENT_QUOTES, 'UTF-8'); ?></textarea>

                    </div>

                    <!-- Status Kategori -->
                    <div class="form-group">

                        <label>Status Kategori</label>

                        <div class="status-box">

                            <div class="custom-control custom-checkbox">

                                <input
                                    type="checkbox"
                                    class="custom-control-input"
                                    id="status_aktif"
                                    name="status_aktif"
                                    value="1"
                                    <?= $status_aktif == 1 ? 'checked' : ''; ?>
                                >

                                <label
                                    class="custom-control-label"
                                    for="status_aktif"
                                >
                                    Aktif
                                </label>

                            </div>

                            <small class="text-muted">
                                Centang jika kategori ingin langsung diaktifkan.
                            </small>

                        </div>

                    </div>

                    <hr>

                    <!-- Tombol -->
                    <div class="d-flex justify-content-between">

                        <a href="kelola_kategori_pelanggaran.php"
                           class="btn btn-secondary">

                            <i class="bi bi-arrow-left"></i>
                            Kembali

                        </a>

                        <button type="submit" class="btn btn-primary">

                            <i class="bi bi-save"></i>
                            Simpan Kategori

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>

</body>
</html>
