
<?php
include 'includes/cek_session.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'config/koneksi.php';

$role = $_SESSION['role'] ?? '';

if (!in_array($role, ['admin', 'guru', 'wali_kelas'], true)) {
    header('Location: dashboard.php');
    exit;
}

// ========================================
// IDENTITAS AKUN LOGIN
// ========================================

$email = trim($_SESSION['email'] ?? '');
$guru_id = 0;

if ($role === 'guru' || $role === 'wali_kelas') {

    if ($email === '') {
        die('Email akun tidak ditemukan. Silakan login ulang.');
    }

    $stmtGuru = mysqli_prepare(
        $koneksi,
        "SELECT id FROM t_guru WHERE email = ? LIMIT 1"
    );

    if (!$stmtGuru) {
        die('Gagal memeriksa akun guru: ' . mysqli_error($koneksi));
    }

    mysqli_stmt_bind_param($stmtGuru, 's', $email);
    mysqli_stmt_execute($stmtGuru);

    $resultGuru = mysqli_stmt_get_result($stmtGuru);
    $dataGuru = mysqli_fetch_assoc($resultGuru);

    mysqli_stmt_close($stmtGuru);

    if (!$dataGuru) {
        die('Data guru tidak ditemukan. Pastikan email akun sama dengan email pada data guru.');
    }

    $guru_id = (int)$dataGuru['id'];
}

// ========================================
// QUERY RIWAYAT PELANGGARAN
// ========================================

$sql = "
    SELECT
        ps.id,
        ps.siswa_id,
        ps.nama_siswa,
        ps.nama_kelas,
        ps.nama_pelanggaran,
        ps.poin,
        ps.tanggal,
        ps.nama_guru,
        ps.status,
        ps.tindakan,
        ps.tahun_ajaran_id,
        ta.nama AS tahun_ajaran
    FROM t_pelanggaran_siswa ps
    LEFT JOIN t_tahun_ajaran ta
        ON ta.id = ps.tahun_ajaran_id
";

if ($role === 'guru') {

    // Guru hanya melihat laporan miliknya sendiri
    $sql .= "
        WHERE ps.guru_id = ?
    ";

} elseif ($role === 'wali_kelas') {

    // Wali kelas hanya melihat laporan miliknya sendiri
    // untuk siswa yang berada di kelas tanggung jawabnya
    $sql .= "
        WHERE ps.guru_id = ?
        AND EXISTS (
            SELECT 1
            FROM t_wali_kelas wk
            WHERE wk.guru_id = ?
              AND wk.kelas_id = ps.kelas_id
              AND wk.tahun_ajaran_id = ps.tahun_ajaran_id
              AND wk.status_aktif = 1
        )
    ";
}

$sql .= "
    ORDER BY ps.tanggal DESC, ps.id DESC
";

$stmt = mysqli_prepare($koneksi, $sql);

if (!$stmt) {
    die('Gagal menyiapkan query: ' . mysqli_error($koneksi));
}

if ($role === 'guru') {

    mysqli_stmt_bind_param($stmt, 'i', $guru_id);

} elseif ($role === 'wali_kelas') {

    mysqli_stmt_bind_param($stmt, 'ii', $guru_id, $guru_id);
}

mysqli_stmt_execute($stmt);

$query = mysqli_stmt_get_result($stmt);

