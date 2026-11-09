
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

// KHUSUS ADMIN
$role = $_SESSION['role'] ?? '';

if ($role !== 'admin') {
    header('Location: dashboard.php');
    exit;
}

// AMBIL DATA JENIS PELANGGARAN
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

    <!-- BOOTSTRAP 4.1.3 -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">

    <!-- FONT AWESOME -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #f8f9fa;
            font-family: Arial, sans-serif;
            color: #343a40;
        }

        .konten {
            margin-left: 273px;
            padding: 28px;
            min-height: 100vh;
        }

        .judul {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .subjudul {
            color: #6c757d;
            font-size: 14px;
            margin-bottom: 25px;
        }

        .card-custom {
            background: #fff;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            margin-bottom: 22px;
            overflow: hidden;
        }

        .card-header-custom {
            background: #fff;
            padding: 17px 20px;
            border-bottom: 1px solid #dee2e6;
            font-weight: bold;
            font-size: 16px;
        }

        .card-body {
            padding: 20px;
        }

        .table-wrapper {
            width: 100%;
            overflow-x: auto;
        }

        .table {
            margin-bottom: 0;
            font-size: 14px;
            vertical-align: middle;
        }

        .table th,
        .table td {
            padding: 12px;
            vertical-align: middle;
            white-space: nowrap;
        }

        .table th {
            cursor: pointer;
        }

        .table th.no-sort {
            cursor: default;
        }

        .kolom-keterangan {
            min-width: 200px;
            white-space: normal !important;
        }

        .kolom-aksi {
            width: 150px;
            min-width: 150px;
            text-align: center;
        }

        .aksi-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 6px;
        }

        .btn-aksi {
            width: 62px;
            height: 32px;
            padding: 0;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            font-size: 12px;
            border-radius: 4px;
            white-space: nowrap;
        }

        .table-controls {
            display: flex;
            justify-content: space-between;
            align-items: end;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 18px;
        }

        .table-controls label {
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 6px;
        }

        .table-controls select,
        .table-controls input {
            height: 36px;
            font-size: 13px;
        }

        .table-controls input {
            width: 260px;
            max-width: 100%;
        }

        .pagination-container {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 5px;
            margin-top: 18px;
        }

        .pagination-container button {
            background: #fff;
            color: #007bff;
            border: 1px solid #dee2e6;
            padding: 7px 12px;
            border-radius: 4px;
            font-size: 13px;
        }

        .pagination-container button.active {
            background: #007bff;
            color: white;
            border-color: #007bff;
        }

        .pagination-container button:disabled {
            color: #999;
            cursor: not-allowed;
        }

        .table-info {
            margin-top: 15px;
            color: #6c757d;
            font-size: 13px;
        }

        .kosong {
            text-align: center;
            color: #777;
            padding: 25px !important;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 13px;
            color: #6c757d;
        }

        @media (max-width: 767px) {
            .konten {
                margin-left: 0;
                padding: 18px;
            }

            .table-controls {
                align-items: stretch;
                flex-direction: column;
            }

            .table-controls input {
                width: 100%;
            }
        }
    </style>

</head>

