
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

// Halaman ini khusus admin
$role = $_SESSION['role'] ?? '';

if ($role === 'admin') {
    $menu = $menu_admin;
} elseif ($role === 'guru') {
    $menu = $menu_guru;
} elseif ($role === 'wali_kelas') {
    $menu = $menu_guru;
} else {
    $menu = [];
}
// Ambil data pelanggaran + kategori
$sql = "SELECT
            t_pelanggaran.*,
            t_pelanggaran_kategori.nama AS nama_kategori
        FROM t_pelanggaran
        LEFT JOIN t_pelanggaran_kategori
            ON t_pelanggaran.pelanggaran_kategori_id = t_pelanggaran_kategori.id
        ORDER BY t_pelanggaran.id DESC";

$query = mysqli_query($koneksi, $sql);

if (!$query) {
    die('Query gagal: ' . mysqli_error($koneksi));
}

// Ambil data kategori
$sql_kategori = "SELECT * FROM t_pelanggaran_kategori
                 ORDER BY nama ASC";

$query_kategori = mysqli_query($koneksi, $sql_kategori);

if (!$query_kategori) {
    die('Query kategori gagal: ' . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pelanggaran</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f8f9fa;
            margin: 0;
            font-family: Arial, sans-serif;
            color: #212529;
        }

        .main-content {
            margin-left: 250px;
            padding: 25px;
            min-width: 0;
        }

        .panel-konten {
            background: white;
            padding: 20px;
            border-radius: 5px;
            border: 1px solid #dee2e6;
        }

        .panel-konten h1 {
            font-size: 28px;
            margin-bottom: 8px;
        }

        .panel-konten h2 {
            font-size: 22px;
            margin-top: 22px;
            margin-bottom: 15px;
        }

        .deskripsi {
            color: #6c757d;
            margin-bottom: 15px;
        }

        .pesan {
            padding: 10px 14px;
            border: 1px solid #b8daff;
            background: #cce5ff;
            border-radius: 4px;
            margin: 15px 0;
        }

        .form-pelanggaran {
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 18px;
            margin-bottom: 20px;
        }

        .form-pelanggaran table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 12px;
        }

        .form-pelanggaran td:first-child {
            width: 180px;
            font-weight: 500;
        }

        .form-pelanggaran td:nth-child(2) {
            width: 20px;
        }

        .form-pelanggaran input,
        .form-pelanggaran select,
        .form-pelanggaran textarea {
            width: 100%;
            max-width: 650px;
            padding: 8px 10px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .form-pelanggaran button {
            background: #007bff;
            color: white;
            border: 0;
            border-radius: 4px;
            padding: 8px 13px;
            cursor: pointer;
        }

        .form-pelanggaran button:hover {
            background: #0069d9;
        }

        .toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            margin: 20px 0;
        }

        .toolbar input {
            max-width: 630px;
        }

        .jumlah-data {
            display: flex;
            align-items: center;
            gap: 8px;
            white-space: nowrap;
        }

        .jumlah-data select {
            width: 80px;
        }

        .table-responsive {
            width: 100%;
        }

        .table {
            margin-bottom: 15px;
            font-size: 14px;
        }

        .table thead th {
            background: #212529;
            color: white;
            border-color: #343a40;
            padding: 13px 10px;
            vertical-align: middle;
        }

        .table tbody td {
            padding: 10px;
            vertical-align: middle;
        }

        .pagination-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }

        .pagination {
            margin-bottom: 0;
            flex-wrap: wrap;
        }

        .page-link {
            cursor: pointer;
        }

        @media print {
            .sidebar,
            .toolbar,
            .pagination-container,
            .tombol-aksi,
            .form-pelanggaran,
            .pesan {
                display: none !important;
            }

            .main-content {
                margin: 0;
                padding: 0;
                width: 100%;
            }

            .panel-konten {
                border: none;
            }
        }

        @media (max-width: 767px) {
            .main-content {
                margin-left: 0;
                padding: 15px;
            }

            .toolbar {
                flex-direction: column;
                align-items: stretch;
            }

            .toolbar input {
                max-width: none;
            }

            .jumlah-data {
                justify-content: flex-start;
            }

            .form-pelanggaran td:first-child {
                width: 100px;
            }

            .panel-konten {
                padding: 15px;
            }
        }
    </style>
