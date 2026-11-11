
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$success = $_SESSION['success_tindakan'] ?? '';
$error = $_SESSION['error_tindakan'] ?? '';

unset($_SESSION['success_tindakan'], $_SESSION['error_tindakan']);

// Menentukan tindakan otomatis berdasarkan poin
function tentukanTindakan($poin)
{
    if ($poin <= 0) return '-';
    if ($poin <= 20) return 'Teguran Lisan';
    if ($poin <= 40) return 'Teguran Tertulis';
    if ($poin <= 60) return 'Pembinaan BK';
    if ($poin <= 80) return 'Pemanggilan Orang Tua';

    return 'Rekomendasi Tindakan Lanjutan';
}

// Ambil data pelanggaran yang belum ditindaklanjuti
$sql = "
    SELECT
        ps.*,
        ta.nama AS tahun_ajaran,
        (
            SELECT COALESCE(SUM(p2.poin), 0)
            FROM t_pelanggaran_siswa p2
            WHERE p2.siswa_id = ps.siswa_id
              AND p2.tahun_ajaran_id = ps.tahun_ajaran_id
        ) AS total_poin
    FROM t_pelanggaran_siswa ps
    LEFT JOIN t_tahun_ajaran ta
        ON ta.id = ps.tahun_ajaran_id
    WHERE LOWER(TRIM(COALESCE(ps.status, ''))) != 'sudah ditindaklanjuti'
    ORDER BY ps.tanggal DESC, ps.id DESC
";

$query = mysqli_query($koneksi, $sql);

