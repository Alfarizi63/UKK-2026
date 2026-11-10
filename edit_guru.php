
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

// Khusus admin
if (($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: dashboard.php');
    exit;
}

$id = (int)($_GET['id'] ?? 0);

if ($id <= 0) {
    die("ID guru tidak valid.");
}

$sql = "SELECT t_guru.*, 
               t_users.name AS nama_akun,
               t_users.email AS email_akun
        FROM t_guru
        LEFT JOIN t_users ON t_guru.user_id = t_users.id
        WHERE t_guru.id = ?";

$stmt = mysqli_prepare($koneksi, $sql);

if (!$stmt) {
    die("Query gagal: " . mysqli_error($koneksi));
}

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$guru = mysqli_fetch_assoc($result);

if (!$guru) {
    die("Data guru tidak ditemukan.");
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Guru</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f8f9fa;
            font-family: Arial, sans-serif;
        }

        .main-content {
            margin-left: 250px;
            padding: 25px;
            min-width: 0;
        }

        .form-card {
            background: white;
            border-radius: 12px;
            padding: 28px;
            box-shadow: 0 3px 12px rgba(0,0,0,0.06);
            max-width: 850px;
        }

        .page-title {
            font-weight: 700;
            color: #343a40;
        }

        .form-label {
            font-weight: 600;
            margin-bottom: 7px;
        }

        .form-control,
        .form-select {
            padding: 10px 12px;
            border-radius: 7px;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13,110,253,.12);
        }

        .btn {
            padding: 9px 18px;
            border-radius: 7px;
        }

        .form-section {
            border-bottom: 1px solid #eee;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }

        @media(max-width: 767px) {
            .main-content {
                margin-left: 0;
                padding: 15px;
            }

            .form-card {
                padding: 18px;
            }
        }
    </style>
</head>

<body>

<?php include 'includes/sidebar.php'; ?>

<div class="main-content">

    <div class="mb-4">
        <h3 class="page-title">Edit Data Guru</h3>
        <p class="text-muted mb-0">
            Perbarui informasi guru dan akun pengguna.
        </p>
    </div>

    <div class="form-card">

        <?php if (isset($_SESSION['pesan'])): ?>
            <div class="alert alert-info">
                <?= htmlspecialchars($_SESSION['pesan']); ?>
            </div>
            <?php unset($_SESSION['pesan']); ?>
        <?php endif; ?>

        <form action="proses_edit_guru.php" method="POST" id="formGuru">

            <input type="hidden" name="id"
                value="<?= htmlspecialchars($guru['id']); ?>">

            <div class="form-section">

                <h5 class="mb-3">Informasi Guru</h5>

                <div class="mb-3">
                    <label class="form-label">NIP</label>

                    <input
                        type="text"
                        name="nip"
                        id="nip"
                        class="form-control"
                        value="<?= htmlspecialchars($guru['nip'] ?? ''); ?>"
                        maxlength="18"
                        pattern="NIP[0-9]{1,15}"
                        placeholder="NIP123456"
                        title="NIP harus diawali NIP dan diikuti 1 sampai 15 angka"
                        required
                    >

                    <small class="text-muted">
                        Format NIP: NIP diikuti 1 sampai 15 angka.
                        Contoh: NIP123456.
                    </small>

                    <div id="peringatanNip" class="text-danger small mt-1"></div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nama Guru</label>

                    <input
                        type="text"
                        name="nama"
                        class="form-control"
                        value="<?= htmlspecialchars($guru['nama'] ?? ''); ?>"
                        required
                    >
                </div>

                <div class="mb-3">
                    <label class="form-label">Status Guru</label>

                    <select name="status_aktif" class="form-select" required>
                        <option value="1"
                            <?= ((int)$guru['status_aktif'] === 1) ? 'selected' : ''; ?>>
                            Aktif
                        </option>

                        <option value="0"
                            <?= ((int)$guru['status_aktif'] === 0) ? 'selected' : ''; ?>>
                            Tidak Aktif
                        </option>
                    </select>
                </div>

            </div>

            <div class="form-section">

                <h5 class="mb-3">Informasi Akun</h5>

                <div class="mb-3">
                    <label class="form-label">Nama Akun</label>

                    <input
                        type="text"
                        name="name"
                        class="form-control"
                        value="<?= htmlspecialchars($guru['nama_akun'] ?? ''); ?>"
                        required
                    >
                </div>

                <div class="mb-3">
                    <label class="form-label">Email Akun</label>

                    <input
                        type="email"
                        name="email"
                        class="form-control"
                        value="<?= htmlspecialchars($guru['email_akun'] ?? ''); ?>"
                        required
                    >
                </div>

                <div class="mb-3">
                    <label class="form-label">Password Baru</label>

                    <input
                        type="password"
                        name="password"
                        class="form-control"
                        placeholder="Kosongkan jika tidak ingin mengubah password"
                    >

                    <small class="text-muted">
                        Password lama tetap digunakan jika kolom ini dikosongkan.
                    </small>
                </div>

            </div>

            <div class="d-flex justify-content-end gap-2">

                <a href="kelola_guru.php" class="btn btn-secondary">
                    Batal
                </a>

                <button type="submit" class="btn btn-primary">
                    Simpan Perubahan
                </button>

            </div>

        </form>

    </div>

</div>

<script>
const formGuru = document.getElementById('formGuru');
const nip = document.getElementById('nip');
const peringatanNip = document.getElementById('peringatanNip');

function validasiNip() {
    nip.value = nip.value.toUpperCase();

    if (!/^NIP[0-9]{1,15}$/.test(nip.value)) {
        peringatanNip.textContent =
            'NIP harus diawali NIP dan diikuti 1 sampai 15 angka!';
        nip.setCustomValidity('Format NIP tidak valid.');
        return false;
    }

    peringatanNip.textContent = '';
    nip.setCustomValidity('');
    return true;
}

nip.addEventListener('input', function() {
    nip.value = nip.value.toUpperCase();
    validasiNip();
});

formGuru.addEventListener('submit', function(e) {
    if (!validasiNip()) {
        e.preventDefault();
        alert('Periksa kembali format NIP guru!');
        nip.focus();
    }
});
</script>

</body>
</html>
