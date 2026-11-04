
<?php
include 'includes/cek_session.php';

$role = $_SESSION['role'] ?? '';

if (!in_array($role, ['admin', 'guru', 'wali_kelas'], true)) {
    header('Location: dashboard.php');
    exit;
}

include 'config/koneksi.php';

$sql = "
    SELECT
        s.id,
        s.nis,
        s.nama,
        COUNT(ps.id) AS jumlah_pelanggaran,
        COALESCE(SUM(ps.poin), 0) AS total_poin
    FROM t_siswa s
    LEFT JOIN t_pelanggaran_siswa ps
        ON ps.siswa_id = s.id
    GROUP BY s.id, s.nis, s.nama
    ORDER BY total_poin DESC, s.nama ASC
";

$query = mysqli_query($koneksi, $sql);

if (!$query) {
    die('Query gagal: ' . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Poin Pelanggaran Siswa</title>

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
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 1px 5px rgba(0, 0, 0, 0.08);
        }

        .panel-konten h2 {
            font-size: 28px;
            margin-bottom: 8px;
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

        .table tbody tr:nth-child(odd) {
            background: #ededed;
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
            .tombol-cetak {
                display: none !important;
            }

            .main-content {
                margin: 0;
                padding: 0;
                width: 100%;
            }

            .panel-konten {
                box-shadow: none;
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

        <?php if ($role === 'guru' || $role === 'wali_kelas') { ?>
            <h4>
                Selamat datang, <?= htmlspecialchars($_SESSION['name'] ?? '') ?>
            </h4>
            <p>Anda login sebagai: <?= htmlspecialchars($role) ?></p>
        <?php } ?>

        <h2>Rekap Poin Pelanggaran Siswa</h2>

        <div class="tombol-aksi">
            <a href="dashboard.php" class="btn btn-secondary btn-sm">
                Kembali ke Dashboard
            </a>

            <button type="button" onclick="window.print()"
                    class="btn btn-primary btn-sm tombol-cetak">
                Cetak Laporan
            </button>
        </div>

        <div class="toolbar">
            <input
                type="text"
                id="cariTabel"
                class="form-control"
                placeholder="Cari NIS atau nama siswa..."
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
            <table id="tabelRekap" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIS</th>
                        <th>Nama Siswa</th>
                        <th>Jumlah Pelanggaran</th>
                        <th>Total Poin</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    $no = 1;

                    while ($data = mysqli_fetch_assoc($query)) {
                    ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= htmlspecialchars($data['nis'] ?? ''); ?></td>
                        <td><?= htmlspecialchars($data['nama'] ?? ''); ?></td>
                        <td><?= (int) $data['jumlah_pelanggaran']; ?></td>
                        <td><?= (int) $data['total_poin']; ?></td>
                    </tr>
                    <?php } ?>
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
    const tabel = document.getElementById('tabelRekap');
    const pencarian = document.getElementById('cariTabel');
    const jumlahData = document.getElementById('jumlahData');
    const info = document.getElementById('infoTabel');
    const pagination = document.getElementById('pagination');

    const semuaBaris = Array.from(tabel.querySelectorAll('tbody tr'));

    let halaman = 1;

    function tampilkanTabel() {
        const kata = pencarian.value.toLowerCase();

        const barisCocok = semuaBaris.filter(function (baris) {
            return baris.innerText.toLowerCase().includes(kata);
        });

        const perHalaman = parseInt(jumlahData.value, 10);
        const totalData = barisCocok.length;
        const totalHalaman = Math.max(1, Math.ceil(totalData / perHalaman));

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
            info.textContent = 'Tidak ada data yang cocok.';
        } else {
            info.textContent =
                'Menampilkan ' + (mulai + 1) + '–' + akhir +
                ' dari ' + totalData + ' data siswa';
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

        buatTombol('Sebelumnya', halaman - 1, halaman === 1, false);

        for (let i = 1; i <= totalHalaman; i++) {
            if (
                totalHalaman <= 7 ||
                i === 1 ||
                i === totalHalaman ||
                Math.abs(i - halaman) <= 1
            ) {
                buatTombol(String(i), i, false, i === halaman);
            }
        }

        buatTombol('Berikutnya', halaman + 1, halaman >= totalHalaman, false);
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
