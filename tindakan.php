
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

// Ambil data jenis pelanggaran
$sql = "SELECT * FROM t_pelanggaran ORDER BY id DESC";
$query = mysqli_query($koneksi, $sql);

if (!$query) {
    die("Gagal mengambil data: " . mysqli_error($koneksi));
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

    <style>
        body {
            background: #f4f6f9;
        }

        .konten {
            margin-left: 273px;
            padding: 28px;
            min-height: 100vh;
        }

        .card {
            border: none;
            border-radius: 8px;
        }

        .card-header {
            font-weight: 600;
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

    <h2 class="mb-2">Tindakan Pelanggaran</h2>

    <p class="text-muted">
        Daftar jenis pelanggaran dan pilihan tindakan pembinaan.
    </p>

    <!-- FORM TINDAKAN -->
    <div class="card shadow-sm mb-4">

        <div class="card-header bg-primary text-white">
            Form Tindakan
        </div>

        <div class="card-body">

            <form action="proses_tindakan.php" method="POST">

                <div class="form-group">
                    <label>Jenis Pelanggaran</label>

                    <select name="pelanggaran_id"
                            class="form-control"
                            required>

                        <option value="">-- Pilih Pelanggaran --</option>

                        <?php
                        mysqli_data_seek($query, 0);

                        while ($row = mysqli_fetch_assoc($query)):
                        ?>

                            <option value="<?= (int)$row['id']; ?>">
                                <?= htmlspecialchars($row['nama'] ?? '-'); ?>
                                (<?= (int)$row['poin']; ?> poin)
                            </option>

                        <?php endwhile; ?>

                    </select>
                </div>

                <div class="form-group">
                    <label>Jenis Tindakan</label>

                    <select name="jenis_tindakan"
                            class="form-control"
                            required>

                        <option value="">-- Pilih Tindakan --</option>
                        <option value="Teguran Lisan">Teguran Lisan</option>
                        <option value="Teguran Tertulis">Teguran Tertulis</option>
                        <option value="Pembinaan BK">Pembinaan BK</option>
                        <option value="Pemanggilan Orang Tua">Pemanggilan Orang Tua</option>
                        <option value="Surat Peringatan">Surat Peringatan</option>

                    </select>
                </div>

                <div class="form-group">
                    <label>Tanggal Tindakan</label>

                    <input type="date"
                           name="tanggal_tindakan"
                           class="form-control"
                           required>
                </div>

                <div class="form-group">
                    <label>Keterangan</label>

                    <textarea name="keterangan"
                              class="form-control"
                              rows="3"
                              placeholder="Catatan pembinaan"></textarea>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    Simpan Tindakan
                </button>

            </form>

        </div>
    </div>


    <!-- TABEL JENIS PELANGGARAN -->
    <div class="card shadow-sm">

        <div class="card-header bg-white">
            Data Jenis Pelanggaran
        </div>

        <div class="card-body table-responsive">

            <table class="table table-bordered table-striped table-hover">

                <thead class="thead-dark">
                    <tr>
                        <th width="60">No</th>
                        <th>ID</th>
                        <th>Kode</th>
                        <th>Nama Pelanggaran</th>
                        <th>Poin</th>
                        <th>Deskripsi</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>

                    <?php
                    mysqli_data_seek($query, 0);

                    if (mysqli_num_rows($query) > 0):

                        $no = 1;

                        while ($row = mysqli_fetch_assoc($query)):
                    ?>

                    <tr>
                        <td><?= $no++; ?></td>

                        <td>
                            <?= (int)$row['id']; ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($row['kode'] ?? '-'); ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($row['nama'] ?? '-'); ?>
                        </td>

                        <td>
                            <?= (int)$row['poin']; ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($row['deskripsi'] ?? '-'); ?>
                        </td>

                        <td>
                            <?php if (!empty($row['status_aktif'])): ?>
                                <span class="badge badge-success">Aktif</span>
                            <?php else: ?>
                                <span class="badge badge-secondary">Tidak Aktif</span>
                            <?php endif; ?>
                        </td>
                    </tr>

                    <?php
                        endwhile;

                    else:
                    ?>

                    <tr>
                        <td colspan="7" class="text-center">
                            Belum ada data pelanggaran.
                        </td>
                    </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>
    </div>

</div>

</body>
</html>
