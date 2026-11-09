
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

$error = '';

$siswa_id = 0;
$kelas_id = 0;
$tahun_ajaran_id = (int) ($_GET['tahun_ajaran_id'] ?? 0);

// PROSES SIMPAN PENEMPATAN SISWA
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $siswa_id = (int) ($_POST['siswa_id'] ?? 0);
    $kelas_id = (int) ($_POST['kelas_id'] ?? 0);
    $tahun_ajaran_id = (int) ($_POST['tahun_ajaran_id'] ?? 0);

    if ($siswa_id <= 0 || $kelas_id <= 0 || $tahun_ajaran_id <= 0) {

        $error = 'Silakan lengkapi semua pilihan.';

    } else {

        // VALIDASI TAHUN AJARAN HARUS AKTIF
        $cek_tahun = mysqli_prepare(
            $koneksi,
            "SELECT id
             FROM t_tahun_ajaran
             WHERE id = ?
               AND status_aktif = 1
             LIMIT 1"
        );

        mysqli_stmt_bind_param(
            $cek_tahun,
            "i",
            $tahun_ajaran_id
        );

        mysqli_stmt_execute($cek_tahun);

        $hasil_tahun = mysqli_stmt_get_result($cek_tahun);
        $tahun_aktif = mysqli_num_rows($hasil_tahun) > 0;

        mysqli_stmt_close($cek_tahun);

        if (!$tahun_aktif) {

            $error = 'Tahun ajaran tidak aktif atau tidak ditemukan.';

        } else {

            // VALIDASI SISWA BELUM DITEMPATKAN
            $cek_siswa = mysqli_prepare(
                $koneksi,
                "SELECT id
                 FROM t_siswa
                 WHERE id = ?
                   AND status_aktif = 1
                 LIMIT 1"
            );

            mysqli_stmt_bind_param(
                $cek_siswa,
                "i",
                $siswa_id
            );

            mysqli_stmt_execute($cek_siswa);

            $hasil_cek_siswa = mysqli_stmt_get_result($cek_siswa);
            $siswa_aktif = mysqli_num_rows($hasil_cek_siswa) > 0;

            mysqli_stmt_close($cek_siswa);

            if (!$siswa_aktif) {

                $error = 'Siswa tidak aktif atau tidak ditemukan.';

            } else {

                $cek = mysqli_prepare(
                    $koneksi,
                    "SELECT id
                     FROM t_kelas_siswa
                     WHERE siswa_id = ?
                       AND tahun_ajaran_id = ?
                     LIMIT 1"
                );

                mysqli_stmt_bind_param(
                    $cek,
                    "ii",
                    $siswa_id,
                    $tahun_ajaran_id
                );

                mysqli_stmt_execute($cek);

                $hasil = mysqli_stmt_get_result($cek);
                $sudah_ditempatkan = mysqli_num_rows($hasil) > 0;

                mysqli_stmt_close($cek);

                if ($sudah_ditempatkan) {

                    $error = 'Siswa sudah ditempatkan pada tahun ajaran tersebut.';

                } else {

                    // VALIDASI KELAS HARUS AKTIF
                    $cek_kelas = mysqli_prepare(
                        $koneksi,
                        "SELECT id
                         FROM t_kelas
                         WHERE id = ?
                           AND status_aktif = 1
                         LIMIT 1"
                    );

                    mysqli_stmt_bind_param(
                        $cek_kelas,
                        "i",
                        $kelas_id
                    );

                    mysqli_stmt_execute($cek_kelas);

                    $hasil_kelas = mysqli_stmt_get_result($cek_kelas);
                    $kelas_aktif = mysqli_num_rows($hasil_kelas) > 0;

                    mysqli_stmt_close($cek_kelas);

                    if (!$kelas_aktif) {

                        $error = 'Kelas tidak aktif atau tidak ditemukan.';

                    } else {

                        // SIMPAN PENEMPATAN SISWA
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

                            header('Location: kelola_penempatan_siswa.php');
                            exit;

                        } else {

                            $error = 'Gagal menyimpan penempatan siswa: '
                                . mysqli_stmt_error($stmt);

                            mysqli_stmt_close($stmt);
                        }
                    }
                }
            }
        }
    }
}

