
<?php

include 'includes/cek_session.php';
include 'config/koneksi.php';

// Ambil data guru beserta email akun user
$sql = "SELECT
            t_guru.*,
            t_users.email AS email_user
        FROM t_guru
        LEFT JOIN t_users ON t_guru.user_id = t_users.id
        ORDER BY t_guru.id DESC";

$query = mysqli_query($koneksi, $sql);

if (!$query) {
    die("Query data guru gagal: " . mysqli_error($koneksi));
}

// Ambil akun yang role-nya guru
$sql_user = "SELECT id, name, email
             FROM t_users
             WHERE role = 'guru'
             ORDER BY name ASC";

$query_user = mysqli_query($koneksi, $sql_user);

if (!$query_user) {
    die("Query akun guru gagal: " . mysqli_error($koneksi));
}

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Guru</title>

    <!-- Bootstrap 4.1.3 -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">

    <style>
        body {
            background-color: #f8f9fa;
            margin: 0;
        }

        /* SIDEBAR TETAP DI KIRI */
        .sidebar-guru {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: 16.666667%;
            height: 100vh;
            overflow-y: auto;
            overflow-x: hidden;
            z-index: 1030;
        }

        /* KONTEN DI SAMPING SIDEBAR */
        .konten-guru {
            margin-left: 16.666667%;
            width: 83.333333%;
            min-height: 100vh;
            box-sizing: border-box;
        }

        .tabel-guru-wrapper {
            width: 100%;
            overflow-x: auto;
        }

        #tabelGuru {
            width: 100%;
            border-collapse: collapse;
        }

        #tabelGuru th,
        #tabelGuru td {
            padding: 10px;
            border: 1px solid #dee2e6;
            vertical-align: middle;
        }

        #tabelGuru th {
            background: #212529;
            color: white;
            white-space: nowrap;
            cursor: pointer;
        }

        .kontrol-tabel {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 12px;
        }

        .kontrol-tabel input,
        .kontrol-tabel select {
            padding: 7px;
            border: 1px solid #ced4da;
            border-radius: 5px;
        }

        .paginasi {
            display: flex;
            gap: 5px;
            margin-top: 12px;
            flex-wrap: wrap;
        }

        .paginasi button {
            padding: 6px 12px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            background: white;
            cursor: pointer;
        }

        .paginasi button.aktif {
            background: #007bff;
            color: white;
        }

        .paginasi button:disabled {
            cursor: not-allowed;
            opacity: 0.5;
        }

        /* HP DAN LAYAR KECIL */
        @media (max-width: 767.98px) {
            .sidebar-guru {
                position: relative;
                width: 100%;
                height: auto;
                max-height: none;
                bottom: auto;
            }

            .konten-guru {
                margin-left: 0;
                width: 100%;
                padding: 15px !important;
            }
        }
    </style>
</head>

<body>

