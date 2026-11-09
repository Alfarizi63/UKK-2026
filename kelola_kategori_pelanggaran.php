
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

// Khusus admin
if (($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: dashboard.php');
    exit;
}

// Ambil data kategori pelanggaran berdasarkan ID terkecil
$sql = "SELECT * FROM t_pelanggaran_kategori ORDER BY id ASC";
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

        .kontrol-tabel {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
            margin: 20px 0 15px;
        }

        .kontrol-tabel input {
            max-width: 350px;
        }

        .tabel-wrapper {
            width: 100%;
            overflow-x: auto;
            background: white;
            border-radius: 6px;
        }

        #tabelKategori {
            width: 100%;
            margin-bottom: 0;
        }

        #tabelKategori th,
        #tabelKategori td {
            padding: 10px;
            vertical-align: middle;
        }

        #tabelKategori th {
            background: #212529;
            color: white;
            white-space: nowrap;
            cursor: pointer;
        }

        /* Kolom nomor tidak perlu bisa diurutkan */
        #tabelKategori th:first-child,
        #tabelKategori th:last-child {
            cursor: default;
        }

        .paginasi {
            display: flex;
            gap: 5px;
            flex-wrap: wrap;
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

    <div class="card border-0 shadow-sm">
        <div class="card-body">

            <h1>Kategori Pelanggaran</h1>

            <p class="text-secondary">
                Halaman untuk mengelola kategori pelanggaran siswa.
            </p>

            <a href="dashboard.php" class="btn btn-secondary btn-sm">
                Kembali ke Dashboard
            </a>

            <a href="tambah_kategori_pelanggaran.php"
               class="btn btn-primary btn-sm">
                + Tambah Kategori
            </a>

            <hr>

            <h2>Data Kategori Pelanggaran</h2>

            <!-- PENCARIAN DAN JUMLAH DATA -->
            <div class="kontrol-tabel">

                <input type="search"
                       id="cariKategori"
                       class="form-control"
                       placeholder="Cari nama kategori atau deskripsi...">

                <label class="mb-0">
                    Tampilkan

                    <select id="jumlahKategori"
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

            <!-- TABEL KATEGORI -->
            <div class="tabel-wrapper">

                <table class="table table-bordered table-striped table-hover"
                       id="tabelKategori">

                    <thead>
                        <tr>
                            <th>No</th>
                            <th data-kolom="1">Nama Kategori</th>
                            <th data-kolom="2">Deskripsi</th>
                            <th data-kolom="3">Status Aktif</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody id="isiKategori">

                    <?php if (mysqli_num_rows($query) > 0): ?>

                        <?php $no = 1; ?>

                        <?php while ($data = mysqli_fetch_assoc($query)): ?>

                            <tr>

                                <!-- NOMOR URUT OTOMATIS -->
                                <td>
                                    <?= $no++; ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($data['nama'] ?? '') ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($data['deskripsi'] ?? '') ?>
                                </td>

                                <td>
                                    <?php if (!empty($data['status_aktif'])): ?>
                                        <span class="badge bg-success">
                                            Aktif
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">
                                            Tidak Aktif
                                        </span>
                                    <?php endif; ?>
                                </td>

                                <td class="text-nowrap">

                                    <!-- ID ASLI TETAP DIPAKAI UNTUK EDIT -->
                                    <a href="edit_kategori_pelanggaran.php?id=<?= (int)$data['id'] ?>"
                                       class="btn btn-primary btn-sm">
                                        Edit
                                    </a>

                                    <!-- ID ASLI TETAP DIPAKAI UNTUK HAPUS -->
                                    <form action="hapus_kategori_pelanggaran.php"
                                          method="POST"
                                          style="display:inline"
                                          onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">

                                        <input type="hidden"
                                               name="id"
                                               value="<?= (int)$data['id'] ?>">

                                        <button type="submit"
                                                class="btn btn-danger btn-sm">
                                            Hapus
                                        </button>

                                    </form>

                                </td>

                            </tr>

                        <?php endwhile; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="5" class="text-center">
                                Belum ada data kategori pelanggaran.
                            </td>
                        </tr>

                    <?php endif; ?>

                    </tbody>

                </table>

            </div>

            <div class="d-flex justify-content-between align-items-center flex-wrap mt-3">

                <p id="infoKategori" class="text-muted"></p>

                <div id="navigasiKategori" class="paginasi"></div>

            </div>

        </div>
    </div>

</main>

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

        // PENCARIAN DATA
        let hasil = semuaBaris.filter(tr =>
            tr.innerText.toLowerCase().includes(kata)
        );

        // PENGURUTAN DATA
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

        const tampil = hasil.slice(
            awal,
            awal + perHalaman
        );

        tbody.replaceChildren();

        if (tampil.length > 0) {

            tampil.forEach((baris, index) => {

                // NOMOR OTOMATIS SESUAI HASIL DAN HALAMAN
                baris.cells[0].textContent = awal + index + 1;

                tbody.appendChild(baris);

            });

        } else {

            const tr = document.createElement('tr');
            const td = document.createElement('td');

            td.colSpan = 5;
            td.className = 'text-center';
            td.textContent = 'Data kategori tidak ditemukan.';

            tr.appendChild(td);
            tbody.appendChild(tr);

        }

        // INFORMASI DATA
        info.textContent = hasil.length > 0
            ? `Menampilkan ${awal + 1}–${Math.min(
                awal + tampil.length,
                hasil.length
              )} dari ${hasil.length} data kategori`
            : 'Tidak ada data kategori yang cocok.';

        // PAGINASI
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

        buatTombol(
            '›',
            halaman + 1,
            halaman === totalHalaman,
            false
        );

    }

    // PENCARIAN
    inputCari.addEventListener('input', function () {
        halaman = 1;
        renderTabel();
    });

    // JUMLAH DATA PER HALAMAN
    inputJumlah.addEventListener('change', function () {
        halaman = 1;
        renderTabel();
    });

    // PENGURUTAN KOLOM
    tabel.querySelectorAll('thead th[data-kolom]').forEach(th => {

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

    // TAMPILKAN DATA PERTAMA KALI
    renderTabel();

});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
