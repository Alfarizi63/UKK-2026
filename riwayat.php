
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

// ========================================
// HAK AKSES
// ========================================
$role = $_SESSION['role'] ?? '';

if (!in_array($role, ['admin', 'guru', 'wali_kelas'], true)) {
    header('Location: dashboard.php');
    exit;
}

$nama_login = $_SESSION['nama_guru']
    ?? $_SESSION['name']
    ?? 'Pengguna';

$guru_id = (int) ($_SESSION['guru_id'] ?? 0);

$error = '';
$data_riwayat = [];

// ========================================
// AMBIL RIWAYAT
// ========================================
if ($role === 'admin') {

    // ADMIN MELIHAT SEMUA LAPORAN
    $sql = "
        SELECT *
        FROM t_pelanggaran_siswa
        ORDER BY id DESC
    ";

    $query = mysqli_query($koneksi, $sql);

} else {

    // GURU HANYA MELIHAT LAPORAN MILIKNYA
    if ($guru_id < 1) {
        $error = 'Identitas guru tidak ditemukan. Silakan logout dan login kembali.';
        $query = false;
    } else {

        $sql = "
            SELECT *
            FROM t_pelanggaran_siswa
            WHERE guru_id = ?
            ORDER BY id DESC
        ";

        $stmt = mysqli_prepare($koneksi, $sql);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "i", $guru_id);
            mysqli_stmt_execute($stmt);

            $query = mysqli_stmt_get_result($stmt);
        } else {
            $query = false;
        }
    }
}

// ========================================
// MASUKKAN HASIL KE ARRAY
// ========================================
if ($query) {

    while ($row = mysqli_fetch_assoc($query)) {
        $data_riwayat[] = $row;
    }

} elseif ($error === '') {

    $error = 'Gagal mengambil data riwayat: ' . mysqli_error($koneksi);
}

$total_riwayat = count($data_riwayat);

// ========================================
// FUNGSI FORMAT TANGGAL
// ========================================
function formatTanggal($tanggal)
{
    if (!$tanggal || $tanggal === '0000-00-00') {
        return '-';
    }

    $waktu = strtotime($tanggal);

    if (!$waktu) {
        return $tanggal;
    }

    return date('d-m-Y', $waktu);
}

// ========================================
// FUNGSI HTML AMAN
// ========================================
function e($nilai)
{
    return htmlspecialchars((string) ($nilai ?? '-'), ENT_QUOTES, 'UTF-8');
}
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Riwayat Pelanggaran</title>

    <!-- Bootstrap 4 -->
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">

    <!-- Font Awesome -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        body {
            background: #f5f7fb;
            font-family: Arial, sans-serif;
            color: #334155;
        }

        .konten {
            margin-left: 273px;
            padding: 28px;
        }

        .judul {
            font-size: 24px;
            font-weight: 700;
            color: #1e293b;
        }

        .deskripsi {
            font-size: 13px;
            color: #64748b;
        }

        .kartu {
            background: #fff;
            border: 1px solid #e8edf4;
            border-radius: 12px;
            box-shadow: 0 3px 12px rgba(0, 0, 0, .035);
        }

        .statistik {
            padding: 20px;
            height: 100%;
        }

        .ikon {
            width: 43px;
            height: 43px;
            border-radius: 10px;
            background: #eaf1ff;
            color: #2563eb;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 18px;
            margin-bottom: 13px;
        }

        .angka {
            font-size: 26px;
            font-weight: 700;
            color: #1e293b;
        }

        .label {
            color: #64748b;
            font-size: 13px;
        }

        .bagian-tabel {
            padding: 20px;
        }

        .judul-tabel {
            font-size: 17px;
            font-weight: 700;
            color: #1e293b;
        }

        .form-control {
            border-radius: 7px;
            height: 40px;
            font-size: 13px;
        }

        .table-responsive {
            border: 1px solid #edf0f5;
            border-radius: 8px;
        }

        .table {
            min-width: 1000px;
            margin-bottom: 0;
        }

        .table thead th {
            background: #f8fafc;
            color: #475569;
            font-size: 11px;
            text-transform: uppercase;
            padding: 14px 12px;
            border-top: none;
            white-space: nowrap;
        }

        .table tbody td {
            font-size: 13px;
            padding: 14px 12px;
            vertical-align: middle;
            color: #475569;
        }

        .table tbody tr:hover {
            background: #f8faff;
        }

        .nama-siswa {
            font-weight: 700;
            color: #1e293b;
        }

        .badge-poin {
            display: inline-block;
            background: #fff4df;
            color: #a16207;
            padding: 6px 9px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 700;
        }

        .badge-pelapor {
            display: inline-block;
            background: #eaf1ff;
            color: #1d4ed8;
            padding: 6px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }

        .badge-status {
            display: inline-block;
            background: #e8f7ee;
            color: #16803d;
            padding: 6px 9px;
            border-radius: 6px;
            font-size: 11px;
        }

        .kosong {
            text-align: center;
            padding: 45px 15px !important;
        }

        .kosong i {
            font-size: 38px;
            color: #94a3b8;
            margin-bottom: 12px;
        }

        .pagination {
            margin-bottom: 0;
        }

        .pagination .page-link {
            border-radius: 6px;
            margin: 0 2px;
            color: #2563eb;
            font-size: 12px;
        }

        .pagination .active .page-link {
            background: #2563eb;
            border-color: #2563eb;
            color: #fff;
        }

        @media (max-width: 768px) {
            .konten {
                margin-left: 0;
                padding: 15px;
            }
        }

        @media print {
            .sidebar,
            .toolbar,
            .pagination,
            .tombol-aksi {
                display: none !important;
            }

            .konten {
                margin: 0;
                padding: 0;
            }

            .table {
                min-width: 100%;
            }
        }
    </style>

