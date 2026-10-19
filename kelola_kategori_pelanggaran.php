
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

$sql = "SELECT * FROM t_pelanggaran_kategori ORDER BY id DESC";
$query = mysqli_query($koneksi, $sql);

if (!$query) {
    die("Query gagal: " . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kategori Pelanggaran</title>

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">

    <style>
        html, body {
            min-height: 100%;
        }

        .sidebar {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            width: 16.666667%;
            height: 100vh;
            overflow-y: auto;
            z-index: 1030;
        }

        .konten-utama {
            margin-left: 16.666667%;
        }

        @media (max-width: 767.98px) {
            .sidebar {
                width: 240px;
                max-width: 80%;
            }

            .konten-utama {
                margin-left: 0;
            }
        }
    </style>
</head>

<body class="bg-light">

<div class="container-fluid">
    <div class="row">

        <!-- SIDEBAR -->
        <div class="sidebar bg-dark p-3">

            <h4 class="text-white mb-4">My Website</h4>

            <ul class="nav nav-pills flex-column">

                <li class="nav-item mb-2">
                    <a href="dashboard.php" class="nav-link text-white">Dashboard</a>
                </li>

                <li class="nav-item mb-2">
                    <a href="kelola_siswa.php" class="nav-link text-white">Data Siswa</a>
                </li>

                <li class="nav-item mb-2">
                    <a href="kelola_guru.php" class="nav-link text-white">Data Guru</a>
                </li>

                <li class="nav-item mb-2">
                    <a href="kelola_kelas.php" class="nav-link text-white">Kelas</a>
                </li>

                <li class="nav-item mb-2">
                    <a href="kelola_kategori_pelanggaran.php" class="nav-link active">
                        kelola kategori Pelanggaran
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="kelola_tahun_ajaran.php" class="nav-link text-white">
                        Tahun Ajaran
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="laporan.php" class="nav-link text-white">Laporan</a>
                </li>

                <li class="nav-item mb-2">
                    <a href="kelola_wali_kelas.php" class="nav-link text-white">
                        Wali Kelas
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="kelola_penempatan_siswa.php" class="nav-link text-white">
                        Penempatan Siswa
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="rekap_point.php" class="nav-link text-white">Rekap Poin</a>
                </li>

                <li class="nav-item mb-2">
                    <a href="riwayat.php" class="nav-link text-white">Riwayat</a>
                </li>

                <li class="nav-item mb-2">
                    <a href="kelola_jenis_pelanggaran.php" class="nav-link text-white">
                        Jenis Pelanggaran
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="export_pelanggaran.php" class="nav-link text-white">
                        Export Pelanggaran
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="tentang_saya.php" class="nav-link text-white">About Me</a>
                </li>

                <hr class="bg-secondary">

                <li class="nav-item">
                    <a href="logout.php" class="nav-link text-danger">Logout</a>
                </li>

            </ul>
        </div>

        <!-- KONTEN UTAMA -->
        <main class="konten-utama col-md-9 col-lg-10 p-4">

            <div class="card border-0 shadow-sm">
                <div class="card-body">

                    <h2>Kategori Pelanggaran</h2>

                    <p class="text-muted">
                        Halaman untuk mengelola kategori pelanggaran siswa.
                    </p>

                    <a href="dashboard.php" class="btn btn-secondary btn-sm">
                        Kembali ke Dashboard
                    </a>

                    <a href="tambah_kategori_pelanggaran.php"
                       class="btn btn-primary btn-sm">
                        + Tambah Kategori
                    </a>

                    <!-- PENCARIAN DAN JUMLAH DATA -->
                    <div class="row mt-4 mb-3">
                        <div class="col-md-6 mb-2">
                            <input type="search"
                                   id="cariKategori"
                                   class="form-control"
                                   placeholder="Cari nama kategori atau deskripsi...">
                        </div>

                        <div class="col-md-6 mb-2 d-flex align-items-center">
                            <label for="jumlahKategori" class="mr-2 mb-0">
                                Tampilkan:
                            </label>

                            <select id="jumlahKategori"
                                    class="form-control"
                                    style="width: auto;">
                                <option value="5">5</option>
                                <option value="10" selected>10</option>
                                <option value="25">25</option>
                                <option value="all">Semua</option>
                            </select>

                            <span class="ml-2">data</span>
                        </div>
                    </div>

                    <!-- TABEL -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover"
                               id="tabelKategori">

                            <thead class="thead-dark">
                                <tr>
                                    <th data-kolom="0">ID</th>
                                    <th data-kolom="1">Nama Kategori</th>
                                    <th data-kolom="2">Deskripsi</th>
                                    <th data-kolom="3">Status Aktif</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>

                            <tbody id="isiKategori">
                            <?php if (mysqli_num_rows($query) > 0) { ?>
                                <?php while ($data = mysqli_fetch_assoc($query)) { ?>
                                    <tr>
                                        <td><?= (int)$data['id'] ?></td>

                                        <td>
                                            <?= htmlspecialchars($data['nama'] ?? '') ?>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars($data['deskripsi'] ?? '') ?>
                                        </td>

                                        <td>
                                            <?php if (!empty($data['status_aktif'])) { ?>
                                                <span class="badge badge-success">
                                                    Aktif
                                                </span>
                                            <?php } else { ?>
                                                <span class="badge badge-secondary">
                                                    Tidak Aktif
                                                </span>
                                            <?php } ?>
                                        </td>

                                        <td class="text-nowrap">
                                            <a href="edit_kategori_pelanggaran.php?id=<?= (int)$data['id'] ?>"
                                               class="btn btn-primary btn-sm">
                                                Edit
                                            </a>

                                            <a href="hapus.php?id=<?= (int)$data['id'] ?>"
                                               class="btn btn-danger btn-sm"
                                               onclick="return confirm('Yakin ingin menghapus kategori ini?')">
                                                Hapus
                                            </a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            <?php } else { ?>
                                <tr>
                                    <td colspan="5" class="text-center">
                                        Belum ada data kategori pelanggaran.
                                    </td>
                                </tr>
                            <?php } ?>
                            </tbody>

                        </table>
                    </div>

                    <div id="infoKategori" class="text-muted mb-2"></div>
                    <div id="navigasiKategori" class="d-flex flex-wrap"></div>

                </div>
            </div>

        </main>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const tabel = document.getElementById('tabelKategori');
    const tbody = document.getElementById('isiKategori');
    const inputCari = document.getElementById('cariKategori');
    const inputJumlah = document.getElementById('jumlahKategori');
    const info = document.getElementById('infoKategori');
    const navigasi = document.getElementById('navigasiKategori');

    const semuaBaris = Array.from(tbody.querySelectorAll('tr'))
        .filter(tr => tr.cells.length === 5);

    let halaman = 1;
    let kolomUrut = -1;
    let arah = 1;

    function renderTabel() {
        const kata = inputCari.value.toLowerCase().trim();

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

        const pilihan = inputJumlah.value;
        const perHalaman = pilihan === 'all'
            ? Math.max(hasil.length, 1)
            : Number(pilihan);

        const totalHalaman = Math.max(
            1,
            Math.ceil(hasil.length / perHalaman)
        );

        halaman = Math.min(halaman, totalHalaman);

        const awal = (halaman - 1) * perHalaman;
        const tampil = hasil.slice(awal, awal + perHalaman);

        tbody.replaceChildren();

        if (tampil.length > 0) {
            tampil.forEach(baris => tbody.appendChild(baris));
        } else {
            const tr = document.createElement('tr');
            const td = document.createElement('td');

            td.colSpan = 5;
            td.className = 'text-center';
            td.textContent = 'Data kategori tidak ditemukan.';

            tr.appendChild(td);
            tbody.appendChild(tr);
        }

        info.textContent = hasil.length > 0
            ? `Menampilkan ${awal + 1}–${Math.min(
                awal + tampil.length,
                hasil.length
            )} dari ${hasil.length} data kategori`
            : 'Tidak ada data kategori yang cocok.';

        navigasi.replaceChildren();

        function buatTombol(teks, tujuan, nonaktif, aktif) {
            const tombol = document.createElement('button');

            tombol.type = 'button';
            tombol.textContent = teks;
            tombol.disabled = nonaktif;
            tombol.className = aktif
                ? 'btn btn-primary btn-sm mr-1 mb-1'
                : 'btn btn-outline-primary btn-sm mr-1 mb-1';

            tombol.addEventListener('click', function () {
                halaman = tujuan;
                renderTabel();
            });

            navigasi.appendChild(tombol);
        }

        buatTombol('Sebelumnya', halaman - 1, halaman === 1, false);

        for (let i = 1; i <= totalHalaman; i++) {
            buatTombol(String(i), i, false, i === halaman);
        }

        buatTombol(
            'Berikutnya',
            halaman + 1,
            halaman === totalHalaman,
            false
        );
    }

    inputCari.addEventListener('input', function () {
        halaman = 1;
        renderTabel();
    });

    inputJumlah.addEventListener('change', function () {
        halaman = 1;
        renderTabel();
    });

    tabel.querySelectorAll('thead th[data-kolom]').forEach(th => {
        th.addEventListener('click', function () {
            const kolom = Number(this.dataset.kolom);

            if (kolomUrut === kolom) {
                arah *= -1;
            } else {
                kolomUrut = kolom;
                arah = 1;
            }

            halaman = 1;
            renderTabel();
        });
    });

    renderTabel();
});
</script>

</body>
</html>
