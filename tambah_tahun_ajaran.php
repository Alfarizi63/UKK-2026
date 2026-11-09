
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

$error = '';

$nama = '';
$tanggal_mulai = '';
$tanggal_selesai = '';
$status_aktif = 1;

// PROSES TAMBAH TAHUN AJARAN
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nama = trim($_POST['nama'] ?? '');
    $tanggal_mulai = $_POST['tanggal_mulai'] ?? '';
    $tanggal_selesai = $_POST['tanggal_selesai'] ?? '';
    $status_aktif = (int) ($_POST['status_aktif'] ?? 0);

    // VALIDASI INPUT
    if (
        $nama === '' ||
        $tanggal_mulai === '' ||
        $tanggal_selesai === ''
    ) {
        $error = 'Semua kolom wajib diisi.';

    } elseif (!in_array($status_aktif, [0, 1], true)) {
        $error = 'Status tahun ajaran tidak valid.';

    } elseif ($tanggal_mulai > $tanggal_selesai) {
        $error = 'Tanggal mulai tidak boleh melewati tanggal selesai.';

    } else {

        // CEK DUPLIKAT NAMA TAHUN AJARAN
        $cek = mysqli_prepare(
            $koneksi,
            "SELECT id FROM t_tahun_ajaran
             WHERE nama = ?
             LIMIT 1"
        );

        if (!$cek) {
            $error = 'Gagal memeriksa data tahun ajaran.';
        } else {

            mysqli_stmt_bind_param($cek, "s", $nama);
            mysqli_stmt_execute($cek);

            $hasil_cek = mysqli_stmt_get_result($cek);

            if (mysqli_num_rows($hasil_cek) > 0) {
                $error = 'Tahun ajaran tersebut sudah terdaftar.';
            }

            mysqli_stmt_close($cek);
        }

        // SIMPAN DATA JIKA TIDAK ADA ERROR
        if ($error === '') {

            mysqli_begin_transaction($koneksi);

            try {

                // JIKA TAHUN AJARAN BARU AKTIF,
                // NONAKTIFKAN TAHUN AJARAN LAIN
                if ($status_aktif === 1) {

                    $nonaktifkan = mysqli_query(
                        $koneksi,
                        "UPDATE t_tahun_ajaran
                         SET status_aktif = 0"
                    );

                    if (!$nonaktifkan) {
                        throw new Exception(mysqli_error($koneksi));
                    }
                }

                // INSERT TAHUN AJARAN
                $stmt = mysqli_prepare(
                    $koneksi,
                    "INSERT INTO t_tahun_ajaran
                    (nama, tanggal_mulai, tanggal_selesai, status_aktif)
                    VALUES (?, ?, ?, ?)"
                );

                if (!$stmt) {
                    throw new Exception(mysqli_error($koneksi));
                }

                mysqli_stmt_bind_param(
                    $stmt,
                    "sssi",
                    $nama,
                    $tanggal_mulai,
                    $tanggal_selesai,
                    $status_aktif
                );

                if (!mysqli_stmt_execute($stmt)) {
                    throw new Exception(mysqli_stmt_error($stmt));
                }

                mysqli_stmt_close($stmt);

                mysqli_commit($koneksi);

                header('Location: kelola_tahun_ajaran.php');
                exit;

            } catch (Throwable $e) {

                mysqli_rollback($koneksi);

                $error = 'Gagal menyimpan tahun ajaran: ' . $e->getMessage();
            }
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
        body {
            margin: 0;
            background: #f8f9fa;
            font-family: Arial, sans-serif;
            color: #343a40;
        }

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

    <!-- SIDEBAR -->
    <?php include 'includes/sidebar.php'; ?>

    <!-- KONTEN UTAMA -->
    <div class="konten">

        <h2 class="judul">Tambah Tahun Ajaran</h2>

        <p class="subjudul">
            Tambahkan data tahun ajaran baru ke dalam sistem.
        </p>

        <a href="kelola_tahun_ajaran.php"
           class="btn btn-secondary mb-4">
            Kembali
        </a>

        <div class="card-form">

            <div class="card-header-custom">
                Form Tambah Tahun Ajaran
            </div>

            <div class="card-body">

                <?php if ($error !== '') { ?>
                    <div class="alert alert-danger">
                        <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
                    </div>
                <?php } ?>

                <form method="POST">

                    <div class="form-group">
                        <label>Nama Tahun Ajaran</label>

                        <input type="text"
                               name="nama"
                               class="form-control"
                               placeholder="Contoh: 2026/2027"
                               value="<?= htmlspecialchars($nama, ENT_QUOTES, 'UTF-8') ?>"
                               required>
                    </div>

                    <div class="form-group">
                        <label>Tanggal Mulai</label>

                        <input type="date"
                               name="tanggal_mulai"
                               class="form-control"
                               value="<?= htmlspecialchars($tanggal_mulai, ENT_QUOTES, 'UTF-8') ?>"
                               required>
                    </div>

                    <div class="form-group">
                        <label>Tanggal Selesai</label>

                        <input type="date"
                               name="tanggal_selesai"
                               class="form-control"
                               value="<?= htmlspecialchars($tanggal_selesai, ENT_QUOTES, 'UTF-8') ?>"
                               required>
                    </div>

                    <div class="form-group">
                        <label>Status Tahun Ajaran</label>

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
                        <i class="fa fa-save"></i> Simpan
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

    </div>

</body>
</html>
