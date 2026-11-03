<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

$error = '';
$akun_baru = $_SESSION['akun_wali_baru'] ?? null;
unset($_SESSION['akun_wali_baru']);

$tahun_ajaran_id = 0;
$kelas_id = 0;
$guru_id = 0;
$tanggal_mulai = '';
$tanggal_selesai = '';
$status_aktif = 1;

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

    } else {

        mysqli_begin_transaction($koneksi);

        try {

            // Ambil data guru dan email dari database
            $stmt_guru = mysqli_prepare(
                $koneksi,
                "SELECT id, nama, email
                 FROM t_guru
                 WHERE id = ?
                 LIMIT 1"
            );

            mysqli_stmt_bind_param($stmt_guru, "i", $guru_id);
            mysqli_stmt_execute($stmt_guru);

            $hasil_guru = mysqli_stmt_get_result($stmt_guru);
            $guru = mysqli_fetch_assoc($hasil_guru);

            mysqli_stmt_close($stmt_guru);

            if (!$guru) {
                throw new Exception('Data guru tidak ditemukan.');
            }

            $email = trim($guru['email']);

            if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception('Email guru belum tersedia atau tidak valid.');
            }

            // Cek apakah kelas sudah mempunyai wali kelas
            $cek = mysqli_prepare(
                $koneksi,
                "SELECT id FROM t_wali_kelas
                 WHERE tahun_ajaran_id = ?
                 AND kelas_id = ?
                 LIMIT 1"
            );

            mysqli_stmt_bind_param(
                $cek,
                "ii",
                $tahun_ajaran_id,
                $kelas_id
            );

            mysqli_stmt_execute($cek);
            $hasil_cek = mysqli_stmt_get_result($cek);
            $kelas_terisi = mysqli_num_rows($hasil_cek) > 0;
            mysqli_stmt_close($cek);

            if ($kelas_terisi) {
                throw new Exception(
                    'Kelas tersebut sudah memiliki wali kelas pada tahun ajaran yang dipilih.'
                );
            }

            // Cek apakah email sudah digunakan akun lain
            $cek_email = mysqli_prepare(
                $koneksi,
                "SELECT id FROM t_users WHERE email = ? LIMIT 1"
            );

            mysqli_stmt_bind_param($cek_email, "s", $email);
            mysqli_stmt_execute($cek_email);

            $hasil_email = mysqli_stmt_get_result($cek_email);
            $email_terpakai = mysqli_num_rows($hasil_email) > 0;

            mysqli_stmt_close($cek_email);

            if ($email_terpakai) {
                throw new Exception(
                    'Email guru sudah terdaftar di t_users. Periksa akun tersebut terlebih dahulu agar tidak membuat akun ganda.'
                );
            }

            // Hash password yang ditentukan sendiri oleh Admin
            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            // Buat akun wali kelas
            $role = 'wali_kelas';

            $buat_akun = mysqli_prepare(
                $koneksi,
                "INSERT INTO t_users (email, password, role)
                 VALUES (?, ?, ?)"
            );

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

            // Simpan penugasan wali kelas
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
                throw new Exception('Gagal menyimpan penugasan wali kelas.');
            }

            mysqli_stmt_close($stmt);

            // Semua berhasil
            mysqli_commit($koneksi);

            // Simpan informasi akun untuk ditampilkan setelah redirect
            $_SESSION['akun_wali_baru'] = [
                'nama' => $guru['nama'],
                'email' => $email,
                'password' => $password
            ];

            header('Location: tambah_wali_kelas.php');
            exit;

        } catch (Exception $e) {

            mysqli_rollback($koneksi);
            $error = $e->getMessage();
        }
    }
}

// Ambil data pilihan dari database
$query_tahun = mysqli_query(
    $koneksi,
    "SELECT id, nama FROM t_tahun_ajaran ORDER BY id DESC"
);

