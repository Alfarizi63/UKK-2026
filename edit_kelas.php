
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

// Khusus admin
if (($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: dashboard.php');
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('ID kelas tidak valid.');
}

$id = (int) $_GET['id'];
$pesan = '';

$stmt = mysqli_prepare($koneksi, "SELECT * FROM t_kelas WHERE id = ?");

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    die('Data kelas tidak ditemukan.');
}

$nama = $data['nama'];
$jurusan = $data['jurusan'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nama = trim($_POST['nama'] ?? '');
    $jurusan = trim($_POST['jurusan'] ?? '');

    if ($nama === '') {
        $pesan = 'Nama kelas wajib diisi.';
    } else {

        $stmt = mysqli_prepare(
            $koneksi,
            "UPDATE t_kelas SET nama = ?, jurusan = ? WHERE id = ?"
        );

        mysqli_stmt_bind_param($stmt, "ssi", $nama, $jurusan, $id);

        if (mysqli_stmt_execute($stmt)) {
            header("Location: kelola_kelas.php");
            exit;
        } else {
            $pesan = 'Gagal mengubah kelas: ' . mysqli_error($koneksi);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Kelas</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f8f9fa;
            font-family: Arial, sans-serif;
        }

        .main-content {
            margin-left: 250px;
            padding: 25px;
            min-width: 0;
        }

        .form-card {
            background: white;
            border-radius: 12px;
            padding: 28px;
            box-shadow: 0 3px 12px rgba(0,0,0,0.06);
            max-width: 850px;
        }

        .page-title {
            font-weight: 700;
            color: #343a40;
        }

        .form-label {
            font-weight: 600;
            margin-bottom: 7px;
        }

        .form-control {
            padding: 10px 12px;
            border-radius: 7px;
        }

        .form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13,110,253,.12);
        }

        .btn {
            padding: 9px 18px;
            border-radius: 7px;
        }

        @media(max-width: 767px) {
            .main-content {
                margin-left: 0;
                padding: 15px;
            }

            .form-card {
                padding: 18px;
            }
        }
    </style>
</head>

<body>

<?php include 'includes/sidebar.php'; ?>

<div class="main-content">

    <div class="mb-4">
        <h3 class="page-title">Edit Data Kelas</h3>
        <p class="text-muted mb-0">
            Perbarui informasi kelas di bawah ini.
        </p>
    </div>

    <div class="form-card">

        <?php if ($pesan !== ''): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($pesan); ?>
            </div>
        <?php endif; ?>

        <form method="POST">

            <div class="mb-3">
                <label class="form-label">Nama Kelas</label>

                <input
                    type="text"
                    name="nama"
                    class="form-control"
                    value="<?= htmlspecialchars($nama); ?>"
                    placeholder="Masukkan nama kelas"
                    required
                >
            </div>

            <div class="mb-4">
                <label class="form-label">Jurusan</label>

                <input
                    type="text"
                    name="jurusan"
                    class="form-control"
                    value="<?= htmlspecialchars($jurusan); ?>"
                    placeholder="Masukkan jurusan"
                >
            </div>

            <div class="d-flex justify-content-end gap-2">

                <a href="kelola_kelas.php" class="btn btn-secondary">
                    Kembali
                </a>

                <button type="submit" class="btn btn-primary">
                    Simpan Perubahan
                </button>

            </div>

        </form>

    </div>

</div>

</body>
</html>
