
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

// Khusus admin
if (($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: dashboard.php');
    exit;
}

// Ambil semua data siswa
$sql = "SELECT * FROM t_siswa ORDER BY id ASC";
$query = mysqli_query($koneksi, $sql);

if (!$query) {
    die("Gagal mengambil data siswa: " . mysqli_error($koneksi));
}

// Ambil kelas aktif
$queryKelas = mysqli_query(
    $koneksi,
    "SELECT id, nama, tingkat, jurusan
     FROM t_kelas
     WHERE status_aktif = 1
     ORDER BY tingkat, jurusan, nama"
);

if (!$queryKelas) {
    die("Gagal mengambil data kelas: " . mysqli_error($koneksi));
}

// Ambil tahun ajaran aktif
$queryTahun = mysqli_query(
    $koneksi,
    "SELECT id, nama
     FROM t_tahun_ajaran
     WHERE status_aktif = 1
     ORDER BY id DESC"
);

if (!$queryTahun) {
    die("Gagal mengambil tahun ajaran: " . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Siswa</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f8f9fa;
        }

        .main-content {
            margin-left: 250px;
            padding: 25px;
            min-width: 0;
        }

        .form-tambah {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,.06);
        }

        .tabel-wrapper {
            width: 100%;
            overflow-x: auto;
            background: white;
            border-radius: 6px;
        }

        #tabelSiswa {
            width: 100%;
            margin-bottom: 0;
        }

        #tabelSiswa th {
            background: #212529;
            color: white;
            white-space: nowrap;
            cursor: pointer;
        }

        #tabelSiswa th,
        #tabelSiswa td {
            padding: 10px;
            vertical-align: middle;
        }

        .kontrol-tabel {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
            margin: 15px 0;
        }

        .kontrol-tabel input {
            max-width: 350px;
        }

        .paginasi {
            display: flex;
            gap: 5px;
            flex-wrap: wrap;
            margin: 15px 0;
        }

        @media (max-width: 767px) {
            .main-content {
                margin-left: 0;
                padding: 15px;
            }
        }
    </style>
</head>

<body>

<?php include 'includes/sidebar.php'; ?>

