```php
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'includes/cek_session.php';
include 'config/koneksi.php';

if (!isset($koneksi) || !($koneksi instanceof mysqli)) {
    die('Koneksi database tidak tersedia.');
}

$id = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);

if ($id <= 0) {
    header('Location: kelola_kategori_pelanggaran.php');
    exit;
}

$error = '';
$berhasil = false;

// Ambil data kategori berdasarkan ID.
$stmt = $koneksi->prepare(
    "SELECT id, nama, deskripsi, status_aktif
     FROM t_pelanggaran_kategori
     WHERE id = ?"
);

$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();
$stmt->close();

if (!$data) {
    die('Data kategori pelanggaran tidak ditemukan. ID: ' . $id);
}

$nama = $data['nama'];
$deskripsi = $data['deskripsi'] ?? '';
$status_aktif = (int) $data['status_aktif'];

// Proses simpan perubahan.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama'] ?? '');
    $deskripsi = trim($_POST['deskripsi'] ?? '');
    $status_aktif = (int) ($_POST['status_aktif'] ?? -1);

    if ($nama === '') {
        $error = 'Nama kategori wajib diisi.';
    } elseif (!in_array($status_aktif, [0, 1], true)) {
        $error = 'Status aktif tidak valid.';
    } else {
        $stmt = $koneksi->prepare(
            "UPDATE t_pelanggaran_kategori
             SET nama = ?, deskripsi = ?, status_aktif = ?
             WHERE id = ?"
        );

        $stmt->bind_param(
            'ssii',
            $nama,
            $deskripsi,
            $status_aktif,
            $id
        );

        if ($stmt->execute()) {
            $stmt->close();

            $_SESSION['pesan'] = 'Kategori pelanggaran berhasil diperbarui!';
            header('Location: kelola_kategori_pelanggaran.php');
            exit;
        }

        $error = 'Gagal memperbarui data: ' . $stmt->error;
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Kategori Pelanggaran</title>

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">

    <style>
        body {
            background: #f4f6f9;
        }

        .konten {
            max-width: 800px;
            margin: 40px auto;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="konten">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">

                <h3 class="mb-4">Edit Kategori Pelanggaran</h3>

                <?php if ($error !== ''): ?>
                    <div class="alert alert-danger">
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>

                <form method="POST"
                      action="edit_kategori_pelanggaran.php?id=<?= $id ?>">

                    <input type="hidden" name="id" value="<?= $id ?>">

                    <div class="form-group">
                        <label for="nama">Nama Kategori</label>
                        <input
                            type="text"
                            id="nama"
                            name="nama"
                            class="form-control"
                            value="<?= htmlspecialchars($nama) ?>"
                            required>
                    </div>

                    <div class="form-group">
                        <label for="deskripsi">Deskripsi</label>
                        <textarea
                            id="deskripsi"
                            name="deskripsi"
                            class="form-control"
                            rows="4"><?= htmlspecialchars($deskripsi) ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="status_aktif">Status Aktif</label>
                        <select
                            id="status_aktif"
                            name="status_aktif"
                            class="form-control"
                            required>
                            <option value="1"
                                <?= $status_aktif === 1 ? 'selected' : '' ?>>
                                Aktif
                            </option>
                            <option value="0"
                                <?= $status_aktif === 0 ? 'selected' : '' ?>>
                                Tidak Aktif
                            </option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        Simpan Perubahan
                    </button>

                    <a href="kelola_kategori_pelanggaran.php"
                       class="btn btn-secondary">
                        Batal
                    </a>

                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>
```