</head>

<body>

<?php include 'includes/sidebar.php'; ?>

<div class="konten">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">

        <div>
            <h4 class="judul mb-1">
                <i class="fas fa-history mr-2"></i>
                Riwayat Pelanggaran
            </h4>

            <p class="deskripsi mb-0">
                Daftar laporan pelanggaran siswa.
            </p>
        </div>

        <button onclick="window.print()"
            class="btn btn-primary btn-sm tombol-aksi">

            <i class="fas fa-print mr-1"></i>
            Cetak Riwayat

        </button>

    </div>

    <!-- IDENTITAS PENGGUNA -->
    <?php if ($role !== 'admin'): ?>

        <div class="alert alert-light border mb-4">

            <i class="fas fa-user-shield text-primary mr-2"></i>

            <strong><?= e($nama_login); ?></strong>

            <span class="text-muted ml-2">
                · Riwayat laporan milik akun Anda.
            </span>

        </div>

    <?php endif; ?>

    <!-- ERROR -->
    <?php if ($error !== ''): ?>

        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle mr-1"></i>
            <?= e($error); ?>
        </div>

    <?php endif; ?>

    <!-- STATISTIK -->
    <div class="row mb-4">

        <div class="col-md-4 mb-3">

            <div class="kartu statistik">

                <div class="ikon">
                    <i class="fas fa-file-alt"></i>
                </div>

                <div class="angka">
                    <?= $total_riwayat; ?>
                </div>

                <div class="label">
                    Total laporan pelanggaran
                </div>

            </div>

        </div>

        <div class="col-md-4 mb-3">

            <div class="kartu statistik">

                <div class="ikon">
                    <i class="fas fa-user-check"></i>
                </div>

                <div class="angka" style="font-size:20px;">
                    <?= $role === 'admin' ? 'Semua Guru' : 'Laporan Saya'; ?>
                </div>

                <div class="label">
                    Cakupan riwayat
                </div>

            </div>

        </div>

        <div class="col-md-4 mb-3">

            <div class="kartu statistik">

                <div class="ikon">
                    <i class="fas fa-calendar-alt"></i>
                </div>

                <div class="angka" style="font-size:20px;">
                    <?= date('d-m-Y'); ?>
                </div>

                <div class="label">
                    Tanggal hari ini
                </div>

            </div>

        </div>

    </div>

    <!-- TABEL RIWAYAT -->
    <div class="kartu">

        <div class="bagian-tabel">

            <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">

                <div>
                    <div class="judul-tabel">
                        Daftar Riwayat
                    </div>

                    <small class="text-muted">
                        Data laporan yang tersimpan.
                    </small>
                </div>

                <span class="badge badge-primary p-2">
                    <?= $total_riwayat; ?> Laporan
                </span>

            </div>

            <!-- TOOLBAR -->
            <div class="row toolbar mb-3">

                <div class="col-md-6 mb-2">

                    <label class="small text-muted">
                        Tampilkan data
                    </label>

                    <select id="jumlahData"
                        class="form-control"
                        style="max-width:120px;">

                        <option value="5">5</option>
                        <option value="10" selected>10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="all">Semua</option>

                    </select>

                </div>

                <div class="col-md-6 mb-2">

                    <label class="small text-muted">
                        Cari riwayat
                    </label>

                    <input type="text"
                        id="cariData"
                        class="form-control"
                        placeholder="Cari nama siswa, kelas, pelanggaran...">

                </div>

            </div>

            <!-- TABEL -->
            <div class="table-responsive">

                <table class="table table-hover" id="tabelRiwayat">

                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Nama Siswa</th>
                            <th>Kelas</th>
                            <th>Pelanggaran</th>
                            <th>Poin</th>
                            <th>Keterangan</th>
                            <th>Tindakan</th>
                            <th>Pelapor</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php if ($total_riwayat > 0): ?>

                        <?php $no = 1; ?>

                        <?php foreach ($data_riwayat as $row): ?>

                            <tr class="baris-data">

                                <td><?= $no++; ?></td>

                                <td>
                                    <?= e(formatTanggal($row['tanggal'] ?? '')); ?>
                                </td>

                                <td>
                                    <div class="nama-siswa">
                                        <?= e($row['nama_siswa'] ?? '-'); ?>
                                    </div>
                                </td>

                                <td>
                                    <?= e($row['nama_kelas'] ?? '-'); ?>
                                </td>

                                <td>
                                    <?= e($row['nama_pelanggaran'] ?? '-'); ?>
                                </td>

                                <td>
                                    <span class="badge-poin">
                                        <?= e($row['poin'] ?? 0); ?> Poin
                                    </span>
                                </td>

                                <td>
                                    <?= e($row['keterangan'] ?? '-'); ?>
                                </td>

                                <td>
                                    <?= e($row['tindakan'] ?? '-'); ?>
                                </td>

                                <td>
                                    <span class="badge-pelapor">
                                        <i class="fas fa-user mr-1"></i>
                                        <?= e($row['nama_guru'] ?? '-'); ?>
                                    </span>
                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="9" class="kosong">

                                <i class="fas fa-folder-open d-block"></i>

                                <h6 class="font-weight-bold">
                                    Belum Ada Riwayat
                                </h6>

                                <p class="text-muted mb-0">
                                    Belum ada laporan pelanggaran yang dapat ditampilkan.
                                </p>

                            </td>
                        </tr>

                    <?php endif; ?>

                    </tbody>

                </table>

            </div>

            <!-- FOOTER TABEL -->
            <div class="d-flex justify-content-between align-items-center flex-wrap mt-3">

                <small class="text-muted" id="infoData">
                    Menampilkan data.
                </small>

                <ul class="pagination mt-2" id="pagination"></ul>

            </div>

        </div>

    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const tabel = document.getElementById('tabelRiwayat');
    const tbody = tabel.querySelector('tbody');

    const baris = Array.from(
        tbody.querySelectorAll('.baris-data')
    );

    const cari = document.getElementById('cariData');
    const jumlah = document.getElementById('jumlahData');
    const info = document.getElementById('infoData');
    const pagination = document.getElementById('pagination');

    let halaman = 1;

    function tampilkanData() {

        const kata = cari.value.toLowerCase().trim();

        const hasil = baris.filter(function (row) {
            return row.textContent.toLowerCase().includes(kata);
        });

        let perHalaman = jumlah.value === 'all'
            ? Math.max(hasil.length, 1)
            : parseInt(jumlah.value);

        const totalHalaman = Math.max(
            1,
            Math.ceil(hasil.length / perHalaman)
        );

        if (halaman > totalHalaman) {
            halaman = totalHalaman;
        }

        const awal = (halaman - 1) * perHalaman;
        const akhir = Math.min(awal + perHalaman, hasil.length);

        baris.forEach(function (row) {
            row.style.display = 'none';
        });

        hasil.forEach(function (row, index) {
            if (index >= awal && index < akhir) {
                row.style.display = '';
            }
        });

        if (hasil.length === 0 && baris.length > 0) {
            info.textContent = 'Data yang dicari tidak ditemukan.';
        } else if (hasil.length > 0) {
            info.textContent =
                'Menampilkan ' + (awal + 1) +
                ' sampai ' + akhir +
                ' dari ' + hasil.length + ' data.';
        } else {
            info.textContent = 'Belum ada data riwayat.';
        }

        buatPagination(totalHalaman);
    }

    function buatPagination(total) {

        pagination.innerHTML = '';

        function tombol(teks, tujuan, aktif, nonaktif) {

            const li = document.createElement('li');
            li.className = 'page-item';

            if (aktif) li.classList.add('active');
            if (nonaktif) li.classList.add('disabled');

            const a = document.createElement('a');
            a.href = '#';
            a.className = 'page-link';
            a.textContent = teks;

            a.addEventListener('click', function (event) {

                event.preventDefault();

                if (nonaktif) return;

                halaman = tujuan;
                tampilkanData();
            });

            li.appendChild(a);
            pagination.appendChild(li);
        }

        tombol('‹', halaman - 1, false, halaman === 1);

        for (let i = 1; i <= total; i++) {

            if (
                total <= 7 ||
                i === 1 ||
                i === total ||
                Math.abs(i - halaman) <= 1
            ) {
                tombol(
                    i,
                    i,
                    i === halaman,
                    false
                );
            }
        }

        tombol('›', halaman + 1, false, halaman === total);
    }

    cari.addEventListener('input', function () {
        halaman = 1;
        tampilkanData();
    });

    jumlah.addEventListener('change', function () {
        halaman = 1;
        tampilkanData();
    });

    window.addEventListener('beforeprint', function () {
        baris.forEach(function (row) {
            row.style.display = '';
        });
    });

    window.addEventListener('afterprint', tampilkanData);

    tampilkanData();

});
</script>

</body>
</html>
