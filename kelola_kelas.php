
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

// Khusus admin
if (($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: dashboard.php');
    exit;
}

// Ambil data kelas berdasarkan tingkat, jurusan, dan nama
$query = mysqli_query($koneksi, "
    SELECT *
    FROM t_kelas
    ORDER BY
        FIELD(tingkat, 'X', 'XI', 'XII'),
        jurusan ASC,
        nama ASC
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

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f8f9fa;
            margin: 0;
        }

        .main-content {
            margin-left: 250px;
            padding: 25px;
            min-width: 0;
        }

        .tabel-kelas-wrapper {
            width: 100%;
            overflow-x: auto;
            background: white;
            border-radius: 6px;
        }

        #tabelKelas {
            width: 100%;
            margin-bottom: 0;
        }

        #tabelKelas th,
        #tabelKelas td {
            padding: 10px;
            vertical-align: middle;
        }

        #tabelKelas th {
            background: #212529;
            color: white;
            white-space: nowrap;
            cursor: pointer;
        }

        .kontrol-tabel {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 15px;
        }

        .kontrol-tabel input {
            max-width: 320px;
        }

        .paginasi {
            display: flex;
            gap: 5px;
            flex-wrap: wrap;
            margin-bottom: 15px;
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

    <h1 class="mb-2">Kelola Kelas</h1>

    <p class="text-secondary">
        Halaman untuk mengelola data kelas.
    </p>

    <a href="dashboard.php" class="btn btn-secondary btn-sm mb-3">
        Kembali ke Dashboard
    </a>

    <a href="tambah_kelas.php" class="btn btn-primary btn-sm mb-3">
        Tambah Kelas
    </a>

    <hr>

    <h2>Data Kelas</h2>

    <div class="kontrol-tabel">

        <input type="search"
               id="cariKelas"
               placeholder="Cari nama kelas atau jurusan..."
               class="form-control">

        <label class="mb-0">
            Tampilkan

            <select id="jumlahKelas"
                    class="form-control"
                    style="display:inline-block;width:auto;">
                <option value="5">5</option>
                <option value="10" selected>10</option>
                <option value="25">25</option>
                <option value="all">Semua</option>
            </select>

            data
        </label>

    </div>

    <div class="tabel-kelas-wrapper">

        <table id="tabelKelas"
               class="table table-bordered table-striped table-hover">

            <thead>
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

                    <td><?= $no++; ?></td>

                    <td>
                        <?= htmlspecialchars($kelas['nama'] ?? ''); ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($kelas['tingkat'] ?? ''); ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($kelas['jurusan'] ?? ''); ?>
                    </td>

                    <td>
                        <?php if (($kelas['status_aktif'] ?? 0) == 1): ?>
                            <span class="badge bg-success">Aktif</span>
                        <?php else: ?>
                            <span class="badge bg-secondary">Tidak Aktif</span>
                        <?php endif; ?>
                    </td>

                    <td class="text-nowrap">

                        <a href="edit_kelas.php?id=<?= (int)$kelas['id']; ?>"
                           class="btn btn-primary btn-sm">
                            Edit
                        </a>

                        <a href="hapus_kelas.php?id=<?= (int)$kelas['id']; ?>"
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

    <div class="d-flex justify-content-between align-items-center flex-wrap mt-3">

        <p id="infoData" class="text-muted"></p>

        <div id="navigasi" class="paginasi"></div>

    </div>

</main>

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

        const tampil = hasil.slice(
            awal,
            awal + perHalaman
        );

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
            td.className = 'text-center';
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
                ? 'btn btn-primary btn-sm'
                : 'btn btn-outline-primary btn-sm';

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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