<main class="main-content">

    <h1 class="mb-4">Kelola Siswa</h1>

    <?php if (isset($_SESSION['pesan'])): ?>
        <div class="alert alert-info">
            <?= htmlspecialchars($_SESSION['pesan']) ?>
        </div>
        <?php unset($_SESSION['pesan']); ?>
    <?php endif; ?>

    <hr>

    <h2>Tambah Data Siswa</h2>

    <form action="proses_siswa.php" method="POST"
          class="form-tambah mb-4" id="formSiswa">

        <input type="hidden" name="aksi" value="tambah">

        <div class="row">

            <div class="form-group col-md-6 mb-3">
                <label>NIS</label>
                <input type="text" name="nis"
                       id="nis"
                       class="form-control"
                       maxlength="20"
                       pattern="[0-9]{1,9}"
                       inputmode="numeric"
                       required>

                <small id="peringatanNIS" class="text-danger">
                    NIS maksimal 9 digit dan hanya boleh menggunakan angka.
                </small>
            </div>

            <div class="form-group col-md-6 mb-3">
                <label>NISN</label>
                <input type="text" name="nisn"
                       class="form-control"
                       maxlength="10"
                       minlength="10"
                       pattern="[0-9]{10}"
                       inputmode="numeric"
                       required>
            </div>

            <div class="form-group col-md-6 mb-3">
                <label>Nama</label>
                <input type="text" name="nama"
                       class="form-control" required>
            </div>

            <div class="form-group col-md-6 mb-3">
                <label>Jenis Kelamin</label>
                <select name="jenis_kelamin"
                        class="form-control" required>
                    <option value="">-- Pilih --</option>
                    <option value="L">Laki-laki</option>
                    <option value="P">Perempuan</option>
                </select>
            </div>

            <div class="form-group col-md-6 mb-3">
                <label>Tanggal Lahir</label>
                <input type="date" name="tanggal_lahir"
                       class="form-control" required>
            </div>

            <div class="form-group col-md-6 mb-3">
                <label>Status Aktif</label>
                <select name="status_aktif"
                        class="form-control" required>
                    <option value="1">Aktif</option>
                    <option value="0">Tidak Aktif</option>
                </select>
            </div>

            <div class="form-group col-md-6 mb-3">
                <label>Kelas</label>
                <select name="kelas_id" class="form-control" required>
                    <option value="">-- Pilih Kelas --</option>
                    <?php while ($kelas = mysqli_fetch_assoc($queryKelas)): ?>
                        <option value="<?= (int)$kelas['id'] ?>">
                            <?= htmlspecialchars($kelas['nama']) ?>
                            (<?= htmlspecialchars($kelas['tingkat']) ?> -
                            <?= htmlspecialchars($kelas['jurusan']) ?>)
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group col-md-6 mb-3">
                <label>Tahun Ajaran</label>
                <select name="tahun_ajaran_id" class="form-control" required>
                    <option value="">-- Pilih Tahun Ajaran --</option>
                    <?php while ($tahun = mysqli_fetch_assoc($queryTahun)): ?>
                        <option value="<?= (int)$tahun['id'] ?>">
                            <?= htmlspecialchars($tahun['nama']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group col-12 mb-3">
                <label>Alamat</label>
                <textarea name="alamat" class="form-control"
                          rows="3" required></textarea>
            </div>

        </div>

        <button type="submit" class="btn btn-success">
            Tambah Siswa
        </button>
    </form>

    <a href="dashboard.php" class="btn btn-secondary mb-3">
        Kembali ke Dashboard
    </a>

    <hr>

    <h2>Data Siswa</h2>

    <div class="kontrol-tabel">

        <input type="search" id="cariSiswa"
               class="form-control"
               placeholder="Cari NIS, nama, atau alamat...">

        <label class="mb-0">
            Tampilkan
            <select id="jumlahSiswa" class="form-control"
                    style="display:inline-block;width:auto;">
                <option value="5">5</option>
                <option value="10" selected>10</option>
                <option value="25">25</option>
                <option value="all">Semua</option>
            </select>
            data
        </label>

    </div>

    <div class="tabel-wrapper">

        <table class="table table-bordered table-hover"
               id="tabelSiswa">

            <thead>
                <tr>
                    <th>ID</th>
                    <th>NIS</th>
                    <th>NISN</th>
                    <th>Nama</th>
                    <th>Jenis Kelamin</th>
                    <th>Tanggal Lahir</th>
                    <th>Alamat</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>

            <?php if (mysqli_num_rows($query) > 0): ?>

                <?php while ($siswa = mysqli_fetch_assoc($query)): ?>

                    <tr>
                        <td><?= (int)$siswa['id'] ?></td>

                        <td><?= htmlspecialchars($siswa['nis'] ?? '') ?></td>

                        <td><?= htmlspecialchars($siswa['nisn'] ?? '') ?></td>

                        <td><?= htmlspecialchars($siswa['nama'] ?? '') ?></td>

                        <td>
                            <?= ($siswa['jenis_kelamin'] ?? '') === 'L'
                                ? 'Laki-laki'
                                : 'Perempuan' ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($siswa['tanggal_lahir'] ?? '') ?>
                        </td>

                        <td><?= htmlspecialchars($siswa['alamat'] ?? '') ?></td>

                        <td>
                            <?php if (($siswa['status_aktif'] ?? 0) == 1): ?>
                                <span class="badge bg-success">Aktif</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Tidak Aktif</span>
                            <?php endif; ?>
                        </td>

                        <td class="text-nowrap">

                            <a href="edit_siswa.php?id=<?= (int)$siswa['id'] ?>"
                               class="btn btn-primary btn-sm">
                                Edit
                            </a>

                            <a href="hapus_siswa.php?id=<?= (int)$siswa['id'] ?>"
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('Yakin ingin menghapus data siswa ini?')">
                                Hapus
                            </a>

                        </td>
                    </tr>

                <?php endwhile; ?>

            <?php else: ?>

                <tr>
                    <td colspan="9" class="text-center">
                        Belum ada data siswa.
                    </td>
                </tr>

            <?php endif; ?>

            </tbody>

        </table>

    </div>

    <p id="infoSiswa" class="mt-3"></p>
    <div id="paginasiSiswa" class="paginasi"></div>

</main>

<script>
// Validasi NIS
const inputNIS = document.getElementById('nis');
const peringatanNIS = document.getElementById('peringatanNIS');
const formSiswa = document.getElementById('formSiswa');

function validasiNIS() {
    const nilai = inputNIS.value;

    if (nilai.length > 9) {
        peringatanNIS.textContent = 'Peringatan: NIS tidak boleh lebih dari 9 digit!';
        peringatanNIS.style.display = 'block';
        inputNIS.classList.add('is-invalid');
        inputNIS.setCustomValidity('NIS maksimal 9 digit.');
        return false;
    }

    if (nilai !== '' && !/^[0-9]+$/.test(nilai)) {
        peringatanNIS.textContent = 'NIS hanya boleh berisi angka!';
        peringatanNIS.style.display = 'block';
        inputNIS.classList.add('is-invalid');
        inputNIS.setCustomValidity('NIS hanya boleh berisi angka.');
        return false;
    }

    peringatanNIS.textContent = 'NIS maksimal 9 digit dan hanya boleh menggunakan angka.';
    inputNIS.classList.remove('is-invalid');
    inputNIS.setCustomValidity('');

    return true;
}

inputNIS.addEventListener('input', validasiNIS);

formSiswa.addEventListener('submit', function(event) {
    if (!validasiNIS()) {
        event.preventDefault();
        inputNIS.focus();
    }
});

// Tabel siswa
document.addEventListener('DOMContentLoaded', function () {

    const tabel = document.getElementById('tabelSiswa');
    const tbody = tabel.querySelector('tbody');
    const thead = tabel.querySelector('thead');

    const semuaBaris = Array.from(tbody.querySelectorAll('tr'))
        .filter(tr => tr.cells.length === 9);

    const cari = document.getElementById('cariSiswa');
    const jumlah = document.getElementById('jumlahSiswa');
    const info = document.getElementById('infoSiswa');
    const paginasi = document.getElementById('paginasiSiswa');

    let halaman = 1;
    let kolomUrut = -1;
    let arah = 1;

    function render() {

        const kata = cari.value.toLowerCase().trim();

        let hasil = semuaBaris.filter(tr =>
            tr.innerText.toLowerCase().includes(kata)
        );

        if (kolomUrut >= 0) {

            hasil.sort((a, b) => {

                const x = a.cells[kolomUrut].innerText.trim();
                const y = b.cells[kolomUrut].innerText.trim();

                return x.localeCompare(y, 'id', {
                    numeric: true,
                    sensitivity: 'base'
                }) * arah;

            });

        }

        const perHalaman = jumlah.value === 'all'
            ? Math.max(hasil.length, 1)
            : Number(jumlah.value);

        const totalHalaman = Math.max(
            1, Math.ceil(hasil.length / perHalaman)
        );

        halaman = Math.min(halaman, totalHalaman);

        const awal = (halaman - 1) * perHalaman;
        const tampil = hasil.slice(awal, awal + perHalaman);

        tbody.replaceChildren();

        if (tampil.length) {

            tampil.forEach(tr => tbody.appendChild(tr));

        } else {

            const tr = document.createElement('tr');
            const td = document.createElement('td');

            td.colSpan = 9;
            td.className = 'text-center';
            td.textContent = 'Data siswa tidak ditemukan.';

            tr.appendChild(td);
            tbody.appendChild(tr);

        }

        info.textContent = hasil.length
            ? `Menampilkan ${awal + 1}–${Math.min(
                awal + tampil.length, hasil.length
              )} dari ${hasil.length} siswa`
            : 'Tidak ada data siswa yang cocok.';

        paginasi.replaceChildren();

        function buatTombol(teks, tujuan, nonaktif, aktif) {

            const btn = document.createElement('button');

            btn.type = 'button';
            btn.textContent = teks;

            btn.className = aktif
                ? 'btn btn-primary btn-sm'
                : 'btn btn-outline-primary btn-sm';

            btn.disabled = nonaktif;

            btn.addEventListener('click', function () {
                halaman = tujuan;
                render();
            });

            paginasi.appendChild(btn);

        }

        buatTombol('‹', halaman - 1, halaman === 1, false);

        for (let i = 1; i <= totalHalaman; i++) {
            buatTombol(String(i), i, false, i === halaman);
        }

        buatTombol('›', halaman + 1, halaman === totalHalaman, false);

    }

    cari.addEventListener('input', function () {
        halaman = 1;
        render();
    });

    jumlah.addEventListener('change', function () {
        halaman = 1;
        render();
    });

    thead.querySelectorAll('th').forEach(function (th, index) {

        th.title = 'Klik untuk mengurutkan';

        th.addEventListener('click', function () {

            if (kolomUrut === index) {
                arah *= -1;
            } else {
                kolomUrut = index;
                arah = 1;
            }

            halaman = 1;
            render();

        });

    });

    render();

});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