// AMBIL HANYA TAHUN AJARAN AKTIF
$query_tahun = mysqli_query(
    $koneksi,
    "SELECT id, nama
     FROM t_tahun_ajaran
     WHERE status_aktif = 1
     ORDER BY tanggal_mulai DESC, id DESC"
);

if (!$query_tahun) {
    die('Gagal mengambil data tahun ajaran.');
}

// VALIDASI TAHUN AJARAN YANG DIPILIH
$tahun_tersedia = false;

if ($tahun_ajaran_id > 0) {

    $cek_pilihan = mysqli_prepare(
        $koneksi,
        "SELECT id
         FROM t_tahun_ajaran
         WHERE id = ?
           AND status_aktif = 1
         LIMIT 1"
    );

    mysqli_stmt_bind_param(
        $cek_pilihan,
        "i",
        $tahun_ajaran_id
    );

    mysqli_stmt_execute($cek_pilihan);

    $hasil_pilihan = mysqli_stmt_get_result($cek_pilihan);
    $tahun_tersedia = mysqli_num_rows($hasil_pilihan) > 0;

    mysqli_stmt_close($cek_pilihan);

    if (!$tahun_tersedia) {
        $tahun_ajaran_id = 0;
    }
}

// AMBIL SISWA YANG BELUM DITEMPATKAN
if ($tahun_ajaran_id > 0) {

    $stmt_siswa = mysqli_prepare(
        $koneksi,
        "SELECT s.id, s.nis, s.nama
         FROM t_siswa s
         WHERE s.status_aktif = 1
           AND NOT EXISTS (
               SELECT 1
               FROM t_kelas_siswa ks
               WHERE ks.siswa_id = s.id
                 AND ks.tahun_ajaran_id = ?
           )
         ORDER BY s.nama ASC"
    );

    mysqli_stmt_bind_param(
        $stmt_siswa,
        "i",
        $tahun_ajaran_id
    );

    mysqli_stmt_execute($stmt_siswa);

    $hasil_siswa = mysqli_stmt_get_result($stmt_siswa);

} else {

    // KOSONGKAN DAFTAR SISWA JIKA TAHUN BELUM DIPILIH
    $hasil_siswa = mysqli_query(
        $koneksi,
        "SELECT id, nis, nama
         FROM t_siswa
         WHERE 1 = 0"
    );
}

// AMBIL KELAS AKTIF
$query_kelas = mysqli_query(
    $koneksi,
    "SELECT id, nama, tingkat, jurusan
     FROM t_kelas
     WHERE status_aktif = 1
     ORDER BY tingkat, jurusan, nama"
);

