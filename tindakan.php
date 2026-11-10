
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$success = $_SESSION['success_tindakan'] ?? '';
$error = $_SESSION['error_tindakan'] ?? '';

unset($_SESSION['success_tindakan'], $_SESSION['error_tindakan']);

$daftar_tindakan = [
    'Teguran Lisan',
    'Teguran Tertulis',
    'Pembinaan BK',
    'Pemanggilan Orang Tua',
    'Rekomendasi Tindakan Lanjutan'
];

// Batas tindakan berdasarkan total poin
function batasTindakan($poin)
{
    if ($poin <= 20) return 0;
    if ($poin <= 40) return 1;
    if ($poin <= 60) return 2;
    if ($poin <= 80) return 3;

    return 4;
}

// Ambil catatan pelanggaran dan akumulasi poin siswa
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

        .aturan-table td {
            padding: 7px 10px;
        }

        .aturan-table {
            font-size: 12px;
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
        Kelola tindakan pembinaan siswa berdasarkan akumulasi poin.
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
            Aturan Tindakan Berdasarkan Poin
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

    <!-- DATA PELANGGARAN -->
    <div class="card shadow-sm">

        <div class="card-header bg-white">
            <i class="fas fa-users"></i>
            Data Catatan Pelanggaran
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
                            <th>Status</th>
                            <th width="130">Tindakan</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php if (mysqli_num_rows($query) > 0): ?>

                        <?php $no = 1; ?>

                        <?php while ($row = mysqli_fetch_assoc($query)): ?>

                            <?php
                            $total_poin = (int)$row['total_poin'];
                            $batas = batasTindakan($total_poin);

                            $status = strtolower(trim($row['status'] ?? ''));

                            $sudah_ditindaklanjuti =
                                ($status === 'sudah ditindaklanjuti');

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

                                    <?php if ($sudah_ditindaklanjuti): ?>

                                        <span class="badge badge-success">
                                            Sudah Ditindaklanjuti
                                        </span>

                                    <?php else: ?>

                                        <span class="badge badge-warning">
                                            Belum Ditindaklanjuti
                                        </span>

                                    <?php endif; ?>

                                </td>

                                <td>

                                    <?php if (!$sudah_ditindaklanjuti): ?>

                                        <?php if ($total_poin <= 0): ?>

                                            <span class="text-muted">
                                                Poin belum tersedia
                                            </span>

                                        <?php else: ?>

                                            <details>

                                                <summary class="btn btn-primary btn-tindakan">
                                                    <i class="fas fa-edit"></i>
                                                    Atur Tindakan
                                                </summary>

                                                <form action="proses_tindakan.php"
                                                      method="POST"
                                                      class="form-detail">

                                                    <input type="hidden"
                                                           name="id"
                                                           value="<?= $id_catatan; ?>">

                                                    <label>Jenis Tindakan</label>

                                                    <select name="jenis_tindakan"
                                                            class="form-control"
                                                            required>

                                                        <option value="">
                                                            -- Pilih Tindakan --
                                                        </option>

                                                        <?php foreach ($daftar_tindakan as $index => $tindakan): ?>

                                                            <?php if ($index >= $batas): ?>

                                                                <option value="<?= htmlspecialchars($tindakan); ?>">
                                                                    <?= htmlspecialchars($tindakan); ?>
                                                                </option>

                                                            <?php endif; ?>

                                                        <?php endforeach; ?>

                                                    </select>

                                                    <label>Tanggal Tindakan</label>

                                                    <input type="date"
                                                           name="tanggal_tindakan"
                                                           class="form-control"
                                                           value="<?= date('Y-m-d'); ?>"
                                                           required>

                                                    <label>Keterangan</label>

                                                    <textarea name="keterangan_tindakan"
                                                              class="form-control"
                                                              rows="2"
                                                              placeholder="Keterangan pembinaan..."
                                                              required></textarea>

                                                    <button type="submit"
                                                            class="btn btn-success btn-sm btn-block"
                                                            onclick="return confirm('Yakin ingin menyimpan tindakan ini?')">

                                                        <i class="fas fa-save"></i>
                                                        Simpan

                                                    </button>

                                                </form>

                                            </details>

                                        <?php endif; ?>

                                    <?php else: ?>

                                        <details>

                                            <summary class="btn btn-light btn-tindakan">
                                                <i class="fas fa-eye"></i>
                                                Lihat Detail
                                            </summary>

                                            <div class="form-detail">

                                                <strong>
                                                    <?= htmlspecialchars($row['tindakan'] ?? '-'); ?>
                                                </strong>

                                                <hr>

                                                <small>
                                                    <strong>Tanggal:</strong><br>
                                                    <?= htmlspecialchars($row['tanggal_tindakan'] ?? '-'); ?>
                                                </small>

                                                <br><br>

                                                <small>
                                                    <strong>Keterangan:</strong><br>
                                                    <?= nl2br(htmlspecialchars($row['keterangan_tindakan'] ?? '-')); ?>
                                                </small>

                                            </div>

                                        </details>

                                    <?php endif; ?>

                                </td>

                            </tr>

                        <?php endwhile; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="fas fa-folder-open"></i>
                                <br>
                                Belum ada catatan pelanggaran siswa.
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