$query_kelas = mysqli_query(
    $koneksi,
    "SELECT id, nama, tingkat, jurusan
     FROM t_kelas
     ORDER BY tingkat, jurusan, nama"
);

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

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">

    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            background: #f8f9fa;
            font-family: Arial, sans-serif;
            color: #343a40;
        }

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

        .card-body { padding: 25px; }

        .form-group { margin-bottom: 20px; }

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

        .footer {
            margin-top: 30px;
            color: #6c757d;
            text-align: center;
            font-size: 13px;
        }

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

        <aside class="sidebar">

            <h4>My Website</h4>

            <ul class="nav nav-pills flex-column">

                <li class="nav-item mb-2">
                    <a href="dashboard.php" class="nav-link">Dashboard</a>
                </li>

                <li class="nav-item mb-2">
                    <a href="kelola_siswa.php" class="nav-link">Data Siswa</a>
                </li>

                <li class="nav-item mb-2">
                    <a href="kelola_guru.php" class="nav-link">Data Guru</a>
                </li>

                <li class="nav-item mb-2">
                    <a href="kelola_kelas.php" class="nav-link">Kelas</a>
                </li>

                <li class="nav-item mb-2">
                    <a href="kelola_kategori_pelanggaran.php" class="nav-link">
                        Kelola Kategori Pelanggaran
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="kelola_tahun_ajaran.php" class="nav-link">
                        Kelola Tahun Ajaran
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="laporan.php" class="nav-link">Laporan</a>
                </li>

                <li class="nav-item mb-2">
                    <a href="kelola_wali_kelas.php" class="nav-link active">
                        Kelola Wali Kelas
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="kelola_penempatan_siswa.php" class="nav-link">
                        Kelola Penempatan Siswa
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="rekap_point.php" class="nav-link">Rekap Poin</a>
                </li>

                <li class="nav-item mb-2">
                    <a href="riwayat.php" class="nav-link">Riwayat</a>
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
                    <a href="tentang_saya.php" class="nav-link">About Me</a>
                </li>

                <hr class="bg-secondary">

                <li class="nav-item">
                    <a href="logout.php" class="nav-link text-danger">Logout</a>
                </li>

            </ul>
        </aside>

        <main class="konten">

            <h2 class="judul">Tambah Wali Kelas</h2>

            <p class="subjudul">
                Tambahkan data guru wali kelas berdasarkan tahun ajaran.
            </p>

            <a href="kelola_wali_kelas.php" class="btn btn-secondary mb-4">
                Kembali
            </a>

            <div class="card-form">

                <div class="card-header-custom">
                    Form Tambah Wali Kelas
                </div>

                <div class="card-body">

                    <?php if ($error !== '') { ?>
                        <div class="alert alert-danger">
                            <?= htmlspecialchars($error) ?>
                        </div>
                    <?php } ?>

                    <?php if ($akun_baru) { ?>
                        <div class="alert alert-success">
                            <h5>Akun Wali Kelas Berhasil Dibuat</h5>

                            <p class="mb-1">
                                <strong>Nama:</strong>
                                <?= htmlspecialchars($akun_baru['nama']) ?>
                            </p>

                            <p class="mb-1">
                                <strong>Email:</strong>
                                <?= htmlspecialchars($akun_baru['email']) ?>
                            </p>

                            <p class="mb-1">
                                <strong>Password:</strong>
                                <code><?= htmlspecialchars($akun_baru['password']) ?></code>
                            </p>

                            <p class="mb-0">
                                <small>
                                    Simpan password ini dan berikan kepada guru.
                                    Password tidak akan ditampilkan kembali setelah halaman ditutup.
                                </small>
                            </p>
                        </div>
                    <?php } ?>

                    <form method="POST" action="">

                        <div class="form-group">
                            <label>Tahun Ajaran</label>

                            <select name="tahun_ajaran_id" class="form-control" required>
                                <option value="">-- Pilih Tahun Ajaran --</option>

                                <?php while ($ta = mysqli_fetch_assoc($query_tahun)) { ?>
                                    <option value="<?= (int) $ta['id'] ?>"
                                        <?= $tahun_ajaran_id == $ta['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($ta['nama']) ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Kelas</label>

                            <select name="kelas_id" class="form-control" required>
                                <option value="">-- Pilih Kelas --</option>

                                <?php while ($kelas = mysqli_fetch_assoc($query_kelas)) { ?>
                                    <option value="<?= (int) $kelas['id'] ?>"
                                        <?= $kelas_id == $kelas['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars(
                                            $kelas['nama'] . ' - ' .
                                            $kelas['tingkat'] . ' - ' .
                                            $kelas['jurusan']
                                        ) ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Guru Wali Kelas</label>

                            <select name="guru_id" class="form-control" required>
                                <option value="">-- Pilih Guru --</option>

                                <?php while ($guru = mysqli_fetch_assoc($query_guru)) { ?>
                                    <option value="<?= (int) $guru['id'] ?>"
                                        <?= $guru_id == $guru['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($guru['nama']) ?>
                                        - <?= htmlspecialchars($guru['email'] ?? '') ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Password Akun Wali Kelas</label>

                            <div class="input-group">
                                <input type="password"
                                       name="password"
                                       id="password"
                                       class="form-control"
                                       placeholder="Buat password sendiri"
                                       minlength="6"
                                       required>

                                <div class="input-group-append">
                                    <button type="button"
                                            class="btn btn-outline-secondary"
                                            id="togglePassword"
                                            onclick="togglePasswordVisibility()">
                                        Lihat
                                    </button>
                                </div>
                            </div>

                            <small class="form-text text-muted">
                                Password ditentukan oleh Admin, minimal 6 karakter.
                            </small>
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

                            <select name="status_aktif" class="form-control" required>
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

                        <hr>

                        <button type="submit" class="btn btn-primary">
                            Simpan
                        </button>

                        <a href="kelola_wali_kelas.php" class="btn btn-secondary">
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

<script>
function togglePasswordVisibility() {
    var inputPassword = document.getElementById('password');
    var tombol = document.getElementById('togglePassword');

    if (inputPassword.type === 'password') {
        inputPassword.type = 'text';
        tombol.textContent = 'Sembunyikan';
    } else {
        inputPassword.type = 'password';
        tombol.textContent = 'Lihat';
    }
}
</script>

</body>
</html>