
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

// KHUSUS ADMIN
if (($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: dashboard.php');
    exit;
}

$error = '';

$akun_baru = $_SESSION['akun_wali_baru'] ?? null;
unset($_SESSION['akun_wali_baru']);

$tahun_ajaran_id = (int) ($_GET['tahun_ajaran_id'] ?? 0);
$kelas_id = 0;
$guru_id = 0;
$tanggal_mulai = '';
$tanggal_selesai = '';
$status_aktif = 1;

// PROSES PENYIMPANAN
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $tahun_ajaran_id = (int) ($_POST['tahun_ajaran_id'] ?? 0);
    $kelas_id = (int) ($_POST['kelas_id'] ?? 0);
    $guru_id = (int) ($_POST['guru_id'] ?? 0);
    $tanggal_mulai = $_POST['tanggal_mulai'] ?? '';
    $tanggal_selesai = $_POST['tanggal_selesai'] ?? '';
    $status_aktif = (int) ($_POST['status_aktif'] ?? 1);
    $password = trim($_POST['password'] ?? '');

    if (
        $tahun_ajaran_id <= 0 ||
        $kelas_id <= 0 ||
        $guru_id <= 0 ||
        $password === '' ||
        $tanggal_mulai === '' ||
        $tanggal_selesai === ''
    ) {
        $error = 'Semua kolom wajib diisi.';

    } elseif (strlen($password) < 6) {
        $error = 'Password minimal 6 karakter.';

    } elseif ($tanggal_mulai > $tanggal_selesai) {
        $error = 'Tanggal mulai tidak boleh melewati tanggal selesai.';

    } elseif (!in_array($status_aktif, [0, 1], true)) {
        $error = 'Status tidak valid.';

    } else {

        mysqli_begin_transaction($koneksi);

        try {

            // PASTIKAN TAHUN AJARAN MASIH AKTIF
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
            $tahun_valid = mysqli_num_rows($hasil_tahun) > 0;

            mysqli_stmt_close($cek_tahun);

            if (!$tahun_valid) {
                throw new Exception(
                    'Tahun ajaran tidak aktif atau tidak ditemukan. Silakan pilih tahun ajaran yang aktif.'
                );
            }

            // PASTIKAN KELAS AKTIF DAN BELUM MEMILIKI WALI KELAS
            $cek_kelas = mysqli_prepare(
                $koneksi,
                "SELECT k.id
                 FROM t_kelas k
                 WHERE k.id = ?
                   AND k.status_aktif = 1
                   AND NOT EXISTS (
                       SELECT 1
                       FROM t_wali_kelas wk
                       WHERE wk.kelas_id = k.id
                         AND wk.tahun_ajaran_id = ?
                   )
                 LIMIT 1"
            );

            mysqli_stmt_bind_param(
                $cek_kelas,
                "ii",
                $kelas_id,
                $tahun_ajaran_id
            );

            mysqli_stmt_execute($cek_kelas);

            $hasil_kelas = mysqli_stmt_get_result($cek_kelas);
            $kelas_valid = mysqli_num_rows($hasil_kelas) > 0;

            mysqli_stmt_close($cek_kelas);

            if (!$kelas_valid) {
                throw new Exception(
                    'Kelas tersebut sudah memiliki wali kelas atau tidak aktif. Silakan pilih kelas lain.'
                );
            }

            // AMBIL DATA GURU
            $stmt_guru = mysqli_prepare(
                $koneksi,
                "SELECT id, nama, email
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
            $guru = mysqli_fetch_assoc($hasil_guru);

            mysqli_stmt_close($stmt_guru);

            if (!$guru) {
                throw new Exception('Data guru tidak ditemukan.');
            }

            $email = trim($guru['email']);

            if (
                $email === '' ||
                !filter_var($email, FILTER_VALIDATE_EMAIL)
            ) {
                throw new Exception(
                    'Email guru belum tersedia atau tidak valid.'
                );
            }

            // CEK EMAIL AKUN
            $cek_email = mysqli_prepare(
                $koneksi,
                "SELECT id
                 FROM t_users
                 WHERE email = ?
                 LIMIT 1"
            );

            mysqli_stmt_bind_param(
                $cek_email,
                "s",
                $email
            );

            mysqli_stmt_execute($cek_email);

            $hasil_email = mysqli_stmt_get_result($cek_email);
            $email_terpakai = mysqli_num_rows($hasil_email) > 0;

            mysqli_stmt_close($cek_email);

            if ($email_terpakai) {
                throw new Exception(
                    'Email guru sudah terdaftar di t_users. Periksa akun tersebut terlebih dahulu.'
                );
            }

            // BUAT AKUN WALI KELAS
            $password_hash = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            $role = 'wali_kelas';

            $buat_akun = mysqli_prepare(
                $koneksi,
                "INSERT INTO t_users (email, password, role)
                 VALUES (?, ?, ?)"
            );

            if (!$buat_akun) {
                throw new Exception('Gagal menyiapkan pembuatan akun.');
            }

            mysqli_stmt_bind_param(
                $buat_akun,
                "sss",
                $email,
                $password_hash,
                $role
            );

            if (!mysqli_stmt_execute($buat_akun)) {
                throw new Exception('Gagal membuat akun wali kelas.');
            }

            mysqli_stmt_close($buat_akun);

            // SIMPAN PENUGASAN WALI KELAS
            $stmt = mysqli_prepare(
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

            if (!$stmt) {
                throw new Exception(
                    'Gagal menyiapkan penugasan wali kelas.'
                );
            }

            mysqli_stmt_bind_param(
                $stmt,
                "iiissi",
                $tahun_ajaran_id,
                $kelas_id,
                $guru_id,
                $tanggal_mulai,
                $tanggal_selesai,
                $status_aktif
            );

            if (!mysqli_stmt_execute($stmt)) {
                throw new Exception(
                    'Gagal menyimpan penugasan wali kelas.'
                );
            }

            mysqli_stmt_close($stmt);

            // SIMPAN TRANSAKSI
            mysqli_commit($koneksi);

            $_SESSION['akun_wali_baru'] = [
                'nama' => $guru['nama'],
                'email' => $email,
                'password' => $password
            ];

            header('Location: tambah_wali_kelas.php');
            exit;

        } catch (Throwable $e) {

            mysqli_rollback($koneksi);
            $error = $e->getMessage();
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

// PASTIKAN TAHUN AJARAN YANG DIPILIH MASIH AKTIF
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

// AMBIL KELAS YANG BELUM MEMILIKI WALI KELAS
if ($tahun_ajaran_id > 0) {

    $stmt_kelas = mysqli_prepare(
        $koneksi,
        "SELECT k.id, k.nama, k.tingkat, k.jurusan
         FROM t_kelas k
         WHERE k.status_aktif = 1
           AND NOT EXISTS (
               SELECT 1
               FROM t_wali_kelas wk
               WHERE wk.kelas_id = k.id
                 AND wk.tahun_ajaran_id = ?
           )
         ORDER BY k.tingkat, k.jurusan, k.nama"
    );

    mysqli_stmt_bind_param(
        $stmt_kelas,
        "i",
        $tahun_ajaran_id
    );

    mysqli_stmt_execute($stmt_kelas);

    $query_kelas = mysqli_stmt_get_result($stmt_kelas);

} else {

    $query_kelas = mysqli_query(
        $koneksi,
        "SELECT id, nama, tingkat, jurusan
         FROM t_kelas
         WHERE 1 = 0"
    );
}

// AMBIL DATA GURU
$query_guru = mysqli_query(
    $koneksi,
    "SELECT id, nama, email
     FROM t_guru
     ORDER BY nama ASC"
);

if (!$query_tahun || !$query_kelas || !$query_guru) {
    die('Gagal mengambil data pilihan. Periksa struktur tabel.');
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

        .btn i {
            margin-right: 5px;
        }

        .input-group .form-control {
            height: 42px;
        }

        .input-group-append .btn {
            height: 42px;
        }

        .akun-info {
            border-left: 4px solid #28a745;
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

        <!-- HEADER -->
        <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">

            <div>
                <h2 class="judul">Tambah Wali Kelas</h2>

                <p class="subjudul">
                    Tambahkan guru sebagai wali kelas berdasarkan tahun ajaran aktif.
                </p>
            </div>

            <div class="mb-3">
                <a href="kelola_wali_kelas.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
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

        <!-- INFORMASI AKUN BARU -->
        <?php if ($akun_baru) { ?>

            <div class="alert alert-success akun-info">

                <h5 class="font-weight-bold">
                    <i class="fas fa-check-circle"></i>
                    Akun Wali Kelas Berhasil Dibuat
                </h5>

                <hr>

                <p class="mb-2">
                    <strong>Nama:</strong>
                    <?= htmlspecialchars($akun_baru['nama'], ENT_QUOTES, 'UTF-8'); ?>
                </p>

                <p class="mb-2">
                    <strong>Email:</strong>
                    <?= htmlspecialchars($akun_baru['email'], ENT_QUOTES, 'UTF-8'); ?>
                </p>

                <p class="mb-2">
                    <strong>Password:</strong>
                    <code><?= htmlspecialchars($akun_baru['password'], ENT_QUOTES, 'UTF-8'); ?></code>
                </p>

                <small>
                    Simpan informasi akun ini. Password tidak akan ditampilkan kembali setelah halaman ditutup.
                </small>

            </div>

        <?php } ?>

        <!-- FORM -->
        <div class="card-form">

            <div class="card-header-custom">
                <i class="fas fa-user-plus text-primary mr-2"></i>
                Form Tambah Wali Kelas
            </div>

            <div class="card-body">

                <form method="POST" action="">

                    <!-- TAHUN AJARAN AKTIF -->
                    <div class="form-group">

                        <label>Tahun Ajaran</label>

                        <select
                            name="tahun_ajaran_id"
                            class="form-control"
                            required
                            onchange="window.location.href='tambah_wali_kelas.php?tahun_ajaran_id='+this.value"
                        >

                            <option value="">-- Pilih Tahun Ajaran Aktif --</option>

                            <?php while ($ta = mysqli_fetch_assoc($query_tahun)) { ?>

                                <option
                                    value="<?= (int) $ta['id']; ?>"
                                    <?= $tahun_ajaran_id == $ta['id'] ? 'selected' : ''; ?>
                                >
                                    <?= htmlspecialchars($ta['nama'], ENT_QUOTES, 'UTF-8'); ?>
                                </option>

                            <?php } ?>

                        </select>

                        <small class="form-text text-muted">
                            Hanya tahun ajaran dengan status aktif yang ditampilkan.
                        </small>

                    </div>

                    <!-- KELAS -->
                    <div class="form-group">

                        <label>Kelas</label>

                        <select
                            name="kelas_id"
                            class="form-control"
                            required
                            <?= $tahun_ajaran_id <= 0 ? 'disabled' : ''; ?>
                        >

                            <option value="">
                                <?= $tahun_ajaran_id > 0
                                    ? '-- Pilih Kelas --'
                                    : '-- Pilih Tahun Ajaran Terlebih Dahulu --'; ?>
                            </option>

                            <?php while ($kelas = mysqli_fetch_assoc($query_kelas)) { ?>

                                <option value="<?= (int) $kelas['id']; ?>">
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

                        <small class="form-text text-muted">
                            Kelas yang sudah memiliki wali kelas pada tahun ajaran terpilih tidak akan ditampilkan.
                        </small>

                    </div>

                    <!-- GURU -->
                    <div class="form-group">

                        <label>Guru Wali Kelas</label>

                        <select name="guru_id" class="form-control" required>

                            <option value="">-- Pilih Guru --</option>

                            <?php while ($guru = mysqli_fetch_assoc($query_guru)) { ?>

                                <option value="<?= (int) $guru['id']; ?>">
                                    <?= htmlspecialchars($guru['nama'], ENT_QUOTES, 'UTF-8'); ?>
                                    - <?= htmlspecialchars($guru['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
                                </option>

                            <?php } ?>

                        </select>

                    </div>

                    <!-- PASSWORD -->
                    <div class="form-group">

                        <label>Password Akun Wali Kelas</label>

                        <div class="input-group">

                            <input
                                type="password"
                                name="password"
                                id="password"
                                class="form-control"
                                placeholder="Buat password akun wali kelas"
                                minlength="6"
                                required
                            >

                            <div class="input-group-append">

                                <button
                                    type="button"
                                    class="btn btn-outline-secondary"
                                    id="togglePassword"
                                    onclick="togglePasswordVisibility()"
                                >
                                    <i class="fas fa-eye"></i>
                                    Lihat
                                </button>

                            </div>

                        </div>

                        <small class="form-text text-muted">
                            Password ditentukan oleh Admin dan minimal 6 karakter.
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

                        <label>Status</label>

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

                    <!-- TOMBOL -->
                    <div class="d-flex justify-content-between flex-wrap">

                        <a href="kelola_wali_kelas.php" class="btn btn-secondary mb-2">
                            <i class="fas fa-times"></i>
                            Batal
                        </a>

                        <button type="submit" class="btn btn-primary mb-2">
                            <i class="fas fa-save"></i>
                            Simpan Wali Kelas
                        </button>

                    </div>

                </form>

            </div>
        </div>

        <!-- FOOTER -->
        <div class="footer">
            © <?= date('Y'); ?> Sistem Informasi Pelanggaran Siswa
        </div>

    </div>

    <!-- JAVASCRIPT -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>

    <script>
        // TAMPILKAN / SEMBUNYIKAN PASSWORD
        function togglePasswordVisibility() {

            const password = document.getElementById('password');
            const tombol = document.getElementById('togglePassword');

            if (password.type === 'password') {

                password.type = 'text';
                tombol.innerHTML = '<i class="fas fa-eye-slash"></i> Sembunyikan';

            } else {

                password.type = 'password';
                tombol.innerHTML = '<i class="fas fa-eye"></i> Lihat';

            }
        }

        // HILANGKAN ALERT ERROR OTOMATIS
        $(document).ready(function () {

            setTimeout(function () {
                $('.alert-danger').alert('close');
            }, 5000);

        });
    </script>

</body>
</html>