if (!$hasil_siswa || !$query_kelas) {
    die('Gagal mengambil data pilihan. Periksa struktur tabel database.');
}
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tambah Penempatan Siswa</title>

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
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
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
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.15);
        }

        .btn {
            padding: 9px 18px;
            font-size: 14px;
            border-radius: 4px;
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

            .judul {
                font-size: 24px;
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
    <main class="konten">

        <!-- HEADER -->
        <div class="d-flex justify-content-between align-items-center flex-wrap">

            <div>
                <h2 class="judul">Tambah Penempatan Siswa</h2>

                <p class="subjudul">
                    Tambahkan siswa ke kelas berdasarkan tahun ajaran aktif.
                </p>
            </div>

            <div class="mb-3">
                <a href="kelola_penempatan_siswa.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-1"></i>
                    Kembali
                </a>
            </div>

        </div>

        <!-- PESAN ERROR -->
        <?php if ($error !== '') { ?>

            <div class="alert alert-danger alert-dismissible fade show">

                <i class="fas fa-exclamation-circle mr-1"></i>

                <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>

                <button type="button" class="close" data-dismiss="alert">
                    <span>&times;</span>
                </button>

            </div>

        <?php } ?>

        <!-- CARD FORM -->
        <div class="card-form">

            <div class="card-header-custom">
                <i class="fas fa-user-plus mr-2"></i>
                Form Tambah Penempatan Siswa
            </div>

            <div class="card-body">

                <form method="POST" action="">

                    <!-- TAHUN AJARAN -->
                    <div class="form-group">

                        <label>Tahun Ajaran</label>

                        <select
                            name="tahun_ajaran_id"
                            class="form-control"
                            required
                            onchange="window.location.href='tambah_penempatan_siswa.php?tahun_ajaran_id='+this.value"
                        >

                            <option value="">
                                -- Pilih Tahun Ajaran Aktif --
                            </option>

                            <?php while ($ta = mysqli_fetch_assoc($query_tahun)) { ?>

                                <option
                                    value="<?= (int) $ta['id']; ?>"
                                    <?= $tahun_ajaran_id == $ta['id'] ? 'selected' : ''; ?>
                                >

                                    <?= htmlspecialchars(
                                        $ta['nama'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ); ?>

                                </option>

                            <?php } ?>

                        </select>

                        <small class="form-text text-muted">
                            Hanya tahun ajaran yang berstatus aktif yang ditampilkan.
                        </small>

                    </div>

                    <!-- SISWA -->
                    <div class="form-group">

                        <label>Siswa</label>

                        <select
                            name="siswa_id"
                            class="form-control"
                            required
                            <?= $tahun_ajaran_id <= 0 ? 'disabled' : ''; ?>
                        >

                            <option value="">
                                <?= $tahun_ajaran_id > 0
                                    ? '-- Pilih Siswa --'
                                    : '-- Pilih Tahun Ajaran Terlebih Dahulu --'; ?>
                            </option>

                            <?php while ($siswa = mysqli_fetch_assoc($hasil_siswa)) { ?>

                                <option
                                    value="<?= (int) $siswa['id']; ?>"
                                    <?= $siswa_id == $siswa['id'] ? 'selected' : ''; ?>
                                >

                                    <?= htmlspecialchars(
                                        $siswa['nis'] . ' - ' . $siswa['nama'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ); ?>

                                </option>

                            <?php } ?>

                        </select>

                        <small class="form-text text-muted">
                            Siswa yang sudah ditempatkan pada tahun ajaran tersebut tidak akan ditampilkan.
                        </small>

                    </div>

                    <!-- KELAS -->
                    <div class="form-group">

                        <label>Kelas</label>

                        <select
                            name="kelas_id"
                            class="form-control"
                            required
                        >

                            <option value="">
                                -- Pilih Kelas --
                            </option>

                            <?php while ($kelas = mysqli_fetch_assoc($query_kelas)) { ?>

                                <option
                                    value="<?= (int) $kelas['id']; ?>"
                                    <?= $kelas_id == $kelas['id'] ? 'selected' : ''; ?>
                                >

                                    <?= htmlspecialchars(
                                        $kelas['nama'] . ' - ' .
                                        $kelas['tingkat'] . ' - ' .
                                        $kelas['jurusan'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ); ?>

                                </option>

                            <?php } ?>

                        </select>

                    </div>

                    <hr>

                    <!-- TOMBOL -->
                    <div class="d-flex justify-content-between flex-wrap">

                        <a href="kelola_penempatan_siswa.php"
                           class="btn btn-secondary mb-2">

                            <i class="fas fa-times mr-1"></i>
                            Batal

                        </a>

                        <button
                            type="submit"
                            class="btn btn-primary mb-2"
                            <?= $tahun_ajaran_id <= 0 ? 'disabled' : ''; ?>
                        >

                            <i class="fas fa-save mr-1"></i>
                            Simpan Penempatan

                        </button>

                    </div>

                </form>

            </div>

        </div>

        <!-- FOOTER -->
        <div class="footer">
            © <?= date('Y'); ?> Sistem Informasi Pelanggaran Siswa
        </div>

    </main>

    <!-- JAVASCRIPT BOOTSTRAP -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>

</body>
</html>
