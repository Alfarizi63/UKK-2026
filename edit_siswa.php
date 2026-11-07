<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

// Khusus admin
if (($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: dashboard.php');
    exit;
}

// Cek ID siswa
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID siswa tidak ditemukan!");
}

$id = (int) $_GET['id'];

// Ambil data siswa
$stmt = $koneksi->prepare("SELECT * FROM t_siswa WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$query = $stmt->get_result();
$siswa = $query->fetch_assoc();
$stmt->close();

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
            color: #343a40;
        }

        /* Mengikuti sidebar utama dari includes/sidebar.php */
        .main-content {
            margin-left: 250px;
            padding: 30px;
            min-width: 0;
            box-sizing: border-box;
        }

        .page-header {
            margin-bottom: 24px;
        }

        .page-title {
            font-size: 25px;
            font-weight: 700;
            margin-bottom: 6px;
            color: #212529;
        }

        .page-description {
            color: #6c757d;
            font-size: 14px;
            margin: 0;
        }

        .form-card {
            width: 100%;
            max-width: 900px;
            background: #fff;
            border: 1px solid #e9ecef;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 3px 12px rgba(0, 0, 0, .04);
        }

        .form-section {
            padding-bottom: 24px;
            margin-bottom: 24px;
            border-bottom: 1px solid #eee;
        }

        .form-section:last-of-type {
            border-bottom: 0;
            padding-bottom: 0;
            margin-bottom: 24px;
        }

        .section-title {
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 7px;
            color: #343a40;
        }

        .section-description {
            font-size: 13px;
            color: #6c757d;
            margin-bottom: 20px;
        }

        .form-label {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .form-control,
        .form-select {
            min-height: 44px;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            font-size: 14px;
        }

        textarea.form-control {
            min-height: 100px;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 3px rgba(0, 123, 255, .10);
        }

        .form-text {
            font-size: 12px;
        }

        .required {
            color: #dc3545;
        }

        .btn {
            border-radius: 8px;
            padding: 10px 18px;
            font-size: 14px;
            font-weight: 600;
        }

        .btn-primary {
            background: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background: #0069d9;
            border-color: #0062cc;
        }

        @media (max-width: 767px) {
            .main-content {
                margin-left: 0;
                padding: 18px;
            }

            .form-card {
                padding: 20px;
            }

            .page-title {
                font-size: 22px;
            }
        }
    </style>
</head>
<body>

<?php include 'includes/sidebar.php'; ?>

<main class="main-content">
    <div class="page-header">
        <h3 class="page-title">Edit Data Siswa</h3>
        <p class="page-description">
            Perbarui informasi identitas dan data siswa.
        </p>
    </div>

    <div class="form-card">
        <form action="proses_edit_siswa.php" method="POST" id="formSiswa">
            <input type="hidden" name="id" value="<?= htmlspecialchars((string) $siswa['id']) ?>">

            <section class="form-section">
                <h5 class="section-title">Informasi Identitas</h5>
                <p class="section-description">Lengkapi informasi identitas siswa dengan benar.</p>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="nis" class="form-label">NIS <span class="required">*</span></label>
                        <input
                            type="text"
                            name="nis"
                            id="nis"
                            class="form-control"
                            value="<?= htmlspecialchars($siswa['nis'] ?? '') ?>"
                            maxlength="15"
                            pattern="[0-9]{1,15}"
                            inputmode="numeric"
                            autocomplete="off"
                            required
                        >
                        <div class="form-text">Maksimal 15 digit angka.</div>
                        <div id="peringatanNis" class="text-danger small"></div>
                    </div>

                    <div class="col-md-6">
                        <label for="nisn" class="form-label">NISN <span class="required">*</span></label>
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
                            autocomplete="off"
                            required
                        >
                        <div class="form-text">Harus tepat 10 digit angka.</div>
                        <div id="peringatanNisn" class="text-danger small"></div>
                    </div>

                    <div class="col-md-12">
                        <label for="nama" class="form-label">Nama Siswa <span class="required">*</span></label>
                        <input
                            type="text"
                            name="nama"
                            id="nama"
                            class="form-control"
                            value="<?= htmlspecialchars($siswa['nama'] ?? '') ?>"
                            placeholder="Masukkan nama lengkap siswa"
                            required
                        >
                    </div>

                    <div class="col-md-6">
                        <label for="jenis_kelamin" class="form-label">Jenis Kelamin <span class="required">*</span></label>
                        <select name="jenis_kelamin" id="jenis_kelamin" class="form-select" required>
                            <option value="L" <?= ($siswa['jenis_kelamin'] ?? '') === 'L' ? 'selected' : '' ?>>
                                Laki-laki
                            </option>
                            <option value="P" <?= ($siswa['jenis_kelamin'] ?? '') === 'P' ? 'selected' : '' ?>>
                                Perempuan
                            </option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="tanggal_lahir" class="form-label">Tanggal Lahir <span class="required">*</span></label>
                        <input
                            type="date"
                            name="tanggal_lahir"
                            id="tanggal_lahir"
                            class="form-control"
                            value="<?= htmlspecialchars($siswa['tanggal_lahir'] ?? '') ?>"
                            required
                        >
                    </div>
                </div>
            </section>

            <section class="form-section">
                <h5 class="section-title">Informasi Tambahan</h5>
                <p class="section-description">Informasi alamat dan status siswa.</p>

                <div class="mb-3">
                    <label for="alamat" class="form-label">Alamat</label>
                    <textarea
                        name="alamat"
                        id="alamat"
                        class="form-control"
                        rows="3"
                        placeholder="Masukkan alamat siswa"
                    ><?= htmlspecialchars($siswa['alamat'] ?? '') ?></textarea>
                </div>

                <div class="mb-3">
                    <label for="status_aktif" class="form-label">Status Aktif <span class="required">*</span></label>
                    <select name="status_aktif" id="status_aktif" class="form-select" required>
                        <option value="1" <?= (string) ($siswa['status_aktif'] ?? '') === '1' ? 'selected' : '' ?>>
                            Aktif
                        </option>
                        <option value="0" <?= (string) ($siswa['status_aktif'] ?? '') === '0' ? 'selected' : '' ?>>
                            Tidak Aktif
                        </option>
                    </select>
                </div>
            </section>

            <div class="d-flex justify-content-end gap-2 flex-wrap">
                <a href="kelola_siswa.php" class="btn btn-light border">Batal</a>
                <button type="submit" name="update" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</main>

<script>
const form = document.getElementById('formSiswa');
const nis = document.getElementById('nis');
const nisn = document.getElementById('nisn');
const peringatanNis = document.getElementById('peringatanNis');
const peringatanNisn = document.getElementById('peringatanNisn');

function validasiNis() {
    // Hanya angka, maksimal 15 digit.
    nis.value = nis.value.replace(/\D/g, '').slice(0, 15);

    if (!/^[0-9]{1,15}$/.test(nis.value)) {
        peringatanNis.textContent = 'NIS wajib diisi dengan maksimal 15 digit angka.';
        return false;
    }

    peringatanNis.textContent = '';
    return true;
}

function validasiNisn() {
    // Hanya angka, tepat 10 digit.
    nisn.value = nisn.value.replace(/\D/g, '').slice(0, 10);

    if (!/^[0-9]{10}$/.test(nisn.value)) {
        peringatanNisn.textContent = 'NISN harus tepat 10 digit angka.';
        return false;
    }

    peringatanNisn.textContent = '';
    return true;
}

nis.addEventListener('input', validasiNis);
nisn.addEventListener('input', validasiNisn);

form.addEventListener('submit', function (e) {
    if (!validasiNis() || !validasiNisn()) {
        e.preventDefault();
        alert('Periksa kembali NIS dan NISN!');
    }
});
</script>

</body>
</html>
