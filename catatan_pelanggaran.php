
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

$role = $_SESSION['role'] ?? '';
$nama_login = $_SESSION['name'] ?? '';
$email_login = $_SESSION['email'] ?? '';

$error = '';
$success = $_SESSION['success_catatan'] ?? '';
unset($_SESSION['success_catatan']);

if (isset($_SESSION['error_catatan'])) {
    $error = $_SESSION['error_catatan'];
    unset($_SESSION['error_catatan']);
}

if (!in_array($role, ['admin', 'guru', 'wali_kelas'], true)) {
    header('Location: dashboard.php');
    exit;
}

// Menu berdasarkan role
if ($role === 'admin') {
    $menu = $menu_admin ?? [];
} elseif ($role === 'guru') {
    $menu = $menu_guru ?? [];
} else {
    $menu = $menu_wali_kelas ?? [];
}

// Ambil email dari akun login jika session email belum tersedia
if ($email_login === '') {
    $user_id = (int)($_SESSION['id'] ?? $_SESSION['user_id'] ?? 0);

    if ($user_id > 0) {
        $stmt = mysqli_prepare($koneksi, "
            SELECT email
            FROM t_users
            WHERE id = ?
            LIMIT 1
        ");

        mysqli_stmt_bind_param($stmt, 'i', $user_id);
        mysqli_stmt_execute($stmt);

        $hasil_user = mysqli_stmt_get_result($stmt);
        $data_user = mysqli_fetch_assoc($hasil_user);

        mysqli_stmt_close($stmt);

        $email_login = $data_user['email'] ?? '';
    }
}

// Ambil tahun ajaran aktif
$q_tahun = mysqli_query($koneksi, "
    SELECT id, nama
    FROM t_tahun_ajaran
    WHERE status_aktif = 1
    LIMIT 1
");

$data_tahun = mysqli_fetch_assoc($q_tahun);
$tahun_ajaran_id = (int)($data_tahun['id'] ?? 0);

// Ambil data guru
$guru_id = 0;
$nama_guru = '';

if ($role !== 'admin') {

    if ($email_login !== '') {
        $stmt = mysqli_prepare($koneksi, "
            SELECT id, nama
            FROM t_guru
            WHERE email = ?
            LIMIT 1
        ");

        mysqli_stmt_bind_param($stmt, 's', $email_login);
        mysqli_stmt_execute($stmt);

        $hasil_guru = mysqli_stmt_get_result($stmt);
        $data_guru = mysqli_fetch_assoc($hasil_guru);

        mysqli_stmt_close($stmt);
    } else {
        $data_guru = null;
    }

    // Fallback berdasarkan nama login
    if (!$data_guru && $nama_login !== '') {
        $stmt = mysqli_prepare($koneksi, "
            SELECT id, nama
            FROM t_guru
            WHERE nama = ?
            LIMIT 1
        ");

        mysqli_stmt_bind_param($stmt, 's', $nama_login);
        mysqli_stmt_execute($stmt);

        $hasil_guru = mysqli_stmt_get_result($stmt);
        $data_guru = mysqli_fetch_assoc($hasil_guru);

        mysqli_stmt_close($stmt);
    }

    if ($data_guru) {
        $guru_id = (int)$data_guru['id'];
        $nama_guru = $data_guru['nama'];
    } else {
        $error = 'Data guru teu kapanggih. Cocogkeun akun login jeung data guru.';
    }
}

// Ambil data guru untuk admin
$data_guru_admin = [];

if ($role === 'admin') {
    $q_guru = mysqli_query($koneksi, "
        SELECT id, nama
        FROM t_guru
        ORDER BY nama ASC
    ");

    while ($row = mysqli_fetch_assoc($q_guru)) {
        $data_guru_admin[] = $row;
    }
}

// Ambil data siswa
$data_siswa = [];

if ($tahun_ajaran_id > 0 && ($role === 'admin' || $guru_id > 0)) {

    if ($role === 'wali_kelas') {

        $stmt = mysqli_prepare($koneksi, "
            SELECT
                s.id,
                s.nis,
                s.nama,
                k.id AS kelas_id,
                k.nama AS nama_kelas
            FROM t_kelas_siswa ks
            INNER JOIN t_siswa s ON s.id = ks.siswa_id
            INNER JOIN t_kelas k ON k.id = ks.kelas_id
            INNER JOIN t_wali_kelas wk
                ON wk.kelas_id = ks.kelas_id
                AND wk.tahun_ajaran_id = ks.tahun_ajaran_id
            WHERE ks.tahun_ajaran_id = ?
                AND s.status_aktif = 1
                AND wk.guru_id = ?
                AND wk.status_aktif = 1
            ORDER BY k.nama ASC, s.nama ASC
        ");

        mysqli_stmt_bind_param(
            $stmt,
            'ii',
            $tahun_ajaran_id,
            $guru_id
        );

    } else {

        $stmt = mysqli_prepare($koneksi, "
            SELECT
                s.id,
                s.nis,
                s.nama,
                k.id AS kelas_id,
                k.nama AS nama_kelas
            FROM t_kelas_siswa ks
            INNER JOIN t_siswa s ON s.id = ks.siswa_id
            INNER JOIN t_kelas k ON k.id = ks.kelas_id
            WHERE ks.tahun_ajaran_id = ?
                AND s.status_aktif = 1
            ORDER BY k.nama ASC, s.nama ASC
        ");

        mysqli_stmt_bind_param($stmt, 'i', $tahun_ajaran_id);
    }

    mysqli_stmt_execute($stmt);
    $hasil_siswa = mysqli_stmt_get_result($stmt);

    while ($row = mysqli_fetch_assoc($hasil_siswa)) {
        $data_siswa[] = $row;
    }

    mysqli_stmt_close($stmt);
}

// Ambil jenis pelanggaran aktif
$data_pelanggaran = [];

$q_pelanggaran = mysqli_query($koneksi, "
    SELECT
        id,
        kode,
        nama,
        poin,
        pelanggaran_kategori_id
    FROM t_pelanggaran
    WHERE status_aktif = 1
    ORDER BY nama ASC
");

while ($row = mysqli_fetch_assoc($q_pelanggaran)) {
    $data_pelanggaran[] = $row;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catatan Pelanggaran</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f5f6fa;
            font-family: Arial, sans-serif;
        }

        .main-content {
            margin-left: 250px;
            padding: 25px;
            min-height: 100vh;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,.06);
        }

        .card-header {
            background: #212529;
            color: white;
            padding: 16px 20px;
            border-radius: 12px 12px 0 0 !important;
        }

        .form-label {
            font-weight: 600;
        }

        .form-control,
        .form-select {
            padding: 10px;
        }

        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                padding: 15px;
            }
        }
    </style>
</head>
<body>

<?php include 'includes/sidebar.php'; ?>

<div class="main-content">

    <div class="mb-4">
        <h3 class="fw-bold mb-1">Catatan Pelanggaran</h3>
        <p class="text-muted mb-0">Form pencatatan pelanggaran siswa</p>
    </div>

    <?php if ($success): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($success); ?>
        </div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <?php if ($tahun_ajaran_id <= 0): ?>
        <div class="alert alert-warning">
            Belum aya tahun ajaran anu aktif.
        </div>
    <?php elseif ($role !== 'admin' && $guru_id <= 0): ?>
        <div class="alert alert-danger">
            Data guru teu kapanggih. Pastikeun ngaran akun login sarua jeung ngaran guru dina database.
        </div>
    <?php else: ?>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Form Catatan Pelanggaran</h5>
        </div>

        <div class="card-body p-4">

            <form action="proses_catatan_pelanggaran.php" method="POST">

                <input type="hidden" name="tahun_ajaran_id"
                       value="<?= $tahun_ajaran_id; ?>">

                <div class="mb-3">
                    <label class="form-label">Tahun Ajaran</label>
                    <input type="text" class="form-control"
                           value="<?= htmlspecialchars($data_tahun['nama']); ?>"
                           readonly>
                </div>

                <?php if ($role === 'admin'): ?>

                    <div class="mb-3">
                        <label class="form-label">Guru yang Mencatat</label>

                        <select name="guru_id" class="form-select" required>
                            <option value="">-- Pilih Guru --</option>

                            <?php foreach ($data_guru_admin as $guru): ?>
                                <option value="<?= (int)$guru['id']; ?>">
                                    <?= htmlspecialchars($guru['nama']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                <?php else: ?>

                    <div class="mb-3">
                        <label class="form-label">Guru yang Mencatat</label>

                        <input type="text" class="form-control"
                               value="<?= htmlspecialchars($nama_guru); ?>"
                               readonly>

                        <input type="hidden" name="guru_id"
                               value="<?= $guru_id; ?>">
                    </div>

                <?php endif; ?>

                <div class="mb-3">
                    <label class="form-label">Pilih Siswa</label>

                    <select name="siswa_id" id="siswa_id"
                            class="form-select" required>

                        <option value="">-- Pilih Siswa --</option>

                        <?php foreach ($data_siswa as $siswa): ?>
                            <option
                                value="<?= (int)$siswa['id']; ?>"
                                data-kelas="<?= (int)$siswa['kelas_id']; ?>">

                                <?= htmlspecialchars($siswa['nis']); ?>
                                -
                                <?= htmlspecialchars($siswa['nama']); ?>
                                (<?= htmlspecialchars($siswa['nama_kelas']); ?>)

                            </option>
                        <?php endforeach; ?>

                    </select>

                    <input type="hidden" name="kelas_id" id="kelas_id">
                </div>

                <div class="mb-3">
                    <label class="form-label">Jenis Pelanggaran</label>

                    <select name="pelanggaran_id" id="pelanggaran_id"
                            class="form-select" required>

                        <option value="">-- Pilih Pelanggaran --</option>

                        <?php foreach ($data_pelanggaran as $pelanggaran): ?>
                            <option
                                value="<?= (int)$pelanggaran['id']; ?>"
                                data-poin="<?= (int)$pelanggaran['poin']; ?>">

                                <?= htmlspecialchars($pelanggaran['kode']); ?>
                                -
                                <?= htmlspecialchars($pelanggaran['nama']); ?>

                            </option>
                        <?php endforeach; ?>

                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Poin Pelanggaran</label>

                    <input type="text" id="poin_tampil"
                           class="form-control"
                           value="0 Poin" readonly>
                </div>

                <div class="mb-3">
                    <label class="form-label">Tanggal Pelanggaran</label>

                    <input type="date" name="tanggal"
                           class="form-control"
                           value="<?= date('Y-m-d'); ?>" required>
                </div>

                <div class="mb-4">
                    <label class="form-label">Keterangan</label>

                    <textarea name="keterangan"
                              class="form-control"
                              rows="4"
                              placeholder="Tuliskan keterangan pelanggaran..."
                              required></textarea>
                </div>

                <button type="submit" class="btn btn-dark"
                    <?= (empty($data_siswa) || empty($data_pelanggaran)) ? 'disabled' : ''; ?>>
                    Simpan Catatan
                </button>

                <a href="dashboard.php" class="btn btn-secondary">
                    Kembali
                </a>

            </form>

        </div>
    </div>

    <?php endif; ?>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const siswaSelect = document.getElementById('siswa_id');
    const kelasInput = document.getElementById('kelas_id');

    const pelanggaranSelect = document.getElementById('pelanggaran_id');
    const poinTampil = document.getElementById('poin_tampil');

    if (siswaSelect) {
        siswaSelect.addEventListener('change', function () {
            const pilihan = this.options[this.selectedIndex];

            kelasInput.value = pilihan.dataset.kelas || '';
        });
    }

    if (pelanggaranSelect) {
        pelanggaranSelect.addEventListener('change', function () {
            const pilihan = this.options[this.selectedIndex];

            poinTampil.value =
                (pilihan.dataset.poin || 0) + ' Poin';
        });
    }

});
</script>

</body>
</html>
