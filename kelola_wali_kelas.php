
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

$sql = "
    SELECT
        wk.id,
        ta.nama AS tahun_ajaran,
        k.nama AS nama_kelas,
        g.nama AS nama_guru,
        wk.tanggal_mulai,
        wk.tanggal_selesai,
        wk.status_aktif
    FROM t_wali_kelas wk
    LEFT JOIN t_tahun_ajaran ta
        ON wk.tahun_ajaran_id = ta.id
    LEFT JOIN t_kelas k
        ON wk.kelas_id = k.id
    LEFT JOIN t_guru g
        ON wk.guru_id = g.id
    ORDER BY wk.id DESC
";

$query = mysqli_query($koneksi, $sql);

if (!$query) {
    die("Gagal mengambil data wali kelas: " . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Wali Kelas</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">

    <style>
        body {
            background: #f8f9fa;
            margin: 0;
            font-family: Arial, sans-serif;
            color: #212529;
        }

        .sidebar {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            width: 273px;
            background: #343a40;
            padding: 14px;
            overflow-y: auto;
            z-index: 1000;
        }

        .sidebar h4 {
            color: white;
            font-weight: bold;
            margin: 0 0 20px;
            padding-top: 0;
        }

        .sidebar a {
            color: white;
            display: block;
            padding: 10px 14px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 14px;
        }

        .sidebar a:hover {
            background: #495057;
        }

        .sidebar a.active {
            background: #007bff;
            color: white;
        }

        .sidebar .nav-item {
            margin-bottom: 2px !important;
        }

        .konten {
            margin-left: 273px;
            padding: 20px 38px;
            min-width: 0;
            width: calc(100% - 273px);
        }

        .panel-konten {
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 1px 5px rgba(0,0,0,0.08);
        }

        .panel-konten h2 {
            font-size: 28px;
            margin-bottom: 6px;
        }

        .deskripsi {
            color: #6c757d;
            margin-bottom: 12px;
        }

        .toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
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
            background: white;
            font-size: 14px;
        }

        .table thead th {
            background: #212529;
            color: white;
            border-color: #343a40;
            vertical-align: middle;
            padding: 13px 10px;
        }

        .table tbody td {
            padding: 10px;
            vertical-align: middle;
        }

        .table tbody tr:nth-child(odd) {
            background: #ededed;
        }

        .table tbody tr:nth-child(even) {
            background: #fff;
        }

        .badge-status {
            display: inline-block;
            padding: 2px 5px;
            border-radius: 3px;
            font-size: 11px;
            font-weight: bold;
            color: white;
        }

        .status-aktif {
            background: #28a745;
        }

        .status-nonaktif {
            background: #dc3545;
        }

        .btn-sm {
            font-size: 12px;
            padding: 5px 8px;
        }

        .pagination-container {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }

        .pagination {
            margin-bottom: 0;
            flex-wrap: wrap;
        }

        .page-link {
            cursor: pointer;
        }

        @media (max-width: 767px) {
            .sidebar {
                position: relative;
                width: 100%;
                height: auto;
                bottom: auto;
            }

            .konten {
                margin-left: 0;
                width: 100%;
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

            .panel-konten {
                padding: 15px;
            }
        }
    </style>
</head>

<body>

<div class="sidebar">
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
                kelola kategori pelanggaran
            </a>
        </li>

        <li class="nav-item mb-2">
            <a href="kelola_tahun_ajaran.php" class="nav-link">
                Kelola tahun ajaran
            </a>
        </li>

        <li class="nav-item mb-2">
            <a href="laporan.php" class="nav-link">Laporan</a>
        </li>

        <li class="nav-item mb-2">
            <a href="kelola_wali_kelas.php" class="nav-link active">
                kelola wali kelas
            </a>
        </li>

        <li class="nav-item mb-2">
            <a href="kelola_penempatan_siswa.php" class="nav-link">
                kelola penempatan siswa
            </a>
        </li>

        <li class="nav-item mb-2">
            <a href="rekap_point.php" class="nav-link">rekap poin</a>
        </li>

        <li class="nav-item mb-2">
            <a href="riwayat.php" class="nav-link">riwayat</a>
        </li>

        <li class="nav-item mb-2">
            <a href="kelola_jenis_pelanggaran.php" class="nav-link">
                kelola jenis pelanggaran
            </a>
        </li>

        <li class="nav-item mb-2">
            <a href="export_pelanggaran.php" class="nav-link">
                export pelanggaran
            </a>
        </li>

        <li class="nav-item mb-2">
            <a href="tentang_saya.php" class="nav-link">About Me</a>
        </li>

        <hr class="text-secondary">

        <li class="nav-item">
            <a href="logout.php" class="nav-link text-danger">Logout</a>
        </li>
    </ul>
</div>

<main class="konten">
    <div class="panel-konten">

        <h2>Kelola Wali Kelas</h2>
        <p class="deskripsi">Halaman pengelolaan data wali kelas sekolah.</p>

        <a href="dashboard.php" class="btn btn-secondary btn-sm">Kembali ke Dashboard</a>
        <a href="tambah_wali_kelas.php" class="btn btn-primary btn-sm">+ Tambah Wali Kelas</a>

        <div class="toolbar">
            <input
                type="text"
                id="cariTabel"
                class="form-control"
                placeholder="Cari data wali kelas..."
            >

            <div class="jumlah-data">
                <label for="jumlahData" class="mb-0">Tampilkan:</label>
                <select id="jumlahData" class="form-control form-control-sm">
                    <option value="10" selected>10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <span>data</span>
            </div>
        </div>

        <div class="table-responsive">
            <table id="tabelWaliKelas" class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tahun Ajaran</th>
                        <th>Kelas</th>
                        <th>Nama Guru</th>
                        <th>Tanggal Mulai</th>
                        <th>Tanggal Selesai</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    $no = 1;

                    if (mysqli_num_rows($query) > 0) {
                        while ($row = mysqli_fetch_assoc($query)) {
                    ?>
                        <tr>
                            <td><?= $no++ ?></td>

                            <td><?= htmlspecialchars($row['tahun_ajaran'] ?? '-') ?></td>

                            <td><?= htmlspecialchars($row['nama_kelas'] ?? '-') ?></td>

                            <td><?= htmlspecialchars($row['nama_guru'] ?? '-') ?></td>

                            <td><?= htmlspecialchars($row['tanggal_mulai'] ?? '-') ?></td>

                            <td><?= htmlspecialchars($row['tanggal_selesai'] ?? '-') ?></td>

                            <td>
                                <?php if ($row['status_aktif'] == 1) { ?>
                                    <span class="badge-status status-aktif">Aktif</span>
                                <?php } else { ?>
                                    <span class="badge-status status-nonaktif">Tidak Aktif</span>
                                <?php } ?>
                            </td>

                            <td>
                                <a
                                    href="edit_wali_kelas.php?id=<?= (int) $row['id'] ?>"
                                    class="btn btn-primary btn-sm"
                                >Edit</a>

                                <a
                                    href="hapus_wali_kelas.php?id=<?= (int) $row['id'] ?>"
                                    class="btn btn-danger btn-sm"
                                    onclick="return confirm('Yakin ingin menghapus data wali kelas ini?')"
                                >Hapus</a>
                            </td>
                        </tr>
                    <?php
                        }
                    } else {
                    ?>
                        <tr>
                            <td colspan="8" class="text-center">Belum ada data wali kelas.</td>
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
    const tabel = document.getElementById('tabelWaliKelas');
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
                ' dari ' + totalData + ' data wali kelas';
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