</head>

<body>

<?php include 'includes/sidebar.php'; ?>

<main class="main-content">

    <div class="panel-konten">

        <h1>
            Selamat datang, <?= htmlspecialchars($_SESSION['name'] ?? '') ?>
        </h1>

        <p>
            Anda login sebagai:
            <?= htmlspecialchars($role) ?>
        </p>

        <h1>Kelola Pelanggaran</h1>

        <?php if (isset($_SESSION['pesan'])): ?>
            <div class="pesan">
                <?= htmlspecialchars($_SESSION['pesan']) ?>
            </div>
            <?php unset($_SESSION['pesan']); ?>
        <?php endif; ?>

        <hr>

        <h2>Tambah Pelanggaran</h2>

        <div class="form-pelanggaran">

            <form action="proses_pelanggaran.php" method="POST">

                <input type="hidden" name="aksi" value="tambah">

                <table>

                    <tr>
                        <td>Kategori</td>
                        <td>:</td>
                        <td>
                            <select name="pelanggaran_kategori_id" required>
                                <option value="">-- Pilih Kategori --</option>

                                <?php while ($kategori = mysqli_fetch_assoc($query_kategori)): ?>
                                    <option value="<?= (int) $kategori['id'] ?>">
                                        <?= htmlspecialchars($kategori['nama']) ?>
                                    </option>
                                <?php endwhile; ?>

                            </select>
                        </td>
                    </tr>

                    <tr>
                        <td>Kode</td>
                        <td>:</td>
                        <td>
                            <input
                                type="text"
                                name="kode"
                                placeholder="PG-001"
                                required
                            >
                        </td>
                    </tr>

                    <tr>
                        <td>Nama Pelanggaran</td>
                        <td>:</td>
                        <td>
                            <input type="text" name="nama" required>
                        </td>
                    </tr>

                    <tr>
                        <td>Poin</td>
                        <td>:</td>
                        <td>
                            <input type="number" name="poin" min="0" required>
                        </td>
                    </tr>

                    <tr>
                        <td>Deskripsi</td>
                        <td>:</td>
                        <td>
                            <textarea name="deskripsi" rows="4" required></textarea>
                        </td>
                    </tr>

                    <tr>
                        <td>Status Aktif</td>
                        <td>:</td>
                        <td>
                            <select name="status_aktif" required>
                                <option value="1">Aktif</option>
                                <option value="0">Tidak Aktif</option>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <td colspan="3">
                            <button type="submit">Tambah Pelanggaran</button>
                        </td>
                    </tr>

                </table>

            </form>

        </div>

        <hr>

        <div class="tombol-aksi">
            <a href="dashboard.php" class="btn btn-secondary btn-sm">
                Kembali ke Dashboard
            </a>
        </div>

        <h2>Data Pelanggaran</h2>

        <div class="toolbar">

            <input
                type="text"
                id="cariTabel"
                class="form-control"
                placeholder="Cari nama kategori atau deskripsi..."
            >

            <div class="jumlah-data">
                <label for="jumlahData" class="mb-0">Tampilkan:</label>

                <select id="jumlahData" class="form-select form-select-sm">
                    <option value="10" selected>10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>

                <span>data</span>
            </div>

        </div>

        <div class="table-responsive">

            <table id="tabelPelanggaran" class="table table-bordered table-striped">

                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kategori</th>
                        <th>Kode</th>
                        <th>Nama Pelanggaran</th>
                        <th>Poin</th>
                        <th>Deskripsi</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    <?php
                    $no = 1;

                    if (mysqli_num_rows($query) > 0):
                        while ($pelanggaran = mysqli_fetch_assoc($query)):
                    ?>

                        <tr>

                            <td><?= $no++ ?></td>

                            <td>
                                <?= htmlspecialchars($pelanggaran['nama_kategori'] ?? '-') ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($pelanggaran['kode'] ?? '-') ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($pelanggaran['nama'] ?? '-') ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($pelanggaran['poin'] ?? '0') ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($pelanggaran['deskripsi'] ?? '-') ?>
                            </td>

                            <td>
                                <?php if ($pelanggaran['status_aktif'] == 1): ?>
                                    <span class="badge bg-success">Aktif</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Tidak Aktif</span>
                                <?php endif; ?>
                            </td>

                            <td>
                                <a
                                    href="edit_pelanggaran.php?id=<?= (int) $pelanggaran['id'] ?>"
                                    class="btn btn-primary btn-sm"
                                >
                                    Edit
                                </a>

                                <a
                                    href="proses_pelanggaran.php?aksi=hapus&id=<?= (int) $pelanggaran['id'] ?>"
                                    class="btn btn-danger btn-sm"
                                    onclick="return confirm('Yakin ingin menghapus pelanggaran ini?');"
                                >
                                    Hapus
                                </a>
                            </td>

                        </tr>

                    <?php
                        endwhile;
                    else:
                    ?>

                        <tr>
                            <td colspan="8" class="text-center">
                                Belum ada data pelanggaran.
                            </td>
                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

        <div class="pagination-container">
            <small id="infoTabel"></small>
            <ul class="pagination" id="pagination"></ul>
        </div>

    </div>

