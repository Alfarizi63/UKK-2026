
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

$sql = "SELECT id, kode, nama, poin, deskripsi, status_aktif
        FROM t_pelanggaran
        ORDER BY id DESC";

$query = mysqli_query($koneksi, $sql);

if (!$query) {
    die("Gagal mengambil data: " . mysqli_error($koneksi));
}

$role = $_SESSION['role'] ?? '';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Riwayat Pelanggaran</title>

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #f4f6f9;
            font-family: Arial, sans-serif;
        }

        .sidebar {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            width: 250px;
            background: #343a40;
            padding: 20px 15px;
            overflow-y: auto;
            z-index: 1000;
        }

        .sidebar h4 {
            color: white;
            font-weight: bold;
        }

        .sidebar a {
            display: block;
            color: white;
            padding: 10px 14px;
            margin-bottom: 5px;
            border-radius: 4px;
            text-decoration: none;
        }

        .sidebar a:hover {
            background: #495057;
        }

        .sidebar a.active {
            background: #007bff;
            color: white;
        }

        .konten {
            margin-left: 250px;
            width: calc(100% - 250px);
            padding: 25px;
            min-width: 0;
        }

        .card {
            border: 1px solid #dee2e6;
            border-radius: 6px;
        }

        .kontrol-tabel {
            display: flex;
            justify-content: space-between;
            align-items: end;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 15px;
        }

        .kontrol-tabel label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .kontrol-tabel input,
        .kontrol-tabel select {
            padding: 8px;
            border: 1px solid #ced4da;
            border-radius: 4px;
        }

        .kontrol-tabel input {
            width: 260px;
            max-width: 100%;
        }

        #tabelRiwayat th[data-sort] {
            cursor: pointer;
            white-space: nowrap;
        }

        #tabelRiwayat th.no-sort {
            cursor: default;
        }

        .pagination-area {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
            margin-top: 15px;
        }

        .pagination-area button {
            border: 1px solid #dee2e6;
            background: white;
            color: #007bff;
            padding: 6px 11px;
            border-radius: 4px;
        }

        .pagination-area button.active {
            background: #007bff;
            color: white;
        }

        .pagination-area button:disabled {
            color: #999;
            cursor: not-allowed;
        }

        #infoTabel {
            margin-top: 12px;
            color: #6c757d;
            font-size: 14px;
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

            .kontrol-tabel {
                align-items: stretch;
                flex-direction: column;
            }

            .kontrol-tabel input {
                width: 100%;
            }
        }

        @media print {
            .sidebar,
            .tombol-aksi,
            .kontrol-tabel,
            .pagination-area,
            #infoTabel {
                display: none !important;
            }

            .konten {
                margin: 0;
                padding: 0;
                width: 100%;
            }

            body {
                background: white;
            }

            .card {
                border: none;
                box-shadow: none;
            }
        }
    </style>
</head>

<body>

<?php if ($role === 'admin'): ?>

<!-- SIDEBAR ADMIN -->
<div class="sidebar">
    <h4 class="mb-4">My Website</h4>

    <a href="dashboard.php">Dashboard</a>
    <a href="kelola_siswa.php">Data Siswa</a>
    <a href="kelola_guru.php">Data Guru</a>
    <a href="kelola_kelas.php">Kelas</a>
    <a href="kelola_kategori_pelanggaran.php">Kategori Pelanggaran</a>
    <a href="kelola_tahun_ajaran.php">Tahun Ajaran</a>
    <a href="laporan.php">Laporan</a>
    <a href="kelola_wali_kelas.php">Wali Kelas</a>
    <a href="kelola_penempatan_siswa.php">Penempatan Siswa</a>
    <a href="rekap_point.php">Rekap Poin</a>
    <a href="riwayat.php" class="active">Riwayat</a>
    <a href="kelola_jenis_pelanggaran.php">Jenis Pelanggaran</a>
    <a href="export_pelanggaran.php">Export Pelanggaran</a>
    <a href="tentang_saya.php">About Me</a>
    <hr class="bg-secondary">
    <a href="logout.php" class="text-danger">Logout</a>
</div>

<?php elseif ($role === 'guru'): ?>

<!-- SIDEBAR GURU -->
<div class="sidebar">
    <h4 class="mb-4">My Website</h4>

    <a href="dashboard.php">Dashboard</a>
    <a href="catatan_pelanggaran.php">Catatan</a>
    <a href="tindakan.php">Tindakan</a>
    <a href="laporan.php">Laporan</a>
    <a href="riwayat.php" class="active">Riwayat</a>
    <a href="rekap_point.php">Rekap Poin</a>
    <hr class="bg-secondary">
    <a href="logout.php" class="text-danger">Logout</a>
</div>

<?php else: ?>

<div class="container mt-5">
    <div class="alert alert-danger">
        Role pengguna tidak dikenali.
    </div>
</div>

<?php endif; ?>

<?php if ($role === 'admin' || $role === 'guru'): ?>

<main class="konten">

    <h2>Riwayat Pelanggaran</h2>
    <p class="text-muted">
        Daftar jenis pelanggaran yang tersimpan dalam sistem.
    </p>

    <div class="card shadow-sm">
        <div class="card-body">

            <div class="kontrol-tabel">
                <div>
                    <label for="jumlahBaris">Tampilkan data:</label>
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
                        placeholder="Cari riwayat pelanggaran..."
                    >
                </div>
            </div>

            <div class="table-responsive">
                <table id="tabelRiwayat"
                       class="table table-bordered table-striped">
                    <thead class="thead-dark">
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
                    <?php if (mysqli_num_rows($query) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($query)): ?>
                            <tr>
                                <td><?= (int) $row['id']; ?></td>
                                <td>
                                    <?= htmlspecialchars($row['kode'] ?? '-'); ?>
                                </td>
                                <td>
                                    <?= htmlspecialchars($row['nama'] ?? '-'); ?>
                                </td>
                                <td><?= (int) $row['poin']; ?></td>
                                <td>
                                    <?= htmlspecialchars($row['deskripsi'] ?? '-'); ?>
                                </td>
                                <td>
                                    <?php if ((int) $row['status_aktif'] === 1): ?>
                                        <span class="badge badge-success">
                                            Aktif
                                        </span>
                                    <?php else: ?>
                                        <span class="badge badge-secondary">
                                            Tidak Aktif
                                        </span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr class="baris-kosong">
                            <td colspan="6" class="text-center">
                                Belum ada data pelanggaran.
                            </td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div id="infoTabel"></div>
            <div id="paginationTabel" class="pagination-area"></div>

            <div class="tombol-aksi mt-3">
                <a href="dashboard.php" class="btn btn-secondary">
                    Kembali
                </a>

                <button
                    type="button"
                    onclick="window.print()"
                    class="btn btn-primary"
                >
                    Cetak
                </button>
            </div>

        </div>
    </div>

</main>

<?php endif; ?>

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
        const totalHalaman = Math.max(1, Math.ceil(total / perHalaman));

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

                if (indeks === 5) {
                    teksA = teksA.toLowerCase().trim();
                    teksB = teksB.toLowerCase().trim();
                }

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

    renderTabel();
});
</script>

</body>
</html>
