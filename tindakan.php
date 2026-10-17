```php
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

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
        body { background: #f4f6f9; }
        .sidebar {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            width: 220px;
            background: #343a40;
            padding: 20px 15px;
            overflow-y: auto;
        }
        .sidebar a {
            display: block;
            color: white;
            padding: 10px;
            border-radius: 5px;
            text-decoration: none;
        }
        .sidebar a:hover,
        .sidebar a.active { background: #007bff; }
        .konten {
            margin-left: 220px;
            padding: 25px;
        }
        @media (max-width: 768px) {
            .sidebar {
                position: relative;
                width: 100%;
            }
            .konten { margin-left: 0; }
        }
    </style>
</head>
<body>

<div class="sidebar">
    <h4 class="text-white mb-4">My Website</h4>
    <a href="dashboard.php">Dashboard</a>
    <a href="catatan_pelanggaran.php">Catatan Pelanggaran</a>
    <a href="tindakan.php" class="active">Tindakan</a>
    <a href="laporan.php">Laporan</a>
    <a href="riwayat.php">Riwayat</a>
    <a href="rekap_point.php">Rekap Poin</a>
    <hr class="bg-secondary">
    <a href="login.php" class="text-danger">Logout</a>
</div>

<div class="konten">
    <h2>Tindakan Pelanggaran</h2>
    <p class="text-muted">
        Daftar jenis pelanggaran dan pilihan tindakan pembinaan.
    </p>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            Form Tindakan
        </div>
        <div class="card-body">
            <form action="proses_tindakan.php" method="POST">
                <div class="form-group">
                    <label>Jenis Pelanggaran</label>
                    <select name="pelanggaran_id" class="form-control" required>
                        <option value="">-- Pilih Pelanggaran --</option>
                        <?php while ($row = mysqli_fetch_assoc($query)): ?>
                            <option value="<?= (int)$row['id']; ?>">
                                <?= htmlspecialchars($row['nama'] ?? '-'); ?>
                                (<?= (int)$row['poin']; ?> poin)
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Jenis Tindakan</label>
                    <select name="jenis_tindakan" class="form-control" required>
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
                    <input type="date" name="tanggal_tindakan"
                           class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Keterangan</label>
                    <textarea name="keterangan" class="form-control"
                              rows="3" placeholder="Catatan pembinaan"></textarea>
                </div>

                <button type="submit" class="btn btn-primary">
                    Simpan Tindakan
                </button>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header">Data Jenis Pelanggaran</div>
        <div class="card-body table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="thead-dark">
                    <tr>
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
                        while ($row = mysqli_fetch_assoc($query)):
                    ?>
                    <tr>
                        <td><?= (int)$row['id']; ?></td>
                        <td><?= htmlspecialchars($row['kode'] ?? '-'); ?></td>
                        <td><?= htmlspecialchars($row['nama'] ?? '-'); ?></td>
                        <td><?= (int)$row['poin']; ?></td>
                        <td><?= htmlspecialchars($row['deskripsi'] ?? '-'); ?></td>
                        <td>
                            <?= !empty($row['status_aktif']) ? 'Aktif' : 'Tidak Aktif'; ?>
                        </td>
                    </tr>
                    <?php
                        endwhile;
                    else:
                    ?>
                    <tr>
                        <td colspan="6" class="text-center">
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
```