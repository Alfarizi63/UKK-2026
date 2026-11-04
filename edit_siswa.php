
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

// Khusus admin
if (($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: dashboard.php');
    exit;
}

// Cek ID siswa
if (!isset($_GET['id'])) {
    die("ID siswa tidak ditemukan!");
}

$id = (int) $_GET['id'];

// Ambil data siswa
$query = mysqli_query($koneksi, "SELECT * FROM t_siswa WHERE id='$id'");

if (!$query) {
    die("Query gagal: " . mysqli_error($koneksi));
}

$siswa = mysqli_fetch_assoc($query);

if (!$siswa) {
    die("Data siswa tidak ditemukan!");
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Siswa</title>

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
        <h3 class="page-title">Edit Data Siswa</h3>
        <p class="text-muted mb-0">
            Perbarui informasi data siswa di bawah ini.
        </p>
    </div>

    <div class="form-card">

        <form action="proses_edit_siswa.php" method="POST" id="formSiswa">

            <input type="hidden" name="id"
                value="<?= htmlspecialchars($siswa['id']) ?>">

            <div class="form-section">

                <h5 class="mb-3">Informasi Identitas</h5>

                <div class="mb-3">
                    <label class="form-label">NIS</label>
                    <input
                        type="text"
                        name="nis"
                        id="nis"
                        class="form-control"
                        value="<?= htmlspecialchars($siswa['nis'] ?? '') ?>"
                        maxlength="9"
                        pattern="[0-9]{1,9}"
                        inputmode="numeric"
                        required
                    >
                    <small class="text-muted">
                        NIS maksimal 9 digit angka.
                    </small>
                    <div id="peringatanNis" class="text-danger small"></div>
                </div>

                <div class="mb-3">
                    <label class="form-label">NISN</label>
                    <input
                        type="text"
                        name="nisn"
                        id="nisn"
                        class="form-control"
                        value="<?= htmlspecialchars($siswa['nisn'] ?? '') ?>"
                        maxlength="10"
                        minlength="10"
                        pattern="[0-9]{10}"
                        inputmode="numeric"
                        required
                    >
                    <small class="text-muted">
                        NISN harus terdiri dari 10 digit angka.
                    </small>
                    <div id="peringatanNisn" class="text-danger small"></div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nama Siswa</label>
                    <input
                        type="text"
                        name="nama"
                        class="form-control"
                        value="<?= htmlspecialchars($siswa['nama'] ?? '') ?>"
                        required
                    >
                </div>

                <div class="mb-3">
                    <label class="form-label">Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="form-select" required>
                        <option value="L"
                            <?= $siswa['jenis_kelamin'] == 'L' ? 'selected' : '' ?>>
                            Laki-laki
                        </option>

                        <option value="P"
                            <?= $siswa['jenis_kelamin'] == 'P' ? 'selected' : '' ?>>
                            Perempuan
                        </option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Tanggal Lahir</label>
                    <input
                        type="date"
                        name="tanggal_lahir"
                        class="form-control"
                        value="<?= htmlspecialchars($siswa['tanggal_lahir'] ?? '') ?>"
                        required
                    >
                </div>

            </div>

            <div class="form-section">

                <h5 class="mb-3">Informasi Tambahan</h5>

                <div class="mb-3">
                    <label class="form-label">Alamat</label>
                    <textarea
                        name="alamat"
                        class="form-control"
                        rows="3"
                        placeholder="Masukkan alamat siswa (boleh dikosongkan)"
                    ><?= htmlspecialchars($siswa['alamat'] ?? '') ?></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Status Aktif</label>
                    <select name="status_aktif" class="form-select" required>
                        <option value="1"
                            <?= $siswa['status_aktif'] == 1 ? 'selected' : '' ?>>
                            Aktif
                        </option>

                        <option value="0"
                            <?= $siswa['status_aktif'] == 0 ? 'selected' : '' ?>>
                            Tidak Aktif
                        </option>
                    </select>
                </div>

            </div>

            <div class="d-flex justify-content-end gap-2">

                <a href="kelola_siswa.php" class="btn btn-secondary">
                    Batal
                </a>

                <button type="submit" name="update" class="btn btn-primary">
                    Simpan Perubahan
                </button>

            </div>

        </form>

    </div>

</div>

<script>
const form = document.getElementById('formSiswa');
const nis = document.getElementById('nis');
const nisn = document.getElementById('nisn');

const peringatanNis = document.getElementById('peringatanNis');
const peringatanNisn = document.getElementById('peringatanNisn');

function validasiNis() {
    if (nis.value.length > 9) {
        peringatanNis.textContent = 'NIS maksimal 9 digit!';
        return false;
    }

    if (!/^[0-9]{1,9}$/.test(nis.value)) {
        peringatanNis.textContent = 'NIS hanya boleh berisi angka!';
        return false;
    }

    peringatanNis.textContent = '';
    return true;
}

function validasiNisn() {
    if (!/^[0-9]{10}$/.test(nisn.value)) {
        peringatanNisn.textContent = 'NISN harus tepat 10 digit angka!';
        return false;
    }

    peringatanNisn.textContent = '';
    return true;
}

nis.addEventListener('input', validasiNis);
nisn.addEventListener('input', validasiNisn);

form.addEventListener('submit', function(e) {
    if (!validasiNis() || !validasiNisn()) {
        e.preventDefault();
        alert('Periksa kembali NIS dan NISN!');
    }
});
</script>

</body>
</html>
