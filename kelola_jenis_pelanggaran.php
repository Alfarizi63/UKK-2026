
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

// Halaman ini khusus admin
$role = $_SESSION['role'] ?? '';

if ($role !== 'admin') {
    header('Location: dashboard.php');
    exit;
}

$sql = "SELECT * FROM t_pelanggaran ORDER BY id DESC";
$result = mysqli_query($koneksi, $sql);

if (!$result) {
    die("Gagal mengambil data: " . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Jenis Pelanggaran</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f8f9fa;
            margin: 0;
            color: #222;
        }

        .main-content {
            margin-left: 250px;
            padding: 25px;
            min-width: 0;
        }

        .container-content {
            width: 100%;
            margin: 0 auto;
        }

        .card {
            background: white;
            padding: 22px;
            margin-bottom: 22px;
            border-radius: 6px;
            border: 1px solid #dee2e6;
            box-shadow: none;
        }

        h2 {
            margin-top: 0;
            font-size: 24px;
        }

        label {
            display: block;
            margin: 14px 0 6px;
            font-weight: bold;
        }

        input,
        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }

        .btn-simpan {
            background: #198754;
            color: white;
            margin-top: 16px;
            padding: 9px 13px;
            border: none;
            border-radius: 5px;
        }

        .btn-edit {
            background: #007bff;
            color: white;
        }

        .btn-hapus {
            background: #dc3545;
            color: white;
        }

        .btn-kembali {
            background: #6c757d;
            color: white;
            margin-bottom: 18px;
        }

        .btn-edit,
        .btn-hapus,
        .btn-kembali {
            display: inline-block;
            padding: 8px 12px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 14px;
        }

        .btn-edit:hover,
        .btn-hapus:hover,
        .btn-kembali:hover {
            color: white;
            opacity: 0.85;
        }

        .table-wrapper {
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th,
        td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
            vertical-align: middle;
        }

        th {
            background: #212529;
            color: white;
            white-space: nowrap;
            cursor: pointer;
        }

        th.no-sort {
            cursor: default;
        }

        .pesan {
            padding: 12px;
            background: #d1e7dd;
            color: #0f5132;
            border-radius: 5px;
            margin-bottom: 15px;
        }

        .kosong {
            text-align: center;
            color: #777;
        }

        .table-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 15px;
        }

        .table-controls input {
            width: 260px;
            max-width: 100%;
        }

        .table-controls select {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .pagination-container {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 5px;
            margin-top: 15px;
        }

        .pagination-container button {
            background: #fff;
            color: #007bff;
            border: 1px solid #dee2e6;
            padding: 8px 12px;
            border-radius: 4px;
        }

        .pagination-container button.active {
            background: #007bff;
            color: white;
        }

        .pagination-container button:disabled {
            color: #999;
            cursor: not-allowed;
        }

        .table-info {
            margin-top: 12px;
            color: #555;
            font-size: 14px;
        }

        @media (max-width: 767px) {
            .main-content {
                margin-left: 0;
                padding: 15px;
            }

            .table-controls {
                align-items: stretch;
                flex-direction: column;
            }

            .table-controls input {
                width: 100%;
            }

            .card {
                padding: 15px;
            }
        }
    </style>
</head>

<body>

<?php include 'includes/sidebar.php'; ?>

<main class="main-content">

    <div class="container-content">

        <a href="dashboard.php" class="btn-kembali">
            Kembali ke Dashboard
        </a>

        <div class="card">

            <h2>Tambah Jenis Pelanggaran</h2>

            <?php if (isset($_SESSION['pesan'])): ?>
                <div class="pesan">
                    <?= htmlspecialchars($_SESSION['pesan']) ?>
                </div>
                <?php unset($_SESSION['pesan']); ?>
            <?php endif; ?>

            <form action="proses_jenis_pelanggaran.php" method="POST">

                <input type="hidden" name="aksi" value="tambah">

                <label for="nama_pelanggaran">
                    Nama Pelanggaran
                </label>

                <input
                    type="text"
                    id="nama_pelanggaran"
                    name="nama_pelanggaran"
                    maxlength="150"
                    required
                >

                <label for="poin">Poin Pelanggaran</label>

                <input
                    type="number"
                    id="poin"
                    name="poin"
                    min="1"
                    required
                >

                <label for="keterangan">Keterangan</label>

                <textarea
                    id="keterangan"
                    name="keterangan"
                    rows="3"
                ></textarea>

                <button type="submit" class="btn-simpan">
                    Simpan Pelanggaran
                </button>

            </form>

        </div>

        <div class="card">

            <h2>Daftar Jenis Pelanggaran</h2>

            <div class="table-controls">

                <div>
                    <label for="jumlahBaris">Tampilkan:</label>

                    <select id="jumlahBaris">
                        <option value="5">5</option>
                        <option value="10" selected>10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="all">Semua</option>
                    </select>
                </div>

                <div>
                    <label for="cariTabel">Cari data:</label>

                    <input
                        type="search"
                        id="cariTabel"
                        placeholder="Cari jenis pelanggaran..."
                    >
                </div>

            </div>

            <div class="table-wrapper">

                <table id="tabelPelanggaran">

                    <thead>
                        <tr>
                            <th data-sort="number">ID ↕</th>
                            <th data-sort="text">Nama Pelanggaran ↕</th>
                            <th data-sort="number">Poin ↕</th>
                            <th data-sort="text">Keterangan ↕</th>
                            <th class="no-sort">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php if (mysqli_num_rows($result) > 0): ?>

                        <?php while ($row = mysqli_fetch_assoc($result)): ?>

                            <tr>

                                <td>
                                    <?= (int) $row['id'] ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($row['nama'] ?? '-') ?>
                                </td>

                                <td>
                                    <?= (int) $row['poin'] ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($row['deskripsi'] ?? '-') ?>
                                </td>

                                <td>

                                    <a
                                        href="edit_jenis_pelanggaran.php?id=<?= (int) $row['id'] ?>"
                                        class="btn-edit"
                                    >
                                        Edit
                                    </a>

                                    <a
                                        href="proses_jenis_pelanggaran.php?aksi=hapus&id=<?= (int) $row['id'] ?>"
                                        class="btn-hapus"
                                        onclick="return confirm('Yakin ingin menghapus pelanggaran ini?')"
                                    >
                                        Hapus
                                    </a>

                                </td>

                            </tr>

                        <?php endwhile; ?>

                    <?php else: ?>

                        <tr class="baris-kosong">
                            <td colspan="5" class="kosong">
                                Belum ada data jenis pelanggaran.
                            </td>
                        </tr>

                    <?php endif; ?>

                    </tbody>

                </table>

            </div>

            <div class="table-info" id="infoTabel"></div>

            <div class="pagination-container" id="paginationTabel"></div>

        </div>

    </div>

</main>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const tabel = document.getElementById('tabelPelanggaran');
    const tbody = tabel.querySelector('tbody');
    const inputCari = document.getElementById('cariTabel');
    const jumlahBaris = document.getElementById('jumlahBaris');
    const infoTabel = document.getElementById('infoTabel');
    const pagination = document.getElementById('paginationTabel');
    const headers = tabel.querySelectorAll('thead th[data-sort]');

    let semuaBaris = Array.from(tbody.querySelectorAll('tr'))
        .filter(function (baris) {
            return !baris.classList.contains('baris-kosong');
        });

    let halamanAktif = 1;
    let kolomUrut = -1;
    let arahUrut = 1;

    function barisTampil() {

        const kata = inputCari.value.trim().toLowerCase();

        return semuaBaris.filter(function (baris) {
            return baris.textContent.toLowerCase().includes(kata);
        });

    }

    function renderTabel() {

        const data = barisTampil();
        const pilihan = jumlahBaris.value;

        const perHalaman = pilihan === 'all'
            ? Math.max(data.length, 1)
            : parseInt(pilihan, 10);

        const total = data.length;

        const totalHalaman = Math.max(
            1,
            Math.ceil(total / perHalaman)
        );

        if (halamanAktif > totalHalaman) {
            halamanAktif = totalHalaman;
        }

        const awal = (halamanAktif - 1) * perHalaman;

        const akhir = pilihan === 'all'
            ? total
            : Math.min(awal + perHalaman, total);

        semuaBaris.forEach(function (baris) {
            baris.style.display = 'none';
        });

        data.forEach(function (baris, indeks) {
            baris.style.display =
                indeks >= awal && indeks < akhir ? '' : 'none';
        });

        let barisKosong = tbody.querySelector('.baris-kosong');

        if (total === 0) {

            if (!barisKosong) {

                barisKosong = document.createElement('tr');
                barisKosong.className = 'baris-kosong';

                const sel = document.createElement('td');

                sel.colSpan = 5;
                sel.className = 'kosong';

                barisKosong.appendChild(sel);
                tbody.appendChild(barisKosong);

            }

            barisKosong.style.display = '';

            barisKosong.querySelector('td').textContent =
                semuaBaris.length === 0
                    ? 'Belum ada data jenis pelanggaran.'
                    : 'Data yang dicari tidak ditemukan.';

        } else if (barisKosong) {

            barisKosong.style.display = 'none';

        }

        infoTabel.textContent = total === 0
            ? 'Tidak ada data yang ditampilkan.'
            : 'Menampilkan ' + (awal + 1) + '–' + akhir +
              ' dari ' + total + ' data.';

        renderPagination(totalHalaman);

    }

    function renderPagination(totalHalaman) {

        pagination.innerHTML = '';

        function buatTombol(teks, halaman, nonaktif, aktif) {

            const tombol = document.createElement('button');

            tombol.type = 'button';
            tombol.textContent = teks;
            tombol.disabled = nonaktif;

            if (aktif) {
                tombol.classList.add('active');
            }

            tombol.addEventListener('click', function () {
                halamanAktif = halaman;
                renderTabel();
            });

            pagination.appendChild(tombol);

        }

        buatTombol(
            'Sebelumnya',
            halamanAktif - 1,
            halamanAktif === 1,
            false
        );

        for (let i = 1; i <= totalHalaman; i++) {

            if (
                totalHalaman <= 7 ||
                i === 1 ||
                i === totalHalaman ||
                Math.abs(i - halamanAktif) <= 1
            ) {

                buatTombol(
                    String(i),
                    i,
                    false,
                    i === halamanAktif
                );

            } else if (
                i === halamanAktif - 2 ||
                i === halamanAktif + 2
            ) {

                const titik = document.createElement('span');

                titik.textContent = '...';
                titik.style.padding = '8px';

                pagination.appendChild(titik);

            }

        }

        buatTombol(
            'Selanjutnya',
            halamanAktif + 1,
            halamanAktif === totalHalaman,
            false
        );

    }

    headers.forEach(function (header, indeks) {

        header.addEventListener('click', function () {

            if (kolomUrut === indeks) {
                arahUrut *= -1;
            } else {
                kolomUrut = indeks;
                arahUrut = 1;
            }

            semuaBaris.sort(function (a, b) {

                const teksA = a.cells[indeks].textContent.trim();
                const teksB = b.cells[indeks].textContent.trim();

                if (header.dataset.sort === 'number') {

                    return (
                        (Number(teksA) - Number(teksB)) * arahUrut
                    );

                }

                return teksA.localeCompare(teksB, 'id', {
                    numeric: true,
                    sensitivity: 'base'
                }) * arahUrut;

            });

            semuaBaris.forEach(function (baris) {
                tbody.appendChild(baris);
            });

            halamanAktif = 1;
            renderTabel();

        });

    });

    inputCari.addEventListener('input', function () {
        halamanAktif = 1;
        renderTabel();
    });

    jumlahBaris.addEventListener('change', function () {
        halamanAktif = 1;
        renderTabel();
    });

    renderTabel();

});
</script>

</body>
</html>
