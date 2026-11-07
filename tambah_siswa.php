
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

// Khusus admin
if (($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: dashboard.php');
    exit;
}

// Ambil kelas aktif
$queryKelas = mysqli_query($koneksi, "
    SELECT id, nama, tingkat, jurusan
    FROM t_kelas
    WHERE status_aktif = 1
    ORDER BY tingkat ASC, jurusan ASC, nama ASC
");

// Ambil tahun ajaran aktif
$queryTahun = mysqli_query($koneksi, "
    SELECT id, nama
    FROM t_tahun_ajaran
    WHERE status_aktif = 1
    ORDER BY id DESC
");

$jumlahKelas = mysqli_num_rows($queryKelas);
$jumlahTahun = mysqli_num_rows($queryTahun);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Siswa</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f8f9fa;
            font-family: Arial, sans-serif;
        }

        .main-content {
            margin-left: 250px;
            padding: 25px;
        }

        .form-card {
            background: #fff;
            border-radius: 12px;
            padding: 28px;
            box-shadow: 0 3px 15px rgba(0,0,0,0.06);
            max-width: 850px;
            margin: auto;
        }

        .page-title {
            font-weight: 700;
            color: #212529;
            margin-bottom: 5px;
        }

        .page-description {
            color: #6c757d;
            margin-bottom: 25px;
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

        .form-section {
            border-bottom: 1px solid #eee;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }

        .section-title {
            font-size: 17px;
            font-weight: 700;
            margin-bottom: 18px;
            color: #343a40;
        }

        .btn {
            padding: 9px 18px;
            border-radius: 7px;
            font-weight: 600;
        }

        .peringatan {
            color: #dc3545;
            font-size: 12px;
            margin-top: 5px;
            display: none;
        }

        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                padding: 15px;
            }

            .form-card {
                padding: 20px;
            }
        }
    </style>
</head>

<body>

<?php include 'includes/sidebar.php'; ?>

<div class="main-content">
    <div class="form-card">

        <h3 class="page-title">Tambah Data Siswa</h3>
        <p class="page-description">
            Masukkan informasi identitas dan penempatan siswa.
        </p>

        <form action="proses_siswa.php" method="POST" id="formSiswa">

            <input type="hidden" name="aksi" value="tambah">

            <div class="form-section">
                <h5 class="section-title">Informasi Identitas</h5>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">NIS</label>
                        <input type="text" name="nis" id="nis"
                            class="form-control" maxlength="15"
                            inputmode="numeric" placeholder="Masukkan NIS" required>
                        <div class="peringatan" id="peringatanNIS">
                            NIS harus berupa angka dan maksimal 15 digit.
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">NISN</label>
                        <input type="text" name="nisn" id="nisn"
                            class="form-control" maxlength="10"
                            minlength="10" inputmode="numeric"
                            placeholder="Masukkan 10 digit NISN" required>
                        <div class="peringatan" id="peringatanNISN">
                            NISN harus tepat 10 digit angka.
                        </div>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control"
                            placeholder="Masukkan nama lengkap siswa" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="form-select" required>
                            <option value="">-- Pilih Jenis Kelamin --</option>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir"
                            class="form-control" required>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <h5 class="section-title">Informasi Tambahan</h5>

                <div class="mb-3">
                    <label class="form-label">Alamat</label>
                    <textarea name="alamat" class="form-control"
                        rows="3" placeholder="Masukkan alamat siswa"></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Status Siswa</label>
                    <select name="status_aktif" class="form-select" required>
                        <option value="1" selected>Aktif</option>
                        <option value="0">Tidak Aktif</option>
                    </select>
                </div>
            </div>

            <div class="form-section">
                <h5 class="section-title">Informasi Penempatan</h5>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Kelas</label>
                        <select name="kelas_id" class="form-select" required>
                            <option value="">-- Pilih Kelas --</option>

                            <?php while ($kelas = mysqli_fetch_assoc($queryKelas)): ?>
                                <option value="<?= $kelas['id']; ?>">
                                    <?= htmlspecialchars(
                                        $kelas['tingkat'] . ' - ' .
                                        $kelas['nama'] . ' - ' .
                                        $kelas['jurusan']
                                    ); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>

                        <?php if ($jumlahKelas === 0): ?>
                            <small class="text-danger">
                                Belum ada kelas aktif.
                            </small>
                        <?php endif; ?>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tahun Ajaran</label>
                        <select name="tahun_ajaran_id" class="form-select" required>
                            <option value="">-- Pilih Tahun Ajaran --</option>

                            <?php while ($tahun = mysqli_fetch_assoc($queryTahun)): ?>
                                <option value="<?= $tahun['id']; ?>">
                                    <?= htmlspecialchars($tahun['nama']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>

                        <?php if ($jumlahTahun === 0): ?>
                            <small class="text-danger">
                                Belum ada tahun ajaran aktif.
                            </small>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="kelola_siswa.php" class="btn btn-secondary">
                    Batal
                </a>

                <button type="submit" class="btn btn-primary"
                    <?= ($jumlahKelas === 0 || $jumlahTahun === 0) ? 'disabled' : ''; ?>>
                    Tambah Siswa
                </button>
            </div>

        </form>
    </div>
</div>

<script>
const formSiswa = document.getElementById('formSiswa');
const nis = document.getElementById('nis');
const nisn = document.getElementById('nisn');

function validasiNIS() {
    const valid = /^[0-9]{1,15}$/.test(nis.value);

    document.getElementById('peringatanNIS').style.display =
        valid || nis.value === '' ? 'none' : 'block';

    return valid;
}

function validasiNISN() {
    const valid = /^[0-9]{10}$/.test(nisn.value);

    document.getElementById('peringatanNISN').style.display =
        valid || nisn.value === '' ? 'none' : 'block';

    return valid;
}

nis.addEventListener('input', function () {
    this.value = this.value.replace(/\D/g, '').slice(0, 15);
    validasiNIS();
});

nisn.addEventListener('input', function () {
    this.value = this.value.replace(/\D/g, '').slice(0, 10);
    validasiNISN();
});

formSiswa.addEventListener('submit', function (e) {
    if (!validasiNIS() || !validasiNISN()) {
        e.preventDefault();
        alert('Periksa kembali NIS dan NISN.');
    }
});
</script>

</body>
</html>
