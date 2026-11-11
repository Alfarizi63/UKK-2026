
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

// KHUSUS ADMIN
if (($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: dashboard.php');
    exit;
}

$error = '';
$success = '';

$kelas_id = (int) ($_POST['kelas_id'] ?? 0);
$guru_id = (int) ($_POST['guru_id'] ?? 0);
$tanggal_mulai = $_POST['tanggal_mulai'] ?? '';
$tanggal_selesai = $_POST['tanggal_selesai'] ?? '';
$status_aktif = (int) ($_POST['status_aktif'] ?? 1);

// ========================================
// AMBIL TAHUN AJARAN YANG AKTIF
// ========================================
$query_tahun = mysqli_query(
    $koneksi,
    "SELECT id, nama
     FROM t_tahun_ajaran
     WHERE status_aktif = 1
     ORDER BY tanggal_mulai DESC, id DESC
     LIMIT 1"
);

if (!$query_tahun) {
    die('Gagal mengambil tahun ajaran: ' . mysqli_error($koneksi));
}

$tahun_aktif = mysqli_fetch_assoc($query_tahun);

if (!$tahun_aktif) {
    die('Belum ada tahun ajaran aktif. Silakan aktifkan tahun ajaran terlebih dahulu.');
}

$tahun_ajaran_id = (int) $tahun_aktif['id'];

// ========================================
// PROSES SIMPAN / UPDATE WALI KELAS
// ========================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (
        $kelas_id <= 0 ||
        $guru_id <= 0 ||
        $tanggal_mulai === '' ||
        $tanggal_selesai === ''
    ) {
        $error = 'Semua kolom wajib diisi.';

    } elseif ($tanggal_mulai > $tanggal_selesai) {
        $error = 'Tanggal mulai tidak boleh melewati tanggal selesai.';

    } elseif (!in_array($status_aktif, [0, 1], true)) {
        $error = 'Status tidak valid.';

    } else {

        mysqli_begin_transaction($koneksi);

        try {

            // PASTIKAN TAHUN AJARAN MASIH AKTIF
            $stmt_tahun = mysqli_prepare(
                $koneksi,
                "SELECT id
                 FROM t_tahun_ajaran
                 WHERE id = ?
                   AND status_aktif = 1
                 LIMIT 1"
            );

            mysqli_stmt_bind_param(
                $stmt_tahun,
                "i",
                $tahun_ajaran_id
            );

            mysqli_stmt_execute($stmt_tahun);

            $hasil_tahun = mysqli_stmt_get_result($stmt_tahun);
            $tahun_valid = mysqli_num_rows($hasil_tahun) > 0;

            mysqli_stmt_close($stmt_tahun);

            if (!$tahun_valid) {
                throw new Exception(
                    'Tahun ajaran sudah tidak aktif. Silakan muat ulang halaman.'
                );
            }

            // PASTIKAN KELAS AKTIF
            $stmt_kelas = mysqli_prepare(
                $koneksi,
                "SELECT id
                 FROM t_kelas
                 WHERE id = ?
                   AND status_aktif = 1
                 LIMIT 1"
            );

            mysqli_stmt_bind_param(
                $stmt_kelas,
                "i",
                $kelas_id
            );

            mysqli_stmt_execute($stmt_kelas);

            $hasil_kelas = mysqli_stmt_get_result($stmt_kelas);
            $kelas_valid = mysqli_num_rows($hasil_kelas) > 0;

            mysqli_stmt_close($stmt_kelas);

            if (!$kelas_valid) {
                throw new Exception('Kelas tidak ditemukan atau tidak aktif.');
            }

            // PASTIKAN GURU TERDAFTAR
            // Tidak memeriksa atau mengubah role t_users.
            $stmt_guru = mysqli_prepare(
                $koneksi,
                "SELECT id
                 FROM t_guru
                 WHERE id = ?
                 LIMIT 1"
            );

            mysqli_stmt_bind_param(
                $stmt_guru,
                "i",
                $guru_id
            );

            mysqli_stmt_execute($stmt_guru);

            $hasil_guru = mysqli_stmt_get_result($stmt_guru);
            $guru_valid = mysqli_num_rows($hasil_guru) > 0;

            mysqli_stmt_close($stmt_guru);

            if (!$guru_valid) {
                throw new Exception('Data guru tidak ditemukan.');
            }

            // CEK APAKAH KELAS SUDAH MEMILIKI PENUGASAN
            $stmt_cek = mysqli_prepare(
                $koneksi,
                "SELECT id
                 FROM t_wali_kelas
                 WHERE kelas_id = ?
                   AND tahun_ajaran_id = ?
                 LIMIT 1"
            );

            mysqli_stmt_bind_param(
                $stmt_cek,
                "ii",
                $kelas_id,
                $tahun_ajaran_id
            );

            mysqli_stmt_execute($stmt_cek);

            $hasil_cek = mysqli_stmt_get_result($stmt_cek);
            $data_wali = mysqli_fetch_assoc($hasil_cek);

            mysqli_stmt_close($stmt_cek);

            if ($data_wali) {

                // UPDATE PENUGASAN YANG SUDAH ADA
                $wali_id = (int) $data_wali['id'];

                $stmt_update = mysqli_prepare(
                    $koneksi,
                    "UPDATE t_wali_kelas
                     SET guru_id = ?,
                         tanggal_mulai = ?,
                         tanggal_selesai = ?,
                         status_aktif = ?
                     WHERE id = ?"
                );

                mysqli_stmt_bind_param(
                    $stmt_update,
                    "issii",
                    $guru_id,
                    $tanggal_mulai,
                    $tanggal_selesai,
                    $status_aktif,
                    $wali_id
                );

                if (!mysqli_stmt_execute($stmt_update)) {
                    throw new Exception('Gagal memperbarui wali kelas.');
                }

                mysqli_stmt_close($stmt_update);

                $pesan_sukses = 'Penugasan wali kelas berhasil diperbarui.';

            } else {

                // TAMBAH PENUGASAN BARU
                $stmt_insert = mysqli_prepare(
                    $koneksi,
                    "INSERT INTO t_wali_kelas
                    (
                        tahun_ajaran_id,
                        kelas_id,
                        guru_id,
                        tanggal_mulai,
                        tanggal_selesai,
                        status_aktif
                    )
                    VALUES (?, ?, ?, ?, ?, ?)"
                );

                mysqli_stmt_bind_param(
                    $stmt_insert,
                    "iiissi",
                    $tahun_ajaran_id,
                    $kelas_id,
                    $guru_id,
                    $tanggal_mulai,
                    $tanggal_selesai,
                    $status_aktif
                );

                if (!mysqli_stmt_execute($stmt_insert)) {
                    throw new Exception('Gagal menyimpan penugasan wali kelas.');
                }

                mysqli_stmt_close($stmt_insert);

                $pesan_sukses = 'Guru berhasil ditugaskan sebagai wali kelas.';
            }

            mysqli_commit($koneksi);

            $_SESSION['success_wali'] = $pesan_sukses;

            header('Location: kelola_wali_kelas.php');
            exit;

        } catch (Throwable $e) {

            mysqli_rollback($koneksi);
            $error = $e->getMessage();
        }
    }
}

