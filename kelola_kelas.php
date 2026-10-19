
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

$query = mysqli_query($koneksi, "
    SELECT * FROM t_kelas
    ORDER BY id DESC
");

if (!$query) {
    die("Gagal mengambil data kelas: " . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Kelas</title>

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css"
          integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO"
          crossorigin="anonymous">
</head>

<body>

<div class="container-fluid">
    <div class="row">

        <!-- SIDEBAR -->
        <div class="col-md-3 col-lg-2 bg-dark min-vh-100 p-3 position-fixed fixed-top overflow-auto"
             style="height: 100vh; bottom: 0; z-index: 1030;">

            <h4 class="text-white mb-4">
                My Website
            </h4>

            <ul class="nav nav-pills flex-column">

                <li class="nav-item mb-2">
                    <a href="dashboard.php" class="nav-link text-white">
                        Dashboard
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="kelola_siswa.php" class="nav-link text-white">
                        Data Siswa
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="kelola_guru.php" class="nav-link text-white">
                        Data Guru
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="kelola_kelas.php" class="nav-link active">
                        Kelas
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="kelola_kategori_pelanggaran.php" class="nav-link text-white">
                        kelola kategori pelanggaran
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="kelola_tahun_ajaran.php" class="nav-link text-white">
                        Kelola tahun ajaran
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="laporan.php" class="nav-link text-white">
                        Laporan
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="kelola_wali_kelas.php" class="nav-link text-white">
                        kelola wali kelas
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="kelola_penempatan_siswa.php" class="nav-link text-white">
                        kelola penempatan siswa
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="rekap_point.php" class="nav-link text-white">
                        rekap poin
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="riwayat.php" class="nav-link text-white">
                        riwayat
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="kelola_jenis_pelanggaran.php" class="nav-link text-white">
                        kelola jenis pelanggaran
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="export_pelanggaran.php" class="nav-link text-white">
                        export pelanggaran
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="tentang_saya.php" class="nav-link text-white">
                        About Me
                    </a>
                </li>

                <hr class="bg-secondary">

                <li class="nav-item">
                    <a href="logout.php" class="nav-link text-danger">
                        Logout
                    </a>
                </li>

            </ul>
        </div>

        <!-- KONTEN -->
        <main class="col-md-9 col-lg-10 p-4 offset-md-3 offset-lg-2">

            <h2>Kelola Kelas</h2>
            <p>Halaman untuk mengelola data kelas.</p>

            <a href="dashboard.php" class="btn btn-secondary btn-sm">
                Kembali ke Dashboard
            </a>

            <a href="tambah_kelas.php" class="btn btn-primary btn-sm">
                Tambah Kelas
            </a>

            <hr>

            <div class="d-flex flex-wrap align-items-center mb-3">
                <input type="search"
                       id="cariKelas"
                       placeholder="Cari nama kelas atau jurusan..."
                       class="form-control mr-3 mb-2"
                       style="max-width: 320px;">

                <label class="mr-2 mb-2">
                    Tampilkan
                </label>

                <select id="jumlahKelas"
                        class="form-control mb-2"
                        style="width: auto;">
                    <option value="5">5</option>
                    <option value="10" selected>10</option>
                    <option value="25">25</option>
                    <option value="all">Semua</option>
                </select>

                <span class="ml-2 mb-2">data</span>
            </div>

            <div class="table-responsive">
                <table id="tabelKelas"
                       class="table table-bordered table-striped table-hover">

                    <thead class="thead-dark">
                        <tr>
                            <th data-kolom="0">No</th>
                            <th data-kolom="1">Nama Kelas</th>
                            <th data-kolom="2">Tingkat</th>
                            <th data-kolom="3">Jurusan</th>
                            <th data-kolom="4">Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody id="isiTabel">
                    <?php
                    $no = 1;

                    if (mysqli_num_rows($query) > 0) {
                        while ($kelas = mysqli_fetch_assoc($query)) {
                    ?>
                        <tr>
                            <td><?= $no++ ?></td>

                            <td>
                                <?= htmlspecialchars($kelas['nama']) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($kelas['tingkat']) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($kelas['jurusan']) ?>
                            </td>

                            <td>
                                <?php if ($kelas['status_aktif']): ?>
                                    <span class="badge badge-success">Aktif</span>
                                <?php else: ?>
                                    <span class="badge badge-secondary">Tidak Aktif</span>
                                <?php endif; ?>
                            </td>

                            <td class="text-nowrap">
                                <a href="edit_kelas.php?id=<?= (int)$kelas['id'] ?>"
                                   class="btn btn-primary btn-sm">
                                    Edit
                                </a>

                                <a href="hapus_kelas.php?id=<?= (int)$kelas['id'] ?>"
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('Yakin ingin menghapus kelas ini?')">
                                    Hapus
                                </a>
                            </td>
                        </tr>
                    <?php
                        }
                    } else {
                    ?>
                        <tr>
                            <td colspan="6" class="text-center">
                                Belum ada data kelas.
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>

                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <p id="infoData" class="text-muted"></p>

                <div id="navigasi" class="mb-3"></div>
            </div>

        </main>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const tbody = document.getElementById('isiTabel');
    const cari = document.getElementById('cariKelas');
    const jumlah = document.getElementById('jumlahKelas');
    const info = document.getElementById('infoData');
    const navigasi = document.getElementById('navigasi');
    const tabel = document.getElementById('tabelKelas');

    const semuaBaris = Array.from(tbody.querySelectorAll('tr'))
        .filter(tr => tr.cells.length === 6);

    let halaman = 1;
    let kolomUrut = -1;
    let arah = 1;

    function renderTabel() {
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

        const pilihan = jumlah.value;
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
            tampil.forEach((baris, index) => {
                baris.cells[0].textContent = awal + index + 1;
                tbody.appendChild(baris);
            });
        } else {
            const tr = document.createElement('tr');
            const td = document.createElement('td');

            td.colSpan = 6;
            td.textContent = 'Data kelas tidak ditemukan.';

            tr.appendChild(td);
            tbody.appendChild(tr);
        }

        info.textContent = hasil.length > 0
            ? `Menampilkan ${awal + 1}–${Math.min(
                awal + tampil.length,
                hasil.length
              )} dari ${hasil.length} data kelas`
            : 'Tidak ada data kelas yang cocok.';

        navigasi.replaceChildren();

        function buatTombol(teks, tujuan, nonaktif, aktif) {
            const tombol = document.createElement('button');

            tombol.type = 'button';
            tombol.textContent = teks;
            tombol.className = aktif
                ? 'btn btn-primary btn-sm mr-1'
                : 'btn btn-outline-primary btn-sm mr-1';
            tombol.disabled = nonaktif;

            tombol.addEventListener('click', function () {
                halaman = tujuan;
                renderTabel();
            });

            navigasi.appendChild(tombol);
        }

        buatTombol('‹', halaman - 1, halaman === 1, false);

        for (let i = 1; i <= totalHalaman; i++) {
            buatTombol(String(i), i, false, i === halaman);
        }

        buatTombol('›', halaman + 1, halaman === totalHalaman, false);
    }

    cari.addEventListener('input', function () {
        halaman = 1;
        renderTabel();
    });

    jumlah.addEventListener('change', function () {
        halaman = 1;
        renderTabel();
    });

    tabel.querySelectorAll('th[data-kolom]').forEach(function (th) {
        th.style.cursor = 'pointer';
        th.title = 'Klik untuk mengurutkan';

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
