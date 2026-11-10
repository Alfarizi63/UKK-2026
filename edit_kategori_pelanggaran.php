
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'includes/cek_session.php';
require_once 'config/koneksi.php';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if (!isset($koneksi) || !($koneksi instanceof mysqli)) {
    die('Koneksi database tidak tersedia.');
}

// Khusus admin
if (($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: dashboard.php');
    exit;
}

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

// Fungsi keamanan output
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

    <!-- Bootstrap 4.1.3 -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body {
            background: #f5f6fa;
            font-family: Arial, sans-serif;
        }

        .main-content {
            margin-left: 250px;
            padding: 30px;
            min-width: 0;
        }

        .page-title {
            font-size: 24px;
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
        }

        .page-subtitle {
            color: #777;
            margin-bottom: 25px;
        }

        .form-card {
            background: #fff;
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
            max-width: 850px;
        }

        .card-header {
            background: #fff;
            border-bottom: 1px solid #eee;
            padding: 18px 22px;
            font-weight: 600;
        }

        .card-body {
            padding: 25px;
        }

        .form-group label {
            font-weight: 600;
            color: #444;
        }

        .form-control {
            border-radius: 6px;
            padding: 10px 12px;
            height: auto;
        }

        textarea.form-control {
            min-height: 120px;
        }

        .btn {
            border-radius: 6px;
            padding: 9px 18px;
        }

        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                padding: 15px;
            }

            .card-body {
                padding: 18px;
            }
        }
    </style>
</head>

<body>

    <!-- SIDEBAR -->
    <?php include 'includes/sidebar.php'; ?>

    <!-- KONTEN UTAMA -->
    <div class="main-content">

        <div class="mb-4">

            <h4 class="page-title">
                <i class="bi bi-pencil-square"></i>
                Edit Kategori Pelanggaran
            </h4>

            <p class="page-subtitle">
                Perbarui informasi kategori pelanggaran.
            </p>

        </div>

        <div class="card form-card">

            <div class="card-header">
                <i class="bi bi-card-list"></i>
                Form Edit Kategori
            </div>

            <div class="card-body">

                <?php if ($error !== ''): ?>

                    <div class="alert alert-danger alert-dismissible fade show">

                        <?= e($error); ?>

                        <button type="button"
                                class="close"
                                data-dismiss="alert">
                            <span>&times;</span>
                        </button>

                    </div>

                <?php endif; ?>

                <form method="POST"
                      action="edit_kategori_pelanggaran.php?id=<?= $id; ?>"
                      id="formKategori">

                    <input
                        type="hidden"
                        name="id"
                        value="<?= $id; ?>"
                    >

                    <!-- Nama Kategori -->
                    <div class="form-group">

                        <label for="nama">Nama Kategori</label>

                        <input
                            type="text"
                            id="nama"
                            name="nama"
                            class="form-control"
                            value="<?= e($nama); ?>"
                            maxlength="100"
                            placeholder="Masukkan nama kategori"
                            required
                        >

                    </div>

                    <!-- Deskripsi -->
                    <div class="form-group">

                        <label for="deskripsi">Deskripsi</label>

                        <textarea
                            id="deskripsi"
                            name="deskripsi"
                            class="form-control"
                            rows="4"
                            placeholder="Masukkan deskripsi kategori"
                        ><?= e($deskripsi); ?></textarea>

                    </div>

                    <!-- Status -->
                    <div class="form-group">

                        <label for="status_aktif">
                            Status Kategori
                        </label>

                        <select
                            id="status_aktif"
                            name="status_aktif"
                            class="form-control"
                            required
                        >

                            <option value="1"
                                <?= $status_aktif === 1 ? 'selected' : ''; ?>>
                                Aktif
                            </option>

                            <option value="0"
                                <?= $status_aktif === 0 ? 'selected' : ''; ?>>
                                Tidak Aktif
                            </option>

                        </select>

                    </div>

                    <hr>

                    <!-- Tombol -->
                    <div class="d-flex justify-content-between">

                        <a href="kelola_kategori_pelanggaran.php"
                           class="btn btn-secondary">

                            <i class="bi bi-arrow-left"></i>
                            Kembali

                        </a>

                        <button type="submit" class="btn btn-primary">

                            <i class="bi bi-save"></i>
                            Simpan Perubahan

                        </button>

                    </div>

                </form>

            </div>
        </div>

    </div>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>

</body>
</html>
