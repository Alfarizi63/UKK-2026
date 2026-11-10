```php
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

if (!isset($koneksi) || !($koneksi instanceof mysqli)) {
    die('Koneksi database tidak tersedia.');
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('ID tahun ajaran tidak valid.');
}

$id = (int) $_GET['id'];
$pesan = '';

// AMBIL DATA TAHUN AJARAN
$stmt = mysqli_prepare(
    $koneksi,
    "SELECT * FROM t_tahun_ajaran WHERE id = ?"
);

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

if (!$data) {
    die('Data tahun ajaran tidak ditemukan.');
}

// NILAI AWAL FORM
$nama = $data['nama'];
$tanggal_mulai = $data['tanggal_mulai'];
$tanggal_selesai = $data['tanggal_selesai'];
$status_aktif = (int) $data['status_aktif'];

// PROSES UPDATE
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nama = trim($_POST['nama'] ?? '');
    $tanggal_mulai = $_POST['tanggal_mulai'] ?? '';
    $tanggal_selesai = $_POST['tanggal_selesai'] ?? '';
    $status_aktif = (int) ($_POST['status_aktif'] ?? -1);

    if (
        $nama === '' ||
        $tanggal_mulai === '' ||
        $tanggal_selesai === ''
    ) {
        $pesan = 'Semua data wajib diisi.';

    } elseif ($tanggal_selesai < $tanggal_mulai) {
        $pesan = 'Tanggal selesai tidak boleh sebelum tanggal mulai.';

    } elseif (!in_array($status_aktif, [0, 1], true)) {
        $pesan = 'Status tahun ajaran tidak valid.';

    } else {

        // CEK NAMA DUPLIKAT
        $cek = mysqli_prepare(
            $koneksi,
            "SELECT id FROM t_tahun_ajaran
             WHERE nama = ? AND id != ?
             LIMIT 1"
        );

        mysqli_stmt_bind_param($cek, "si", $nama, $id);
        mysqli_stmt_execute($cek);

        $hasil_cek = mysqli_stmt_get_result($cek);

        if (mysqli_num_rows($hasil_cek) > 0) {
            $pesan = 'Nama tahun ajaran sudah terdaftar.';
        }

        mysqli_stmt_close($cek);

        // PROSES UPDATE
        if ($pesan === '') {

            mysqli_begin_transaction($koneksi);

            try {

                // JIKA TAHUN AJARAN DIPILIH AKTIF
                // NONAKTIFKAN TAHUN AJARAN LAIN TERLEBIH DAHULU
                if ($status_aktif === 1) {

                    $nonaktif = mysqli_prepare(
                        $koneksi,
                        "UPDATE t_tahun_ajaran
                         SET status_aktif = 0
                         WHERE id != ?"
                    );

                    if (!$nonaktif) {
                        throw new Exception('Gagal menonaktifkan tahun ajaran sebelumnya.');
                    }

                    mysqli_stmt_bind_param($nonaktif, "i", $id);

                    if (!mysqli_stmt_execute($nonaktif)) {
                        throw new Exception('Gagal menonaktifkan tahun ajaran sebelumnya.');
                    }

                    mysqli_stmt_close($nonaktif);
                }

                // UPDATE TAHUN AJARAN YANG DIPILIH
                $update = mysqli_prepare(
                    $koneksi,
                    "UPDATE t_tahun_ajaran
                     SET nama = ?,
                         tanggal_mulai = ?,
                         tanggal_selesai = ?,
                         status_aktif = ?
                     WHERE id = ?"
                );

                if (!$update) {
                    throw new Exception('Gagal menyiapkan proses update.');
                }

                mysqli_stmt_bind_param(
                    $update,
                    "sssii",
                    $nama,
                    $tanggal_mulai,
                    $tanggal_selesai,
                    $status_aktif,
                    $id
                );

                if (!mysqli_stmt_execute($update)) {
                    throw new Exception('Gagal mengubah data tahun ajaran.');
                }

                mysqli_stmt_close($update);

                // SIMPAN PERUBAHAN
                mysqli_commit($koneksi);

                $_SESSION['pesan'] = 'Data tahun ajaran berhasil diperbarui.';

                header('Location: kelola_tahun_ajaran.php');
                exit;

            } catch (Exception $e) {

                mysqli_rollback($koneksi);

                $pesan = $e->getMessage();
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

    <title>Edit Tahun Ajaran</title>

    <!-- BOOTSTRAP 4.1.3 -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">

    <!-- FONT AWESOME -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

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

        .konten {
            margin-left: 273px;
            padding: 30px;
            min-height: 100vh;
        }

        .judul {
            font-size: 26px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .subjudul {
            color: #6c757d;
            font-size: 14px;
            margin-bottom: 25px;
        }

        .card-custom {
            background: #fff;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .05);
        }

        .card-header-custom {
            padding: 16px 20px;
            background: #fff;
            border-bottom: 1px solid #dee2e6;
            font-weight: bold;
        }

        .card-body {
            padding: 25px;
        }

        .form-group label {
            font-weight: 600;
            font-size: 14px;
        }

        .form-control {
            font-size: 14px;
            height: 40px;
        }

        .btn {
            font-size: 13px;
        }

        .btn i {
            margin-right: 5px;
        }

        .info-status {
            font-size: 12px;
            color: #6c757d;
            margin-top: 6px;
        }

        @media (max-width: 767px) {
            .konten {
                margin-left: 0;
                padding: 18px;
            }

            .judul {
                font-size: 23px;
            }

            .card-body {
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

        <div class="mb-3">
            <h2 class="judul">Edit Tahun Ajaran</h2>

            <p class="subjudul">
                Ubah informasi dan status tahun ajaran sekolah.
            </p>
        </div>

        <!-- PESAN ERROR -->
        <?php if ($pesan !== '') { ?>

            <div class="alert alert-danger alert-dismissible fade show">
                <i class="fas fa-exclamation-circle mr-1"></i>

                <?= htmlspecialchars($pesan, ENT_QUOTES, 'UTF-8'); ?>

                <button type="button" class="close" data-dismiss="alert">
                    <span>&times;</span>
                </button>
            </div>

        <?php } ?>

        <div class="card-custom">

            <div class="card-header-custom">
                <i class="fas fa-edit text-primary mr-2"></i>
                Form Edit Tahun Ajaran
            </div>

            <div class="card-body">

                <form method="POST">

                    <!-- NAMA TAHUN AJARAN -->
                    <div class="form-group">
                        <label>Nama Tahun Ajaran</label>

                        <input
                            type="text"
                            name="nama"
                            class="form-control"
                            value="<?= htmlspecialchars($nama, ENT_QUOTES, 'UTF-8'); ?>"
                            placeholder="Contoh: 2026/2027"
                            required
                        >
                    </div>

                    <!-- TANGGAL MULAI -->
                    <div class="form-group">
                        <label>Tanggal Mulai</label>

                        <input
                            type="date"
                            name="tanggal_mulai"
                            class="form-control"
                            value="<?= htmlspecialchars($tanggal_mulai, ENT_QUOTES, 'UTF-8'); ?>"
                            required
                        >
                    </div>

                    <!-- TANGGAL SELESAI -->
                    <div class="form-group">
                        <label>Tanggal Selesai</label>

                        <input
                            type="date"
                            name="tanggal_selesai"
                            class="form-control"
                            value="<?= htmlspecialchars($tanggal_selesai, ENT_QUOTES, 'UTF-8'); ?>"
                            required
                        >
                    </div>

                    <!-- STATUS TAHUN AJARAN -->
                    <div class="form-group">
                        <label>Status Tahun Ajaran</label>

                        <select
                            name="status_aktif"
                            class="form-control"
                            required
                        >
                            <option value="1" <?= $status_aktif === 1 ? 'selected' : ''; ?>>
                                Aktif
                            </option>

                            <option value="0" <?= $status_aktif === 0 ? 'selected' : ''; ?>>
                                Nonaktif
                            </option>
                        </select>

                        <small class="info-status">
                            Jika tahun ajaran ini diaktifkan, tahun ajaran aktif sebelumnya
                            akan otomatis menjadi nonaktif.
                        </small>
                    </div>

                    <hr>

                    <!-- TOMBOL -->
                    <div class="d-flex justify-content-between flex-wrap">

                        <a href="kelola_tahun_ajaran.php"
                           class="btn btn-secondary mb-2">
                            <i class="fas fa-arrow-left"></i>
                            Kembali
                        </a>

                        <button type="submit"
                                class="btn btn-primary mb-2">
                            <i class="fas fa-save"></i>
                            Simpan Perubahan
                        </button>

                    </div>

                </form>

            </div>
        </div>

    </div>

    <!-- JAVASCRIPT -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>

</body>
</html>