<body>

    <!-- SIDEBAR -->
    <?php include 'includes/sidebar.php'; ?>

    <!-- KONTEN -->
    <main class="konten">

        <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">

            <div>
                <h2 class="judul">Kelola Jenis Pelanggaran</h2>

                <p class="subjudul">
                    Kelola daftar jenis pelanggaran dan poin pelanggaran siswa.
                </p>
            </div>

            <div class="mb-3">

                <a href="tambah_jenis_pelanggaran.php"
                   class="btn btn-primary">

                    <i class="fas fa-plus mr-1"></i>
                    Tambah Jenis Pelanggaran

                </a>

            </div>

        </div>

        <!-- PESAN -->
        <?php if (isset($_SESSION['pesan'])) { ?>

            <div class="alert alert-success alert-dismissible fade show">

                <i class="fas fa-check-circle mr-1"></i>

                <?= htmlspecialchars($_SESSION['pesan'], ENT_QUOTES, 'UTF-8'); ?>

                <button type="button" class="close" data-dismiss="alert">
                    <span>&times;</span>
                </button>

            </div>

            <?php unset($_SESSION['pesan']); ?>

        <?php } ?>

        <!-- TABEL -->
        <div class="card-custom">

            <div class="card-header-custom">
                <i class="fas fa-list mr-2 text-primary"></i>
                Daftar Jenis Pelanggaran
            </div>

            <div class="card-body">

                <!-- KONTROL TABEL -->
                <div class="table-controls">

                    <div>
                        <label for="jumlahBaris">Tampilkan data</label>

                        <select id="jumlahBaris" class="form-control">
                            <option value="5">5</option>
                            <option value="10" selected>10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="all">Semua</option>
                        </select>
                    </div>

                    <div>
                        <label for="cariTabel">Cari data</label>

                        <input
                            type="search"
                            id="cariTabel"
                            class="form-control"
                            placeholder="Cari jenis pelanggaran..."
                        >
                    </div>

                </div>

                <!-- TABEL DATA -->
                <div class="table-wrapper">

                    <table class="table table-bordered table-hover"
                           id="tabelPelanggaran">

                        <thead class="thead-dark">

                            <tr>
                                <th data-sort="number">ID ↕</th>
                                <th data-sort="text">Nama Pelanggaran ↕</th>
                                <th data-sort="number">Poin ↕</th>
                                <th data-sort="text" class="kolom-keterangan">
                                    Keterangan ↕
                                </th>
                                <th class="no-sort kolom-aksi">Aksi</th>
                            </tr>

                        </thead>

                        <tbody>

                            <?php if (mysqli_num_rows($result) > 0) { ?>

                                <?php $no = 1; ?>

                                <?php while ($row = mysqli_fetch_assoc($result)) { ?>

                                    <tr>

                                        <td><?= $no++; ?></td>

                                        <td>
                                            <?= htmlspecialchars(
                                                $row['nama'] ?? '-',
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ); ?>
                                        </td>

                                        <td>
                                            <?= (int) $row['poin']; ?>
                                        </td>

                                        <td class="kolom-keterangan">
                                            <?= htmlspecialchars(
                                                $row['deskripsi'] ?? '-',
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ); ?>
                                        </td>

                                        <td class="kolom-aksi">

                                            <div class="aksi-wrapper">

                                                <a
                                                    href="edit_jenis_pelanggaran.php?id=<?= (int) $row['id']; ?>"
                                                    class="btn btn-primary btn-aksi"
                                                >
                                                    <i class="fas fa-edit"></i>
                                                    Edit
                                                </a>

                                                <a
                                                    href="proses_jenis_pelanggaran.php?aksi=hapus&id=<?= (int) $row['id']; ?>"
                                                    class="btn btn-danger btn-aksi"
                                                    onclick="return confirm('Yakin ingin menghapus jenis pelanggaran ini?')"
                                                >
                                                    <i class="fas fa-trash"></i>
                                                    Hapus
                                                </a>

                                            </div>

                                        </td>

                                    </tr>

                                <?php } ?>

                            <?php } else { ?>

                                <tr class="baris-kosong">

                                    <td colspan="5" class="kosong">
                                        Belum ada data jenis pelanggaran.
                                    </td>

                                </tr>

                            <?php } ?>

                        </tbody>

                    </table>

                </div>

                <div class="table-info" id="infoTabel"></div>

                <div class="pagination-container" id="paginationTabel"></div>

            </div>

        </div>

        <div class="footer">
            © <?= date('Y'); ?> Sistem Informasi Pelanggaran Siswa
        </div>

    </main>

    <!-- JAVASCRIPT -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>

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
