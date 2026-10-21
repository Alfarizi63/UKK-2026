
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
    header('Location: kelola_jenis_pelanggaran.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama'] ?? '');
    $poin = filter_var($_POST['poin'] ?? null, FILTER_VALIDATE_INT);
    $deskripsi = trim($_POST['deskripsi'] ?? '');

    if ($nama === '') {
        $error = 'Nama pelanggaran wajib diisi.';
    } elseif ($poin === false || $poin === null || $poin < 1) {
        $error = 'Poin harus berupa angka minimal 1.';
    } else {
        $stmt = $koneksi->prepare(
            "UPDATE t_pelanggaran
             SET nama = ?, poin = ?, deskripsi = ?
             WHERE id = ?"
        );

        $stmt->bind_param('sisi', $nama, $poin, $deskripsi, $id);

        if ($stmt->execute()) {
            $stmt->close();
            $_SESSION['pesan'] = 'Jenis pelanggaran berhasil diperbarui!';
            header('Location: kelola_jenis_pelanggaran.php');
            exit;
        }

        $error = 'Gagal memperbarui data: ' . $stmt->error;
        $stmt->close();
    }
}

$stmt = $koneksi->prepare(
    "SELECT id, nama, poin, deskripsi
     FROM t_pelanggaran
     WHERE id = ?"
);

$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();
$stmt->close();

if (!$data) {
    die('Data jenis pelanggaran tidak ditemukan. ID: ' . $id);
}

$nama = $_POST['nama'] ?? $data['nama'];
$poin = $_POST['poin'] ?? $data['poin'];
$deskripsi = $_POST['deskripsi'] ?? ($data['deskripsi'] ?? '');
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Jenis Pelanggaran</title>
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
                <h3 class="mb-4">Edit Jenis Pelanggaran</h3>

                <?php if ($error !== ''): ?>
                    <div class="alert alert-danger">
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>

                <form method="POST"
                      action="edit_jenis_pelanggaran.php?id=<?= $id ?>">

                    <input type="hidden" name="id" value="<?= $id ?>">

                    <div class="form-group">
                        <label for="nama">Nama Pelanggaran</label>
                        <input type="text" id="nama" name="nama"
                               class="form-control"
                               maxlength="150"
                               value="<?= htmlspecialchars($nama) ?>"
                               required>
                    </div>

                    <div class="form-group">
                        <label for="poin">Poin Pelanggaran</label>
                        <input type="number" id="poin" name="poin"
                               class="form-control"
                               min="1"
                               value="<?= htmlspecialchars((string) $poin) ?>"
                               required>
                    </div>

                    <div class="form-group">
                        <label for="deskripsi">Keterangan</label>
                        <textarea id="deskripsi" name="deskripsi"
                                  class="form-control"
                                  rows="4"><?= htmlspecialchars($deskripsi) ?></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        Simpan Perubahan
                    </button>

                    <a href="kelola_jenis_pelanggaran.php"
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
