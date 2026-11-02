
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
          href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">

    <style>

/* SAMAKAN SIDEBAR DENGAN LAPORAN.PHP */
.sidebar {
    background: #212529 !important;
    min-height: 100vh;
}

.sidebar a {
    color: white;
}

.sidebar .nav-link {
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 0.25rem;
}

.sidebar .nav-link:hover {
    background: #343a40;
    color: white;
}

.sidebar .nav-link.active {
    background: #007bff !important;
    color: white !important;
}

/* SAMAKAN UKURAN SIDEBAR DAN KONTEN */
@media (min-width: 992px) {
    .sidebar {
        flex: 0 0 16.666667%;
        max-width: 16.666667%;
    }

    main.col-lg-10 {
        flex: 0 0 83.333333%;
        max-width: 83.333333%;
    }
}

    </style>
</head>

<body>

<div class="container-fluid">
    <div class="row">

        <!-- SIDEBAR ADMIN -->
        <?php if ($role === 'admin') { ?>

        <aside class="col-md-3 col-lg-2 sidebar p-3">
            <h4 class="text-white mb-4">My Website</h4>

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
                        Kelola Kategori Pelanggaran
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="kelola_tahun_ajaran.php" class="nav-link">
                        Kelola Tahun Ajaran
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="laporan.php" class="nav-link">Laporan</a>
                </li>

                <li class="nav-item mb-2">
                    <a href="kelola_wali_kelas.php" class="nav-link">
                        Kelola Wali Kelas
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="kelola_penempatan_siswa.php" class="nav-link">
                        Kelola Penempatan Siswa
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="rekap_point.php" class="nav-link">Rekap Poin</a>
                </li>

                <li class="nav-item mb-2">
                    <a href="riwayat.php" class="nav-link active">Riwayat</a>
                </li>

                <li class="nav-item mb-2">
                    <a href="kelola_jenis_pelanggaran.php" class="nav-link">
                        Kelola Jenis Pelanggaran
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="export_pelanggaran.php" class="nav-link">
                        Export Pelanggaran
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="tentang_saya.php" class="nav-link">About Me</a>
                </li>

                <hr class="bg-secondary">

                <li class="nav-item">
                    <a href="logout.php" class="nav-link text-danger">Logout</a>
                </li>

            </ul>
        </aside>

        <?php } ?>

        <!-- SIDEBAR GURU -->
        <?php if ($role === 'guru') { ?>

        <aside class="col-md-3 col-lg-2 sidebar p-3">
            <h4 class="text-white mb-4">My Website</h4>

            <ul class="nav nav-pills flex-column">

                <li class="nav-item mb-2">
                    <a href="dashboard.php" class="nav-link">Dashboard</a>
                </li>

                <li class="nav-item mb-2">
                    <a href="catatan_pelanggaran.php" class="nav-link">
                        catatan
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="tindakan.php" class="nav-link">tindakan</a>
                </li>

                <li class="nav-item mb-2">
                    <a href="laporan.php" class="nav-link">laporan</a>
                </li>

                <li class="nav-item mb-2">
                    <a href="riwayat.php" class="nav-link active">riwayat</a>
                </li>

                <li class="nav-item mb-2">
                    <a href="rekap_point.php" class="nav-link">rekap point</a>
                </li>

                <li class="nav-item">
                    <a href="logout.php" class="nav-link text-danger">Logout</a>
                </li>

            </ul>
        </aside>

        <?php } ?>

        <?php if ($role !== 'admin' && $role !== 'guru') { ?>

        <main class="col-12 p-4">
            <div class="alert alert-danger">
                Role pengguna tidak dikenali.
            </div>
        </main>

        <?php } ?>

        <!-- KONTEN RIWAYAT -->
        <?php if ($role === 'admin' || $role === 'guru') { ?>

        <main class="col-md-9 col-lg-10 p-4">

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

                    <!-- KONTROL TABEL -->
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
                                class="form-control"
                                placeholder="Cari riwayat pelanggaran..."
                            >
                        </div>

                    </div>

                    <!-- TABEL -->
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

                                            <span class="badge badge-success">
                                                Aktif
                                            </span>

                                        <?php } else { ?>

                                            <span class="badge badge-secondary">
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

                        <a href="dashboard.php"
                           class="btn btn-secondary kembali-dashboard">
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

        <?php } ?>

    </div>
</div>

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
