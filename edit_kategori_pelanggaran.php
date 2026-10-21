
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'includes/cek_session.php';
require_once 'config/koneksi.php';

if (!isset($koneksi) || !($koneksi instanceof mysqli)) {
    die('Koneksi database tidak tersedia.');
}

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$error = '';
$id = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);

if ($id <= 0) {
    header('Location: kelola_kategori_pelanggaran.php');
    exit;
}

// AMBIL DATA KATEGORI
try {
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
        die('Data kategori pelanggaran tidak ditemukan.');
    }
} catch (mysqli_sql_exception $e) {
    die('Gagal mengambil data kategori: ' .
        htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'));
}

$nama = $data['nama'];
$deskripsi = $data['deskripsi'] ?? '';
$status_aktif = (int) $data['status_aktif'];

// PROSES SIMPAN PERUBAHAN
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama'] ?? '');
    $deskripsi = trim($_POST['deskripsi'] ?? '');
    $statusInput = $_POST['status_aktif'] ?? '';

    if ($nama === '') {
        $error = 'Nama kategori wajib diisi.';
    } elseif (!in_array((string) $statusInput, ['0', '1'], true)) {
        $error = 'Status aktif tidak valid.';
    } else {
        $status_aktif = (int) $statusInput;

        try {
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

            $stmt->execute();
            $stmt->close();

            $_SESSION['pesan'] =
                'Kategori pelanggaran berhasil diperbarui!';

            header('Location: kelola_kategori_pelanggaran.php');
            exit;

        } catch (mysqli_sql_exception $e) {
            $error = 'Gagal memperbarui data: ' . $e->getMessage();
        }
    }
}

function e($value) {
    return htmlspecialchars(
        (string) $value,
        ENT_QUOTES,
        'UTF-8'
    );
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
                        <?= e($error) ?>
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
                            value="<?= e($nama) ?>"
                            required>
                    </div>

                    <div class="form-group">
                        <label for="deskripsi">Deskripsi</label>
                        <textarea
                            id="deskripsi"
                            name="deskripsi"
                            class="form-control"
                            rows="4"><?= e($deskripsi) ?></textarea>
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