</main>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const tabel = document.getElementById('tabelPelanggaran');
    const pencarian = document.getElementById('cariTabel');
    const jumlahData = document.getElementById('jumlahData');
    const info = document.getElementById('infoTabel');
    const pagination = document.getElementById('pagination');

    const semuaBaris = Array.from(tabel.querySelectorAll('tbody tr'));

    const punyaData = semuaBaris.length > 0 &&
        !(semuaBaris.length === 1 && semuaBaris[0].cells.length === 1);

    let halaman = 1;

    function tampilkanTabel() {

        const kata = pencarian.value.toLowerCase();

        const barisCocok = punyaData
            ? semuaBaris.filter(function (baris) {
                return baris.innerText.toLowerCase().includes(kata);
            })
            : [];

        const perHalaman = parseInt(jumlahData.value, 10);
        const totalData = barisCocok.length;

        const totalHalaman = Math.max(
            1,
            Math.ceil(totalData / perHalaman)
        );

        if (halaman > totalHalaman) {
            halaman = totalHalaman;
        }

        semuaBaris.forEach(function (baris) {
            baris.style.display = 'none';
        });

        const mulai = (halaman - 1) * perHalaman;
        const akhir = Math.min(mulai + perHalaman, totalData);

        barisCocok.slice(mulai, akhir).forEach(function (baris, indeks) {
            baris.style.display = '';
            baris.cells[0].textContent = mulai + indeks + 1;
        });

        if (totalData === 0) {

            info.textContent = punyaData
                ? 'Tidak ada data yang cocok.'
                : 'Belum ada data pelanggaran.';

        } else {

            info.textContent =
                'Menampilkan ' + (mulai + 1) + '–' + akhir +
                ' dari ' + totalData + ' data pelanggaran';

        }

        pagination.innerHTML = '';

        function buatTombol(teks, nomor, nonaktif, aktif) {

            const li = document.createElement('li');

            li.className = 'page-item' +
                (nonaktif ? ' disabled' : '') +
                (aktif ? ' active' : '');

            const tombol = document.createElement('button');

            tombol.type = 'button';
            tombol.className = 'page-link';
            tombol.textContent = teks;
            tombol.disabled = nonaktif;

            tombol.addEventListener('click', function () {
                halaman = nomor;
                tampilkanTabel();
            });

            li.appendChild(tombol);
            pagination.appendChild(li);

        }

        buatTombol(
            'Sebelumnya',
            halaman - 1,
            halaman === 1,
            false
        );

        for (let i = 1; i <= totalHalaman; i++) {

            if (
                totalHalaman <= 7 ||
                i === 1 ||
                i === totalHalaman ||
                Math.abs(i - halaman) <= 1
            ) {

                buatTombol(
                    String(i),
                    i,
                    false,
                    i === halaman
                );

            }

        }

        buatTombol(
            'Berikutnya',
            halaman + 1,
            halaman >= totalHalaman,
            false
        );

    }

    pencarian.addEventListener('input', function () {
        halaman = 1;
        tampilkanTabel();
    });

    jumlahData.addEventListener('change', function () {
        halaman = 1;
        tampilkanTabel();
    });

    tampilkanTabel();

});
</script>

</body>
</html>
