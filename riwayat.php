
<?php
include 'includes/cek_session.php';

$role = $_SESSION['role'] ?? '';

if (!in_array($role, ['admin', 'guru', 'wali_kelas'], true)) {
    header('Location: dashboard.php');
    exit;
}

include 'config/koneksi.php';

$sql = "SELECT  siswa_id, nama_siswa, nama_kelas, keterangan, tindakan,
        FROM t_pelanggaran_siswa
        ORDER BY id ASC";

$query = mysqli_query($koneksi, $sql);

if (!$query) {
    die("Gagal mengambil data: " . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Riwayat Pelanggaran</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f8f9fa;
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .main-content {
            margin-left: 250px;
            padding: 25px;
            min-width: 0;
        }

        .tabel-wrapper {
            width: 100%;
            overflow-x: auto;
            background: white;
        }

        #tabelRiwayat {
            width: 100%;
            margin-bottom: 0;
        }

        #tabelRiwayat th {
            background: #212529;
            color: white;
            white-space: nowrap;
            cursor: pointer;
        }

        #tabelRiwayat th,
        #tabelRiwayat td {
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

        .pagination-area {
            display: flex;
            gap: 5px;
            flex-wrap: wrap;
            margin: 15px 0;
        }

        .pagination-area button {
            border: 1px solid #007bff;
            background: white;
            color: #007bff;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
        }

        .pagination-area button:hover {
            background: #007bff;
            color: white;
        }

        .pagination-area button.active {
            background: #007bff;
            color: white;
        }

        .pagination-area button:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        @media (max-width: 767px) {
            .main-content {
                margin-left: 0;
                padding: 15px;
            }

            .kontrol-tabel {
                flex-direction: column;
                align-items: stretch;
            }

            .kontrol-tabel input {
                max-width: 100%;
            }
        }

        @media print {
            .sidebar,
            .kontrol-tabel,
            .pagination-area,
            .tombol-aksi,
            #infoTabel {
                display: none !important;
            }

            .main-content {
                width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            body {
                background: white;
            }

            .tabel-wrapper {
                overflow: visible;
            }

            #tabelRiwayat {
                font-size: 11px;
            }

            #tabelRiwayat tr {
                display: table-row !important;
            }
        }
    </style>
</head>

<body>

<?php include 'includes/sidebar.php'; ?>

<main class="main-content">

    <div class="d-flex justify-content-between align-items-center flex-wrap">

        <div>
            <h2>Riwayat Pelanggaran</h2>

            <p class="text-muted">
                Daftar jenis pelanggaran yang tersimpan dalam sistem.
            </p>
        </div>

    </div>

    <div class="card shadow-sm">
        <div class="card-body">

            <?php if ($role === 'guru' || $role === 'wali_kelas') { ?>
                <div class="mb-3">
                    <h5>
                        Selamat datang,
                        <?= htmlspecialchars($_SESSION['name'] ?? '') ?>
                    </h5>
                    <p>Anda login sebagai: <?= htmlspecialchars($role) ?></p>
                </div>
            <?php } ?>

            <!-- KONTROL TABEL -->
            <div class="kontrol-tabel">

                <div>
                    <label for="jumlahBaris">
                        Tampilkan data:
                    </label>

                    <select id="jumlahBaris">
                        <option value="5">5</option>
                        <option value="10" selected>10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="all">Semua</option>
                    </select>
                </div>

                <div>
                    <label for="cariTabel">
                        Cari data:
                    </label>

                    <input
                        type="search"
                        id="cariTabel"
                        class="form-control"
                        placeholder="Cari riwayat pelanggaran..."
                    >
                </div>

            </div>

            <!-- TABEL -->
            <div class="tabel-wrapper">

                <table id="tabelRiwayat"
                       class="table table-bordered table-striped">

                    <thead>
                        <tr>
                            <th data-sort="number">ID ↕</th>
                            <th data-sort="text">Kode ↕</th>
                            <th data-sort="text">Nama Pelanggaran ↕</th>
                            <th data-sort="number">Poin ↕</th>
                            <th data-sort="text">Deskripsi ↕</th>
                            <th data-sort="text">Status ↕</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php if (mysqli_num_rows($query) > 0) { ?>

                        <?php while ($row = mysqli_fetch_assoc($query)) { ?>

                        <tr>
                            <td><?= (int) $row['id']; ?></td>

                            <td>
                                <?= htmlspecialchars($row['kode'] ?? '-'); ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($row['nama'] ?? '-'); ?>
                            </td>

                            <td>
                                <?= (int) $row['poin']; ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($row['deskripsi'] ?? '-'); ?>
                            </td>

                            <td>
                                <?php if ((int) $row['status_aktif'] === 1) { ?>

                                    <span class="badge bg-success">
                                        Aktif
                                    </span>

                                <?php } else { ?>

                                    <span class="badge bg-secondary">
                                        Tidak Aktif
                                    </span>

                                <?php } ?>
                            </td>
                        </tr>

                        <?php } ?>

                    <?php } else { ?>

                        <tr class="baris-kosong">
                            <td colspan="6" class="text-center">
                                Belum ada data pelanggaran.
                            </td>
                        </tr>

                    <?php } ?>

                    </tbody>

                </table>

            </div>

            <p id="infoTabel" class="mt-3"></p>

            <div id="paginationTabel" class="pagination-area"></div>

            <!-- TOMBOL AKSI -->
            <div class="tombol-aksi mt-3">

                <a href="dashboard.php" class="btn btn-secondary">
                    Kembali
                </a>

                <button
                    type="button"
                    onclick="window.print()"
                    class="btn btn-primary">
                    Cetak
                </button>

            </div>

        </div>
    </div>

</main>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const tabel = document.getElementById('tabelRiwayat');

    if (!tabel) {
        return;
    }

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

    function renderTabel() {

        const kata = inputCari.value.trim().toLowerCase();

        const data = semuaBaris.filter(function (baris) {
            return baris.textContent.toLowerCase().includes(kata);
        });

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

                sel.colSpan = 6;
                sel.className = 'text-center';

                barisKosong.appendChild(sel);
                tbody.appendChild(barisKosong);
            }

            barisKosong.style.display = '';

            barisKosong.querySelector('td').textContent =
                semuaBaris.length === 0
                    ? 'Belum ada data pelanggaran.'
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

                let teksA = a.cells[indeks].textContent.trim();
                let teksB = b.cells[indeks].textContent.trim();

                if (header.dataset.sort === 'number') {
                    return (Number(teksA) - Number(teksB)) * arahUrut;
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

    // CETAK SELURUH DATA
    let kondisiCetak = null;

    window.addEventListener('beforeprint', function () {

        kondisiCetak = {
            pencarian: inputCari.value,
            jumlah: jumlahBaris.value,
            halaman: halamanAktif
        };

        tbody.replaceChildren();

        if (semuaBaris.length === 0) {

            const tr = document.createElement('tr');
            const td = document.createElement('td');

            td.colSpan = 6;
            td.className = 'text-center';
            td.textContent = 'Belum ada data pelanggaran.';

            tr.appendChild(td);
            tbody.appendChild(tr);

            return;
        }

        semuaBaris.forEach(function (tr) {
            tbody.appendChild(tr);
        });
    });

    window.addEventListener('afterprint', function () {

        if (!kondisiCetak) {
            return;
        }

        inputCari.value = kondisiCetak.pencarian;
        jumlahBaris.value = kondisiCetak.jumlah;
        halamanAktif = kondisiCetak.halaman;

        kondisiCetak = null;

        renderTabel();
    });

    renderTabel();

});
</script>

</body>
</html>