if (!$query) {
    die('Gagal mengambil riwayat: ' . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Riwayat Pelanggaran</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

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
            font-size: 13px;
        }

        #tabelRiwayat th {
            background: #212529;
            color: white;
            white-space: nowrap;
            cursor: pointer;
        }

        #tabelRiwayat th,
        #tabelRiwayat td {
            padding: 9px 10px;
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

        .pagination-area button:hover,
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

    <div class="mb-3">

        <h2>
            <i class="fas fa-history"></i>
            Riwayat Pelanggaran
        </h2>

        <p class="text-muted">
            Daftar catatan pelanggaran siswa berdasarkan hak akses akun.
        </p>

        <?php if ($role !== 'admin'): ?>
            <div class="alert alert-info py-2">
                <i class="fas fa-user-shield"></i>
                Login sebagai:
                <strong>
                    <?= htmlspecialchars($_SESSION['name'] ?? $role); ?>
                </strong>
                (<?= htmlspecialchars($role); ?>)
            </div>
        <?php endif; ?>

    </div>

    <div class="card shadow-sm">
        <div class="card-body">

            <!-- KONTROL TABEL -->
            <div class="kontrol-tabel">

                <div>
                    <label for="jumlahBaris">
                        Tampilkan:
                    </label>

                    <select id="jumlahBaris" class="form-control d-inline-block"
                            style="width: auto;">

                        <option value="5">5</option>
                        <option value="10" selected>10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="all">Semua</option>

                    </select>
                </div>

                <div>
                    <input
                        type="search"
                        id="cariTabel"
                        class="form-control"
                        placeholder="Cari nama, kelas, pelanggaran..."
                    >
                </div>

            </div>

            <!-- TABEL RIWAYAT -->
            <div class="tabel-wrapper">

                <table id="tabelRiwayat"
                       class="table table-bordered table-striped table-hover">

                    <thead>
                        <tr>
                            <th data-sort="number">ID ↕</th>
                            <th data-sort="text">Nama Siswa ↕</th>
                            <th data-sort="text">Kelas ↕</th>
                            <th data-sort="text">Pelanggaran ↕</th>
                            <th data-sort="number">Poin ↕</th>
                            <th data-sort="text">Tanggal ↕</th>
                            <th data-sort="text">Status ↕</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php if (mysqli_num_rows($query) > 0): ?>

                        <?php while ($row = mysqli_fetch_assoc($query)): ?>

                            <?php
                            $status = strtolower(
                                trim($row['status'] ?? '')
                            );

                            $sudahDitindaklanjuti =
                                ($status === 'sudah ditindaklanjuti');
                            ?>

                            <tr>

                                <td>
                                    <?= (int)$row['id']; ?>
                                </td>

                                <td>
                                    <strong>
                                        <?= htmlspecialchars($row['nama_siswa'] ?? '-'); ?>
                                    </strong>
                                </td>

                                <td>
                                    <?= htmlspecialchars($row['nama_kelas'] ?? '-'); ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($row['nama_pelanggaran'] ?? '-'); ?>
                                </td>

                                <td>
                                    <strong class="text-primary">
                                        <?= (int)$row['poin']; ?> poin
                                    </strong>
                                </td>

                                <td>
                                    <?= htmlspecialchars($row['tanggal'] ?? '-'); ?>
                                </td>

                                <td>

                                    <?php if ($sudahDitindaklanjuti): ?>

                                        <span class="badge bg-success">
                                            Sudah Ditindaklanjuti
                                        </span>

                                    <?php else: ?>

                                        <span class="badge bg-warning text-dark">
                                            Belum Ditindaklanjuti
                                        </span>

                                    <?php endif; ?>

                                </td>

                            </tr>

                        <?php endwhile; ?>

                    <?php else: ?>

                        <tr class="baris-kosong">
                            <td colspan="7" class="text-center">
                                Belum ada riwayat pelanggaran.
                            </td>
                        </tr>

                    <?php endif; ?>

                    </tbody>

                </table>

            </div>

            <p id="infoTabel" class="mt-3 text-muted"></p>

            <div id="paginationTabel" class="pagination-area"></div>

            <!-- TOMBOL -->
            <div class="tombol-aksi mt-3">

                <a href="dashboard.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                    Kembali
                </a>

                <button
                    type="button"
                    onclick="window.print()"
                    class="btn btn-primary">

                    <i class="fas fa-print"></i>
                    Cetak

                </button>

            </div>

        </div>
    </div>

</main>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const tabel = document.getElementById('tabelRiwayat');

    if (!tabel) return;

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
                sel.colSpan = 7;

                barisKosong.appendChild(sel);
                tbody.appendChild(barisKosong);
            }

            barisKosong.style.display = '';

            barisKosong.querySelector('td').textContent =
                semuaBaris.length === 0
                    ? 'Belum ada riwayat pelanggaran.'
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

    // PENGURUTAN KOLOM
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

                    const angkaA = parseInt(teksA.replace(/[^\d-]/g, ''), 10) || 0;
                    const angkaB = parseInt(teksB.replace(/[^\d-]/g, ''), 10) || 0;

                    return (angkaA - angkaB) * arahUrut;
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

            td.colSpan = 7;
            td.className = 'text-center';
            td.textContent = 'Belum ada riwayat pelanggaran.';

            tr.appendChild(td);
            tbody.appendChild(tr);

            return;
        }

        semuaBaris.forEach(function (tr) {
            tbody.appendChild(tr);
        });
    });

    window.addEventListener('afterprint', function () {

        if (!kondisiCetak) return;

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

<?php
mysqli_stmt_close($stmt);
?>