<div class="container-fluid">
    <div class="row">

        <!-- SIDEBAR -->
        <div class="sidebar-guru bg-dark p-3">
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
                    <a href="kelola_guru.php" class="nav-link active">
                        Data Guru
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="kelola_kelas.php" class="nav-link text-white">
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

                <hr class="text-secondary">

                <li class="nav-item">
                    <a href="logout.php" class="nav-link text-danger">
                        Logout
                    </a>
                </li>

            </ul>
        </div>

        <!-- KONTEN -->
        <main class="konten-guru p-4">

            <h1>Kelola Guru</h1>

            <?php if (isset($_SESSION['pesan'])) { ?>
                <div class="alert alert-info">
                    <?= htmlspecialchars($_SESSION['pesan']); ?>
                </div>
                <?php unset($_SESSION['pesan']); ?>
            <?php } ?>

            <hr>

            <h2>Tambah Data Guru</h2>

            <form action="proses_guru.php" method="POST">

                <input type="hidden" name="aksi" value="tambah">

                <div class="table-responsive">
                    <table class="table table-bordered bg-white">

                        <tr>
                            <td>NIP</td>
                            <td>:</td>
                            <td>
                                <input type="text"
                                       name="nip"
                                       class="form-control"
                                       required>
                            </td>
                        </tr>

                        <tr>
                            <td>Nama Guru</td>
                            <td>:</td>
                            <td>
                                <input type="text"
                                       name="nama"
                                       class="form-control"
                                       required>
                            </td>
                        </tr>

                        <tr>
                            <td>Email</td>
                            <td>:</td>
                            <td>
                                <input type="email"
                                       name="email"
                                       class="form-control"
                                       required>
                            </td>
                        </tr>

                        <tr>
                            <td>Akun Guru</td>
                            <td>:</td>
                            <td>
                                <select name="user_id"
                                        class="form-control"
                                        required>

                                    <option value="">
                                        -- Pilih Akun Guru --
                                    </option>

                                    <?php while ($user = mysqli_fetch_assoc($query_user)) { ?>
                                        <option value="<?= (int) $user['id']; ?>">
                                            <?= htmlspecialchars($user['name']); ?>
                                            -
                                            <?= htmlspecialchars($user['email']); ?>
                                        </option>
                                    <?php } ?>

                                </select>
                            </td>
                        </tr>

                        <tr>
                            <td>Status Aktif</td>
                            <td>:</td>
                            <td>
                                <select name="status_aktif"
                                        class="form-control"
                                        required>
                                    <option value="1">Aktif</option>
                                    <option value="0">Tidak Aktif</option>
                                </select>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="3">
                                <button type="submit" class="btn btn-success">
                                    Tambah Guru
                                </button>
                            </td>
                        </tr>

                    </table>
                </div>

                <a href="dashboard.php" class="btn btn-secondary">
                    Kembali ke Dashboard
                </a>

            </form>

            <hr>

            <h2>Data Guru</h2>

            <div class="kontrol-tabel">
                <input type="search"
                       id="cariGuru"
                       class="form-control"
                       style="max-width: 300px;"
                       placeholder="Cari NIP, nama, atau email...">

                <label class="mb-0">
                    Tampilkan
                    <select id="jumlahGuru">
                        <option value="5">5</option>
                        <option value="10" selected>10</option>
                        <option value="25">25</option>
                        <option value="all">Semua</option>
                    </select>
                    data
                </label>
            </div>

            <div class="tabel-guru-wrapper">
                <table class="table table-striped table-hover bg-white"
                       id="tabelGuru">

                    <thead>
                        <tr>
                            <th data-kolom="0">ID</th>
                            <th data-kolom="1">NIP</th>
                            <th data-kolom="2">Nama</th>
                            <th data-kolom="3">Email</th>
                            <th data-kolom="4">Status</th>
                            <th data-kolom="5">User ID</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (mysqli_num_rows($query) > 0) { ?>

                            <?php while ($guru = mysqli_fetch_assoc($query)) { ?>
                                <tr>
                                    <td>
                                        <?= (int) $guru['id']; ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($guru['nip'] ?? ''); ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($guru['nama'] ?? ''); ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars(
                                            $guru['email_user'] ?? $guru['email'] ?? '-'
                                        ); ?>
                                    </td>

                                    <td>
                                        <?php if ($guru['status_aktif'] == 1) { ?>
                                            Aktif
                                        <?php } else { ?>
                                            Tidak Aktif
                                        <?php } ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($guru['user_id'] ?? '-'); ?>
                                    </td>

                                    <td>
                                        <a href="edit_guru.php?id=<?= (int) $guru['id']; ?>"
                                           class="btn btn-primary btn-sm">
                                            Edit
                                        </a>

                                        <a href="proses_guru.php?aksi=hapus&id=<?= (int) $guru['id']; ?>"
                                           class="btn btn-danger btn-sm"
                                           onclick="return confirm('Yakin ingin menghapus data guru ini?');">
                                            hapus
                                        </a>
                                    </td>
                                </tr>
                            <?php } ?>

                        <?php } else { ?>
                            <tr>
                                <td colspan="7" class="text-center">
                                    Belum ada data guru.
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>

                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center flex-wrap mt-3">
                <small id="infoGuru" class="text-muted"></small>
                <div class="paginasi" id="paginasiGuru"></div>
            </div>

        </main>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const tabel = document.getElementById('tabelGuru');
    const tbody = tabel.querySelector('tbody');
    const pencarian = document.getElementById('cariGuru');
    const pilihanJumlah = document.getElementById('jumlahGuru');
    const info = document.getElementById('infoGuru');
    const paginasi = document.getElementById('paginasiGuru');

    const barisAsli = Array.from(tbody.querySelectorAll('tr'))
        .filter(function (tr) {
            return tr.cells.length === 7;
        });

    let hasil = barisAsli.slice();
    let halaman = 1;
    let kolomUrut = -1;
    let arah = 1;

    function render() {
        const kata = pencarian.value.toLowerCase().trim();

        hasil = barisAsli.filter(function (tr) {
            return tr.innerText.toLowerCase().includes(kata);
        });

        if (kolomUrut >= 0) {
            hasil.sort(function (a, b) {
                const x = a.cells[kolomUrut].innerText.trim();
                const y = b.cells[kolomUrut].innerText.trim();

                return x.localeCompare(y, 'id', {
                    numeric: true,
                    sensitivity: 'base'
                }) * arah;
            });
        }

        const jumlah = pilihanJumlah.value === 'all'
            ? Math.max(hasil.length, 1)
            : Number(pilihanJumlah.value);

        const totalHalaman = Math.max(
            1,
            Math.ceil(hasil.length / jumlah)
        );

        halaman = Math.min(halaman, totalHalaman);

        const awal = (halaman - 1) * jumlah;
        const dataTampil = hasil.slice(awal, awal + jumlah);

        tbody.replaceChildren();

        if (dataTampil.length > 0) {
            dataTampil.forEach(function (tr) {
                tbody.appendChild(tr);
            });
        } else {
            const tr = document.createElement('tr');
            const td = document.createElement('td');

            td.colSpan = 7;
            td.className = 'text-center';
            td.textContent = 'Data guru tidak ditemukan.';

            tr.appendChild(td);
            tbody.appendChild(tr);
        }

        if (hasil.length > 0) {
            info.textContent =
                'Menampilkan ' + (awal + 1) + '–' +
                Math.min(awal + dataTampil.length, hasil.length) +
                ' dari ' + hasil.length + ' data guru';
        } else {
            info.textContent = 'Tidak ada data guru yang cocok.';
        }

        renderPaginasi(totalHalaman);
    }

    function renderPaginasi(totalHalaman) {
        paginasi.replaceChildren();

        function buatTombol(teks, tujuan, nonaktif, aktif) {
            const tombol = document.createElement('button');

            tombol.type = 'button';
            tombol.textContent = teks;
            tombol.disabled = nonaktif;

            if (aktif) {
                tombol.classList.add('aktif');
            }

            tombol.addEventListener('click', function () {
                halaman = tujuan;
                render();
            });

            paginasi.appendChild(tombol);
        }

        buatTombol('‹', halaman - 1, halaman <= 1, false);

        for (let i = 1; i <= totalHalaman; i++) {
            buatTombol(String(i), i, false, i === halaman);
        }

        buatTombol('›', halaman + 1, halaman >= totalHalaman, false);
    }

    pencarian.addEventListener('input', function () {
        halaman = 1;
        render();
    });

    pilihanJumlah.addEventListener('change', function () {
        halaman = 1;
        render();
    });

    tabel.querySelectorAll('thead th[data-kolom]').forEach(function (th) {
        th.addEventListener('click', function () {
            const kolom = Number(th.dataset.kolom);

            if (kolomUrut === kolom) {
                arah *= -1;
            } else {
                kolomUrut = kolom;
                arah = 1;
            }

            halaman = 1;
            render();
        });
    });

    render();
});
</script>

</body>
</html>
