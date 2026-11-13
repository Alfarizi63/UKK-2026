
<?php

include 'includes/cek_session.php';
include 'config/koneksi.php';

$role = $_SESSION['role'] ?? '';

if (!in_array($role, ['admin', 'guru', 'wali_kelas'], true)) {
    header('Location: dashboard.php');
    exit;
}

$is_admin = ($role === 'admin');
$is_guru = in_array($role, ['guru', 'wali_kelas'], true);

$tahun_ajaran_id = (int)($_GET['tahun_ajaran_id'] ?? 0);
$kelas_id = (int)($_GET['kelas_id'] ?? 0);

$guru_id = 0;
$kelas_wali_id = 0;
$tahun_ajaran_aktif_id = 0;
$nama_kelas_wali = '';
$nama_tahun_aktif = '';
$akses_wali_kelas = false;

// ========================================
// TAHUN AJARAN AKTIF
// ========================================

$query_tahun_aktif = mysqli_query($koneksi, "
    SELECT id, nama
    FROM t_tahun_ajaran
    WHERE status_aktif = 1
    ORDER BY id DESC
    LIMIT 1
");

if (!$query_tahun_aktif) {
    die(mysqli_error($koneksi));
}

$data_tahun_aktif = mysqli_fetch_assoc($query_tahun_aktif);

if ($data_tahun_aktif) {
    $tahun_ajaran_aktif_id = (int)$data_tahun_aktif['id'];
    $nama_tahun_aktif = $data_tahun_aktif['nama'];
}

// ========================================
// IDENTITAS GURU LOGIN
// ========================================

if ($is_guru) {

    $user_id = (int)($_SESSION['id'] ?? 0);

    $stmt = mysqli_prepare($koneksi, "
        SELECT id
        FROM t_guru
        WHERE user_id = ?
          AND status_aktif = 1
        LIMIT 1
    ");

    if (!$stmt) {
        die(mysqli_error($koneksi));
    }

    mysqli_stmt_bind_param($stmt, 'i', $user_id);
    mysqli_stmt_execute($stmt);

    $data_guru = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

    mysqli_stmt_close($stmt);

    $guru_id = (int)($data_guru['id'] ?? 0);

    // ========================================
    // CEK WALI KELAS
    // ========================================

    if ($guru_id > 0 && $tahun_ajaran_aktif_id > 0) {

        $stmt = mysqli_prepare($koneksi, "
            SELECT
                wk.kelas_id,
                k.nama AS nama_kelas
            FROM t_wali_kelas wk
            INNER JOIN t_kelas k ON k.id = wk.kelas_id
            INNER JOIN t_tahun_ajaran ta
                ON ta.id = wk.tahun_ajaran_id
            WHERE wk.guru_id = ?
              AND wk.tahun_ajaran_id = ?
              AND wk.status_aktif = 1
              AND ta.status_aktif = 1
            LIMIT 1
        ");

        if (!$stmt) {
            die(mysqli_error($koneksi));
        }

        mysqli_stmt_bind_param(
            $stmt,
            'ii',
            $guru_id,
            $tahun_ajaran_aktif_id
        );

        mysqli_stmt_execute($stmt);

        $data_wali = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

        mysqli_stmt_close($stmt);

        if ($data_wali) {
            $akses_wali_kelas = true;
            $kelas_wali_id = (int)$data_wali['kelas_id'];
            $kelas_id = $kelas_wali_id;
            $tahun_ajaran_id = $tahun_ajaran_aktif_id;
            $nama_kelas_wali = $data_wali['nama_kelas'];
        }
    }

    if (!$akses_wali_kelas) {
        header('Location: dashboard.php');
        exit;
    }
}

// ========================================
// BATASI AKSES NON-ADMIN
// ========================================

if (!$is_admin) {
    $tahun_ajaran_id = $tahun_ajaran_aktif_id;
    $kelas_id = $kelas_wali_id;
}

// ========================================
// FILTER TAHUN AJARAN
// ========================================

if ($is_admin) {

    $query_tahun = mysqli_query($koneksi, "
        SELECT id, nama
        FROM t_tahun_ajaran
        ORDER BY id DESC
    ");

} else {

    $stmt = mysqli_prepare($koneksi, "
        SELECT id, nama
        FROM t_tahun_ajaran
        WHERE id = ?
    ");

    mysqli_stmt_bind_param($stmt, 'i', $tahun_ajaran_id);
    mysqli_stmt_execute($stmt);

    $query_tahun = mysqli_stmt_get_result($stmt);
    mysqli_stmt_close($stmt);
}

if (!$query_tahun) {
    die(mysqli_error($koneksi));
}

$data_tahun = mysqli_fetch_all($query_tahun, MYSQLI_ASSOC);

// ========================================
// FILTER KELAS
// ========================================

if ($is_admin) {

    $query_kelas = mysqli_query($koneksi, "
        SELECT id, nama, tingkat, jurusan
        FROM t_kelas
        ORDER BY tingkat, jurusan, nama
    ");

} else {

    $stmt = mysqli_prepare($koneksi, "
        SELECT id, nama, tingkat, jurusan
        FROM t_kelas
        WHERE id = ?
    ");

    mysqli_stmt_bind_param($stmt, 'i', $kelas_id);
    mysqli_stmt_execute($stmt);

    $query_kelas = mysqli_stmt_get_result($stmt);
    mysqli_stmt_close($stmt);
}

if (!$query_kelas) {
    die(mysqli_error($koneksi));
}

$data_kelas = mysqli_fetch_all($query_kelas, MYSQLI_ASSOC);

// ========================================
// AMBIL LAPORAN PELANGGARAN
// ========================================

$sql = "
    SELECT
        ps.id,
        s.nis,
        COALESCE(NULLIF(ps.nama_siswa, ''), s.nama, '-') AS nama_siswa,

        COALESCE(NULLIF(ps.nama_kelas, ''), k.nama, '-') AS nama_kelas,

        COALESCE(
            NULLIF(NULLIF(ps.nama_pelanggaran, ''), '0'),
            p.nama,
            '-'
        ) AS nama_pelanggaran,

        ps.tanggal,
        ps.poin,
        ps.tindakan,

        COALESCE(
            NULLIF(NULLIF(ps.nama_guru, ''), '0'),
            g.nama,
            '-'
        ) AS nama_guru,

        ps.status,
        ta.nama AS tahun_ajaran

    FROM t_pelanggaran_siswa ps

    LEFT JOIN t_siswa s
        ON s.id = ps.siswa_id

    LEFT JOIN t_kelas k
        ON k.id = ps.kelas_id

    LEFT JOIN t_pelanggaran p
        ON p.id = ps.pelanggaran_id

    LEFT JOIN t_guru g
        ON g.id = ps.guru_id

    LEFT JOIN t_tahun_ajaran ta
        ON ta.id = ps.tahun_ajaran_id

    WHERE 1 = 1
";

if ($is_admin) {

    if ($tahun_ajaran_id > 0) {
        $sql .= " AND ps.tahun_ajaran_id = " . $tahun_ajaran_id;
    }

    if ($kelas_id > 0) {
        $sql .= " AND ps.kelas_id = " . $kelas_id;
    }

} else {

    $sql .= " AND ps.kelas_id = " . $kelas_wali_id;
    $sql .= " AND ps.tahun_ajaran_id = " . $tahun_ajaran_aktif_id;

}

$sql .= " ORDER BY ps.tanggal DESC, ps.id DESC";

$query_laporan = mysqli_query($koneksi, $sql);

if (!$query_laporan) {
    die("Gagal mengambil laporan: " . mysqli_error($koneksi));
}

$data_laporan = [];

while ($row = mysqli_fetch_assoc($query_laporan)) {
    $data_laporan[] = $row;
}

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Laporan Pelanggaran Siswa</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        main {
            min-width: 0;
        }

        .tabel-wrapper {
            width: 100%;
            overflow-x: auto;
            background: white;
        }

        #tabelLaporan {
            width: 100%;
            margin-bottom: 0;
        }

        #tabelLaporan th {
            background: #212529;
            color: white;
            white-space: nowrap;
            cursor: pointer;
        }

        #tabelLaporan th,
        #tabelLaporan td {
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

        .paginasi {
            display: flex;
            gap: 5px;
            flex-wrap: wrap;
            margin: 15px 0;
        }

        @media print {
            .sidebar,
            .filter-laporan,
            .kontrol-tabel,
            .paginasi,
            .tombol-aksi,
            #infoData,
            .kembali-dashboard {
                display: none !important;
            }

            .main-content {
                width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            body {
                background: white !important;
            }

            .tabel-wrapper {
                overflow: visible;
            }

            #tabelLaporan {
                font-size: 10px;
            }

            #tabelLaporan tr {
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
            <h1>Laporan Pelanggaran Siswa</h1>

            <p class="text-muted">
                Laporan pelanggaran siswa berdasarkan kelas dan tahun ajaran.
            </p>
        </div>

        <div class="tombol-aksi mb-3">
            <button type="button"
                    onclick="window.print()"
                    class="btn btn-success">
                Cetak Laporan
            </button>
        </div>

    </div>

    <a href="dashboard.php"
       class="btn btn-secondary mb-3 kembali-dashboard">
        Kembali ke Dashboard
    </a>

    <hr>

    <?php if ($is_guru && $akses_wali_kelas) { ?>

        <div class="alert alert-info">
            <strong>Laporan Wali Kelas</strong><br>
            Menampilkan pelanggaran siswa kelas
            <strong><?= htmlspecialchars($nama_kelas_wali) ?></strong>
            pada tahun ajaran
            <strong><?= htmlspecialchars($nama_tahun_aktif) ?></strong>.
        </div>

    <?php } ?>

    <!-- FILTER -->

    <div class="card shadow-sm mb-4 filter-laporan">

        <div class="card-header bg-primary text-white">
            Filter Laporan
        </div>

        <div class="card-body">

            <?php if ($is_admin) { ?>

                <form method="GET" action="laporan.php">

                    <div class="row">

                        <div class="col-md-5 mb-3">
                            <label class="form-label">Tahun Ajaran:</label>

                            <select name="tahun_ajaran_id" class="form-select">

                                <option value="0">Semua Tahun Ajaran</option>

                                <?php foreach ($data_tahun as $ta) { ?>

                                    <option
                                        value="<?= (int)$ta['id'] ?>"
                                        <?= $tahun_ajaran_id == $ta['id'] ? 'selected' : '' ?>>

                                        <?= htmlspecialchars($ta['nama']) ?>

                                    </option>

                                <?php } ?>

                            </select>
                        </div>

                        <div class="col-md-5 mb-3">
                            <label class="form-label">Kelas:</label>

                            <select name="kelas_id" class="form-select">

                                <option value="0">Semua Kelas</option>

                                <?php foreach ($data_kelas as $kelas) { ?>

                                    <option
                                        value="<?= (int)$kelas['id'] ?>"
                                        <?= $kelas_id == $kelas['id'] ? 'selected' : '' ?>>

                                        <?= htmlspecialchars(
                                            $kelas['nama'] . ' - ' .
                                            $kelas['tingkat'] . ' - ' .
                                            $kelas['jurusan']
                                        ) ?>

                                    </option>

                                <?php } ?>

                            </select>
                        </div>

                        <div class="col-md-2 d-flex align-items-end mb-3">

                            <button type="submit" class="btn btn-primary w-100">
                                Tampilkan
                            </button>

                        </div>

                    </div>

                </form>

            <?php } else { ?>

                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tahun Ajaran:</label>

                        <input type="text"
                               class="form-control"
                               value="<?= htmlspecialchars($nama_tahun_aktif) ?>"
                               readonly>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Kelas:</label>

                        <input type="text"
                               class="form-control"
                               value="<?= htmlspecialchars($nama_kelas_wali) ?>"
                               readonly>
                    </div>

                </div>

            <?php } ?>

        </div>
    </div>

    <!-- TABEL -->

    <div class="card shadow-sm">

        <div class="card-header bg-white">
            <h4 class="mb-0">Data Pelanggaran Siswa</h4>
        </div>

        <div class="card-body">

            <div class="kontrol-tabel">

                <label class="mb-0">
                    Tampilkan

                    <select id="jumlahData"
                            class="form-select d-inline-block"
                            style="width: auto;">

                        <option value="5">5</option>
                        <option value="10" selected>10</option>
                        <option value="25">25</option>
                        <option value="all">Semua</option>

                    </select>

                    data
                </label>

                <input type="search"
                       id="cariData"
                       class="form-control"
                       placeholder="Cari nama, pelanggaran, kelas, atau guru...">

            </div>

            <div class="tabel-wrapper">

                <table class="table table-bordered table-hover"
                       id="tabelLaporan">

                    <thead>
                        <tr>
                            <th>No</th>
                            <th>NIS</th>
                            <th>Nama Siswa</th>
                            <th>Kelas</th>
                            <th>Jenis Pelanggaran</th>
                            <th>Tanggal</th>
                            <th>Poin</th>
                            <th>Teguran / Tindakan</th>
                            <th>Guru Pencatat</th>
                            <th>Status</th>
                            <th>Tahun Ajaran</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php if (count($data_laporan) > 0) { ?>

                            <?php foreach ($data_laporan as $index => $row) { ?>

                                <tr>

                                    <td><?= $index + 1 ?></td>

                                    <td>
                                        <?= htmlspecialchars($row['nis'] ?? '-') ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($row['nama_siswa'] ?? '-') ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($row['nama_kelas'] ?? '-') ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($row['nama_pelanggaran'] ?? '-') ?>
                                    </td>

                                    <td>
                                        <?= !empty($row['tanggal'])
                                            ? htmlspecialchars(date('d-m-Y', strtotime($row['tanggal'])))
                                            : '-' ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars((string)($row['poin'] ?? 0)) ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars(
                                            !empty($row['tindakan']) ? $row['tindakan'] : '-'
                                        ) ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($row['nama_guru'] ?? '-') ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($row['status'] ?? '-') ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($row['tahun_ajaran'] ?? '-') ?>
                                    </td>

                                </tr>

                            <?php } ?>

                        <?php } else { ?>

                            <tr>
                                <td colspan="11" class="text-center">
                                    Tidak ada data pelanggaran siswa.
                                </td>
                            </tr>

                        <?php } ?>

                    </tbody>

                </table>

            </div>

            <p id="infoData" class="mt-3"></p>

            <div id="paginasi" class="paginasi"></div>

        </div>
    </div>

</main>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const tabel = document.getElementById('tabelLaporan');
    const tbody = tabel.querySelector('tbody');
    const cari = document.getElementById('cariData');
    const jumlah = document.getElementById('jumlahData');
    const info = document.getElementById('infoData');
    const paginasi = document.getElementById('paginasi');

    const baris = Array.from(tbody.querySelectorAll('tr'))
        .filter(tr => tr.cells.length === 11);

    let halaman = 1;
    let kolomUrut = -1;
    let arah = 1;

    function renderTabel() {

        const kata = cari.value.toLowerCase().trim();

        let hasil = baris.filter(tr =>
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

        const perHalaman = jumlah.value === 'all'
            ? Math.max(hasil.length, 1)
            : parseInt(jumlah.value, 10);

        const totalHalaman = Math.max(
            1,
            Math.ceil(hasil.length / perHalaman)
        );

        halaman = Math.min(halaman, totalHalaman);

        const awal = (halaman - 1) * perHalaman;

        const dataTampil = hasil.slice(
            awal,
            awal + perHalaman
        );

        tbody.replaceChildren();

        if (dataTampil.length > 0) {

            dataTampil.forEach((tr, index) => {
                tr.cells[0].textContent = awal + index + 1;
                tbody.appendChild(tr);
            });

        } else {

            const tr = document.createElement('tr');
            const td = document.createElement('td');

            td.colSpan = 11;
            td.className = 'text-center';
            td.textContent = 'Tidak ada data pelanggaran siswa.';

            tr.appendChild(td);
            tbody.appendChild(tr);

        }

        info.textContent = hasil.length > 0
            ? `Menampilkan ${awal + 1}–${Math.min(
                awal + dataTampil.length,
                hasil.length
            )} dari ${hasil.length} data`
            : 'Tidak ada data yang cocok.';

        paginasi.replaceChildren();

        if (jumlah.value === 'all' || totalHalaman <= 1) {
            return;
        }

        function tombol(teks, tujuan, nonaktif, aktif) {

            const btn = document.createElement('button');

            btn.type = 'button';
            btn.textContent = teks;
            btn.disabled = nonaktif;

            btn.className = aktif
                ? 'btn btn-primary btn-sm'
                : 'btn btn-outline-primary btn-sm';

            btn.addEventListener('click', function () {
                halaman = tujuan;
                renderTabel();
            });

            paginasi.appendChild(btn);
        }

        tombol('Sebelumnya', halaman - 1, halaman === 1, false);

        for (let i = 1; i <= totalHalaman; i++) {
            tombol(String(i), i, false, i === halaman);
        }

        tombol('Berikutnya', halaman + 1, halaman === totalHalaman, false);
    }

    cari.addEventListener('input', function () {
        halaman = 1;
        renderTabel();
    });

    jumlah.addEventListener('change', function () {
        halaman = 1;
        renderTabel();
    });

    tabel.querySelectorAll('thead th').forEach(function (th, index) {

        th.title = 'Klik untuk mengurutkan';

        th.addEventListener('click', function () {

            if (kolomUrut === index) {
                arah *= -1;
            } else {
                kolomUrut = index;
                arah = 1;
            }

            halaman = 1;
            renderTabel();
        });

    });

    let kondisiCetak = null;

    window.addEventListener('beforeprint', function () {

        kondisiCetak = {
            pencarian: cari.value,
            jumlah: jumlah.value,
            halaman: halaman
        };

        let semuaBaris = baris.slice();

        if (kolomUrut >= 0) {

            semuaBaris.sort((a, b) => {

                const x = a.cells[kolomUrut].innerText.trim();
                const y = b.cells[kolomUrut].innerText.trim();

                return x.localeCompare(y, 'id', {
                    numeric: true,
                    sensitivity: 'base'
                }) * arah;

            });

        }

        tbody.replaceChildren();

        semuaBaris.forEach(function (tr, index) {
            tr.cells[0].textContent = index + 1;
            tbody.appendChild(tr);
        });

        if (semuaBaris.length === 0) {

            const tr = document.createElement('tr');
            const td = document.createElement('td');

            td.colSpan = 11;
            td.className = 'text-center';
            td.textContent = 'Tidak ada data pelanggaran siswa.';

            tr.appendChild(td);
            tbody.appendChild(tr);

        }

    });

    window.addEventListener('afterprint', function () {

        if (!kondisiCetak) return;

        cari.value = kondisiCetak.pencarian;
        jumlah.value = kondisiCetak.jumlah;
        halaman = kondisiCetak.halaman;

        kondisiCetak = null;

        renderTabel();

    });

    renderTabel();

});
</script>

</body>
</html>