if (!$query) {
    die('Gagal mengambil data: ' . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Tindakan Pelanggaran</title>

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        body {
            background: #f4f6f9;
            font-family: Arial, sans-serif;
        }

        .konten {
            margin-left: 273px;
            padding: 25px;
            min-height: 100vh;
        }

        .judul-halaman {
            font-size: 24px;
            font-weight: 600;
            color: #26344a;
        }

        .card {
            border: none;
            border-radius: 9px;
        }

        .card-header {
            font-size: 14px;
            font-weight: 600;
            padding: 13px 17px;
        }

        .card-body {
            padding: 16px;
        }

        .table {
            font-size: 12px;
            margin-bottom: 0;
        }

        .table th,
        .table td {
            padding: 9px 10px;
            vertical-align: middle;
        }

        .table thead th {
            white-space: nowrap;
            font-weight: 600;
            border-bottom: none;
        }

        .table tbody td {
            white-space: nowrap;
        }

        .table thead.thead-dark th {
            background: #293d59;
        }

        .badge {
            font-size: 10px;
            padding: 6px 8px;
            border-radius: 5px;
        }

        .poin {
            font-weight: 600;
            color: #1769c2;
        }

        .btn-tindakan {
            font-size: 11px;
            padding: 5px 9px;
            border-radius: 5px;
        }

        .form-detail {
            min-width: 220px;
            max-width: 260px;
            white-space: normal;
            margin-top: 9px;
            padding: 12px;
            background: #f8f9fc;
            border: 1px solid #e3e7ed;
            border-radius: 7px;
        }

        .form-detail .form-control {
            font-size: 12px;
            margin-bottom: 8px;
        }

        .form-detail textarea {
            resize: vertical;
        }

        details summary {
            list-style: none;
            cursor: pointer;
            outline: none;
        }

        details summary::-webkit-details-marker {
            display: none;
        }

        .aturan-table {
            font-size: 12px;
        }

        .aturan-table td {
            padding: 7px 10px;
        }

        @media (max-width: 768px) {
            .konten {
                margin-left: 0;
                padding: 15px;
            }
        }
    </style>
</head>

<body>

<?php include 'includes/sidebar.php'; ?>

<div class="konten">

    <h2 class="judul-halaman mb-1">
        <i class="fas fa-gavel"></i> Tindakan Pelanggaran
    </h2>

    <p class="text-muted mb-4" style="font-size:13px;">
        Tindakan pembinaan ditentukan otomatis berdasarkan total poin siswa.
    </p>

    <?php if ($success): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            <?= htmlspecialchars($success); ?>
        </div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i>
            <?= htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <!-- ATURAN POIN -->
    <div class="card shadow-sm mb-4">

        <div class="card-header bg-primary text-white">
            <i class="fas fa-list-ol"></i>
            Aturan Tindakan Otomatis
        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered aturan-table">

                    <tbody>
                        <tr>
                            <td width="30%">1–20 poin</td>
                            <td>Teguran Lisan</td>
                        </tr>

                        <tr>
                            <td>21–40 poin</td>
                            <td>Teguran Tertulis</td>
                        </tr>

                        <tr>
                            <td>41–60 poin</td>
                            <td>Pembinaan BK</td>
                        </tr>

                        <tr>
                            <td>61–80 poin</td>
                            <td>Pemanggilan Orang Tua</td>
                        </tr>

                        <tr>
                            <td>Di atas 80 poin</td>
                            <td>Rekomendasi Tindakan Lanjutan</td>
                        </tr>
                    </tbody>

                </table>

            </div>

        </div>
    </div>

    <!-- DATA TINDAKAN -->
    <div class="card shadow-sm">

        <div class="card-header bg-white">
            <i class="fas fa-users"></i>
            Data Tindakan Siswa
        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover">

                    <thead class="thead-dark">
                        <tr>
                            <th width="45">No</th>
                            <th>Nama Siswa</th>
                            <th>Kelas</th>
                            <th>Pelanggaran</th>
                            <th>Total Poin</th>
                            <th>Tindakan Otomatis</th>
                            <th>Status</th>
                            <th width="130">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php if (mysqli_num_rows($query) > 0): ?>

                        <?php $no = 1; ?>

                        <?php while ($row = mysqli_fetch_assoc($query)): ?>

                            <?php
                            $total_poin = (int)$row['total_poin'];
                            $tindakan_otomatis = tentukanTindakan($total_poin);
                            $id_catatan = (int)$row['id'];
                            ?>

                            <tr>

                                <td><?= $no++; ?></td>

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
                                    <span class="poin">
                                        <?= $total_poin; ?> poin
                                    </span>
                                </td>

                                <td>
                                    <?php if ($total_poin > 0): ?>

                                        <span class="badge badge-primary">
                                            <?= htmlspecialchars($tindakan_otomatis); ?>
                                        </span>

                                    <?php else: ?>

                                        <span class="text-muted">
                                            Belum tersedia
                                        </span>

                                    <?php endif; ?>
                                </td>

                                <td>
                                    <span class="badge badge-warning">
                                        Belum Ditindaklanjuti
                                    </span>
                                </td>

                                <td>

                                    <?php if ($total_poin > 0): ?>

                                        <details>

                                            <summary class="btn btn-primary btn-tindakan">
                                                <i class="fas fa-edit"></i>
                                                Proses
                                            </summary>

                                            <form action="proses_tindakan.php"
                                                  method="POST"
                                                  class="form-detail">

                                                <input type="hidden"
                                                       name="id"
                                                       value="<?= $id_catatan; ?>">

                                                <div class="mb-2">
                                                    <label>Jenis Tindakan</label>

                                                    <input type="text"
                                                           class="form-control"
                                                           value="<?= htmlspecialchars($tindakan_otomatis); ?>"
                                                           readonly>
                                                </div>

                                                <label>Tanggal Tindakan</label>

                                                <input type="date"
                                                       name="tanggal_tindakan"
                                                       class="form-control"
                                                       value="<?= date('Y-m-d'); ?>"
                                                       required>

                                                <label>Keterangan</label>

                                                <textarea name="keterangan_tindakan"
                                                          class="form-control"
                                                          rows="3"
                                                          placeholder="Masukkan keterangan tindakan..."
                                                          required></textarea>

                                                <button type="submit"
                                                        class="btn btn-success btn-sm btn-block"
                                                        onclick="return confirm('Yakin ingin menyelesaikan tindakan ini?')">

                                                    <i class="fas fa-check-circle"></i>
                                                    Selesaikan Tindakan

                                                </button>

                                            </form>

                                        </details>

                                    <?php else: ?>

                                        <span class="text-muted">
                                            Poin belum tersedia
                                        </span>

                                    <?php endif; ?>

                                </td>

                            </tr>

                        <?php endwhile; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="fas fa-folder-open"></i>
                                <br>
                                Tidak ada tindakan yang perlu diproses.
                            </td>
                        </tr>

                    <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>
    </div>

</div>

</body>
</html>