// ========================================
// AMBIL SEMUA KELAS AKTIF
// ========================================
$query_kelas = mysqli_query(
    $koneksi,
    "SELECT id, nama, tingkat, jurusan
     FROM t_kelas
     WHERE status_aktif = 1
     ORDER BY tingkat, jurusan, nama"
);

// ========================================
// AMBIL SEMUA GURU
// ========================================
$query_guru = mysqli_query(
    $koneksi,
    "SELECT id, nama, email
     FROM t_guru
     ORDER BY nama ASC"
);

if (!$query_kelas || !$query_guru) {
    die('Gagal mengambil data kelas atau guru: ' . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tambah Wali Kelas</title>

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
            min-height: 100vh;
        }

        .judul {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .subjudul {
            color: #6c757d;
            font-size: 14px;
            margin-bottom: 25px;
        }

        .card-form {
            background: #fff;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .card-header-custom {
            background: #fff;
            color: #343a40;
            padding: 16px 20px;
            border-bottom: 1px solid #dee2e6;
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
            margin-bottom: 8px;
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

        .form-text {
            font-size: 12px;
        }

        .btn {
            padding: 9px 18px;
            font-size: 13px;
            border-radius: 4px;
        }

        .info-tahun {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 12px 15px;
            border-radius: 5px;
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

<?php include 'includes/sidebar.php'; ?>

<div class="konten">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">

        <div>
            <h2 class="judul">Tambah Wali Kelas</h2>

            <p class="subjudul">
                Atur penugasan guru sebagai wali kelas pada tahun ajaran aktif.
            </p>
        </div>

        <div class="mb-3">
            <a href="kelola_wali_kelas.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i>
                Kembali
            </a>
        </div>

    </div>

    <!-- ERROR -->
    <?php if ($error !== ''): ?>

        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-circle mr-1"></i>

            <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>

            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>

    <?php endif; ?>

    <!-- FORM -->
    <div class="card-form">

        <div class="card-header-custom">
            <i class="fas fa-user-plus text-primary mr-2"></i>
            Form Penugasan Wali Kelas
        </div>

        <div class="card-body">

            <form method="POST" action="">

                <!-- TAHUN AJARAN OTOMATIS -->
                <div class="form-group">

                    <label>Tahun Ajaran Aktif</label>

                    <div class="info-tahun">
                        <i class="fas fa-calendar-alt text-primary mr-2"></i>

                        <strong>
                            <?= htmlspecialchars($tahun_aktif['nama'], ENT_QUOTES, 'UTF-8'); ?>
                        </strong>
                    </div>

                    <small class="form-text text-muted">
                        Tahun ajaran otomatis mengikuti pengaturan tahun ajaran aktif.
                    </small>

                </div>

                <!-- PILIH KELAS -->
                <div class="form-group">

                    <label>Pilih Kelas</label>

                    <input
                        type="text"
                        id="cariKelas"
                        class="form-control mb-2"
                        placeholder="Cari nama kelas..."
                    >

                    <select name="kelas_id"
                            id="kelas_id"
                            class="form-control"
                            required>

                        <option value="">-- Pilih Kelas --</option>

                        <?php while ($kelas = mysqli_fetch_assoc($query_kelas)): ?>

                            <option
                                value="<?= (int) $kelas['id']; ?>"
                                <?= $kelas_id === (int) $kelas['id'] ? 'selected' : ''; ?>
                            >
                                <?= htmlspecialchars(
                                    $kelas['nama'] . ' - ' .
                                    $kelas['tingkat'] . ' - ' .
                                    $kelas['jurusan'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ); ?>
                            </option>

                        <?php endwhile; ?>

                    </select>

                </div>

                <!-- PILIH GURU -->
                <div class="form-group">

                    <label>Pilih Guru</label>

                    <input
                        type="text"
                        id="cariGuru"
                        class="form-control mb-2"
                        placeholder="Cari nama atau email guru..."
                    >

                    <select name="guru_id"
                            id="guru_id"
                            class="form-control"
                            required>

                        <option value="">-- Pilih Guru --</option>

                        <?php while ($guru = mysqli_fetch_assoc($query_guru)): ?>

                            <option
                                value="<?= (int) $guru['id']; ?>"
                                <?= $guru_id === (int) $guru['id'] ? 'selected' : ''; ?>
                            >
                                <?= htmlspecialchars(
                                    $guru['nama'] . ' - ' . $guru['email'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ); ?>
                            </option>

                        <?php endwhile; ?>

                    </select>

                    <small class="form-text text-muted">
                        Guru yang sudah memiliki akun tetap bisa dipilih.
                        Role akun tidak akan diubah.
                    </small>

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

                <!-- STATUS -->
                <div class="form-group">

                    <label>Status Penugasan</label>

                    <select name="status_aktif" class="form-control" required>

                        <option value="1" <?= $status_aktif === 1 ? 'selected' : ''; ?>>
                            Aktif
                        </option>

                        <option value="0" <?= $status_aktif === 0 ? 'selected' : ''; ?>>
                            Tidak Aktif
                        </option>

                    </select>

                </div>

                <hr>

                <div class="d-flex justify-content-between flex-wrap">

                    <a href="kelola_wali_kelas.php" class="btn btn-secondary mb-2">
                        <i class="fas fa-times"></i>
                        Batal
                    </a>

                    <button type="submit" class="btn btn-primary mb-2">
                        <i class="fas fa-save"></i>
                        Simpan Penugasan
                    </button>

                </div>

            </form>

        </div>
    </div>

    <div class="text-center mt-4 text-muted" style="font-size: 13px;">
        © <?= date('Y'); ?> Sistem Informasi Pelanggaran Siswa
    </div>

</div>

<!-- JAVASCRIPT -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // =================================
    // PENCARIAN KELAS
    // =================================
    const cariKelas = document.getElementById('cariKelas');
    const selectKelas = document.getElementById('kelas_id');

    cariKelas.addEventListener('input', function () {

        const kata = this.value.toLowerCase();

        Array.from(selectKelas.options).forEach(function (option, index) {

            if (index === 0) return;

            option.hidden = !option.text.toLowerCase().includes(kata);

        });

        if (
            selectKelas.selectedIndex > 0 &&
            !selectKelas.options[selectKelas.selectedIndex]
                .text.toLowerCase().includes(kata)
        ) {
            selectKelas.selectedIndex = 0;
        }

    });

    // =================================
    // PENCARIAN GURU
    // =================================
    const cariGuru = document.getElementById('cariGuru');
    const selectGuru = document.getElementById('guru_id');

    cariGuru.addEventListener('input', function () {

        const kata = this.value.toLowerCase();

        Array.from(selectGuru.options).forEach(function (option, index) {

            if (index === 0) return;

            option.hidden = !option.text.toLowerCase().includes(kata);

        });

        if (
            selectGuru.selectedIndex > 0 &&
            !selectGuru.options[selectGuru.selectedIndex]
                .text.toLowerCase().includes(kata)
        ) {
            selectGuru.selectedIndex = 0;
        }

    });

    // =================================
    // ALERT OTOMATIS
    // =================================
    setTimeout(function () {

        document.querySelectorAll('.alert-danger').forEach(function (alert) {

            if (typeof $ !== 'undefined') {
                $(alert).alert('close');
            }

        });

    }, 5000);

});
</script>

</body>
</html>
