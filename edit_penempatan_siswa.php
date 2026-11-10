```php
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

// VALIDASI ID
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    die('ID penempatan siswa tidak valid.');
}

// AMBIL DATA PENEMPATAN
$stmt = mysqli_prepare(
    $koneksi,
    "SELECT * FROM t_kelas_siswa WHERE id = ?"
);

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

if (!$data) {
    die('Data penempatan siswa tidak ditemukan.');
}

$error = '';

// NILAI AWAL FORM
$siswa_id = (int) $data['siswa_id'];
$kelas_id = (int) $data['kelas_id'];
$tahun_ajaran_id = (int) $data['tahun_ajaran_id'];

// PROSES UPDATE
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $siswa_id = (int) ($_POST['siswa_id'] ?? 0);
    $kelas_id = (int) ($_POST['kelas_id'] ?? 0);
    $tahun_ajaran_id = (int) ($_POST['tahun_ajaran_id'] ?? 0);

    if ($siswa_id <= 0 || $kelas_id <= 0 || $tahun_ajaran_id <= 0) {

        $error = 'Semua data harus dipilih.';

    } else {

        // CEK SISWA SUDAH DITEMPATKAN ATAU BELUM
        $cek = mysqli_prepare(
            $koneksi,
            "SELECT id
             FROM t_kelas_siswa
             WHERE siswa_id = ?
             AND tahun_ajaran_id = ?
             AND id != ?
             LIMIT 1"
        );

        mysqli_stmt_bind_param(
            $cek,
            "iii",
            $siswa_id,
            $tahun_ajaran_id,
            $id
        );

        mysqli_stmt_execute($cek);

        $hasil_cek = mysqli_stmt_get_result($cek);

        if (mysqli_num_rows($hasil_cek) > 0) {

            $error = 'Siswa sudah ditempatkan pada tahun ajaran tersebut.';

        }

        mysqli_stmt_close($cek);

        // UPDATE DATA
        if ($error === '') {

            $update = mysqli_prepare(
                $koneksi,
                "UPDATE t_kelas_siswa
                 SET siswa_id = ?,
                     kelas_id = ?,
                     tahun_ajaran_id = ?
                 WHERE id = ?"
            );

            if ($update) {

                mysqli_stmt_bind_param(
                    $update,
                    "iiii",
                    $siswa_id,
                    $kelas_id,
                    $tahun_ajaran_id,
                    $id
                );

                if (mysqli_stmt_execute($update)) {

                    mysqli_stmt_close($update);

                    $_SESSION['pesan'] = 'Data penempatan siswa berhasil diperbarui.';

                    header('Location: kelola_penempatan_siswa.php');
                    exit;

                } else {

                    $error = 'Gagal mengubah data penempatan siswa.';
                }

                mysqli_stmt_close($update);

            } else {

                $error = 'Terjadi kesalahan pada database.';
            }
        }
    }
}

// AMBIL DATA SISWA
$siswa_query = mysqli_query(
    $koneksi,
    "SELECT id, nis, nama
     FROM t_siswa
     ORDER BY nama ASC"
);

// AMBIL DATA KELAS
$kelas_query = mysqli_query(
    $koneksi,
    "SELECT id, nama, tingkat, jurusan
     FROM t_kelas
     ORDER BY tingkat, jurusan, nama"
);

// AMBIL DATA TAHUN AJARAN
$ta_query = mysqli_query(
    $koneksi,
    "SELECT id, nama
     FROM t_tahun_ajaran
     ORDER BY id DESC"
);

if (!$siswa_query || !$kelas_query || !$ta_query) {
    die('Gagal mengambil data: ' . htmlspecialchars(mysqli_error($koneksi)));
}
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Penempatan Siswa</title>

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

    <!-- SIDEBAR UTAMA -->
    <?php include 'includes/sidebar.php'; ?>

    <!-- KONTEN UTAMA -->
    <main class="konten">

        <!-- HEADER -->
        <div class="mb-3">

            <h2 class="judul">
                Edit Penempatan Siswa
            </h2>

            <p class="subjudul">
                Ubah data penempatan siswa ke kelas dan tahun ajaran.
            </p>

        </div>

        <!-- PESAN ERROR -->
        <?php if ($error !== ''): ?>

            <div class="alert alert-danger alert-dismissible fade show">

                <i class="fas fa-exclamation-circle mr-1"></i>

                <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>

                <button type="button"
                        class="close"
                        data-dismiss="alert">

                    <span>&times;</span>

                </button>

            </div>

        <?php endif; ?>

        <!-- CARD FORM -->
        <div class="card-custom">

            <div class="card-header-custom">

                <i class="fas fa-edit text-primary mr-2"></i>

                Form Edit Penempatan Siswa

            </div>

            <div class="card-body">

                <form method="POST">

                    <!-- SISWA -->
                    <div class="form-group">

                        <label>Siswa</label>

                        <select name="siswa_id"
                                class="form-control"
                                required>

                            <option value="">
                                -- Pilih Siswa --
                            </option>

                            <?php while ($siswa = mysqli_fetch_assoc($siswa_query)): ?>

                                <option
                                    value="<?= (int) $siswa['id']; ?>"
                                    <?= ((int) $siswa['id'] === $siswa_id) ? 'selected' : ''; ?>>

                                    <?= htmlspecialchars(
                                        $siswa['nis'] . ' - ' . $siswa['nama'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ); ?>

                                </option>

                            <?php endwhile; ?>

                        </select>

                    </div>

                    <!-- KELAS -->
                    <div class="form-group">

                        <label